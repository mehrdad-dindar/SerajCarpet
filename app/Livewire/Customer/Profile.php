<?php

namespace App\Livewire\Customer;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("پروفایل کاربری من")]
#[Layout("customer.layouts.app")]
class Profile extends Component
{
    use LivewireAlert;

    public string $name = '';
    public string $phone = '';
    public ?string $phone2 = '';

    protected function rules(): array
    {
        return [
            'name'   => 'required|string|min:3|max:100',
            'phone2' => 'nullable|regex:/^09[0-9]{9}$/',
        ];
    }

    protected $messages = [
        'name.required' => 'نام و نام خانوادگی الزامی است.',
        'phone2.regex'  => 'شماره تماس دوم باید یک شماره موبایل معتبر ۱۱ رقمی باشد.',
    ];

    public function mount(): void
    {
        $customer = auth('customer')->user();
        $this->name = $customer->name ?? '';
        $this->phone = $customer->phone ?? '';
        $this->phone2 = $customer->phone2 ?? '';
    }

    public function save(): void
    {
        $this->validate();

        $customer = auth('customer')->user();
        $customer->update([
            'name'   => $this->name,
            'phone2' => $this->phone2,
        ]);

        $this->alert('success', 'اطلاعات پروفایل شما با موفقیت بروزرسانی شد.');
    }

    public function render()
    {
        return view('livewire.customer.profile', [
            'customer' => auth('customer')->user(),
        ]);
    }
}
