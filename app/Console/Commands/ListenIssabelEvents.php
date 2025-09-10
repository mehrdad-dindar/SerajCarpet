<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PAMI\Client\Impl\ClientImpl as PamiClient;
use PAMI\Message\Event\EventMessage;

class ListenIssabelEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'issabel:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen for Asterisk AMI events from Issabel server';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $options = [
            'host' => env('ISSABEL_HOST', 'your_issabel_ip'),
            'scheme' => 'tcp://',
            'port' => env('ISSABEL_PORT', 5038),
            'username' => env('ISSABEL_USERNAME', 'crmuser'),
            'secret' => env('ISSABEL_PASSWORD', 'your_secure_password'),
            'connect_timeout' => 10,
            'read_timeout' => 30,
        ];

        $this->info('Connecting to Issabel AMI...');

        try {
            $pami = new PamiClient($options);
            $pami->open();

            $pami->registerEventListener(
                function (EventMessage $event) {
                    $this->handleEvent($event);
                },
                function (EventMessage $event) {
                    // فقط رویدادهای Newchannel و Hangup را پردازش می‌کنیم
                    return in_array($event->getName(), ['Newchannel', 'Hangup']);
                }
            );

            $this->info('Listening for AMI events...');

            // حلقه بی‌نهایت برای شنود رویدادها
            while (true) {
                $pami->process();
                usleep(1000); // جلوگیری از مصرف بیش از حد CPU
            }

            $pami->close();
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }

    protected function handleEvent(EventMessage $event)
    {
        $eventName = $event->getName();
        $callerId = $event->getKey('CallerIDNum') ?? 'unknown';
        $callType = $eventName === 'Newchannel' ? 'incoming' : 'outgoing';

        $this->info("Event: $eventName, Caller ID: $callerId, Type: $callType");

        // ارسال رویداد به API
        $response = \Http::post(url('/api/call-incoming'), [
            'caller_id' => $callerId,
            'call_type' => $callType,
            'timestamp' => now()->toDateTimeString(),
        ]);

        if ($response->successful()) {
            $this->info('Event sent to API successfully');
        } else {
            $this->error('Failed to send event to API: ' . $response->body());
        }
    }
}
