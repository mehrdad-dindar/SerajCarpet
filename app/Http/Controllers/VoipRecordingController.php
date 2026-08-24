<?php

namespace App\Http\Controllers;

use App\Models\CallLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VoipRecordingController extends Controller
{
    /**
     * دانلود یا استریم فایل ضبط شده از سرور ایزابل
     */
    public function stream(Request $request, CallLog $callLog): mixed
    {
        if (blank($callLog->recording_file)) {
            abort(404, 'فایل ضبط شده‌ای برای این تماس موجود نیست.');
        }

        $issabelHost = env('ISSABEL_HOST');
        $issabelSecret = env('VOIP_API_KEY');

        // آدرس دانلود امن فایل از وب‌سرویس ایزابل
        $fileUrl = "http://{$issabelHost}/recordings/download.php?file=" . urlencode($callLog->recording_file) . "&key=" . $issabelSecret;

        try {
            $response = Http::timeout(30)->get($fileUrl);

            if ($response->successful()) {
                return response($response->body(), 200, [
                    'Content-Type' => 'audio/wav',
                    'Content-Disposition' => 'inline; filename="' . basename($callLog->recording_file) . '"',
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Recording Stream Failed: ' . $e->getMessage());
        }

        abort(404, 'امکان دریافت فایل صوتی از سرور ایزابل میسر نشد.');
    }
}
