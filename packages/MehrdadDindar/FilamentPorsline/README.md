# Filament Porsline Package

یک پکیج FilamentPHP برای ادغام با API پرسلاین برای مدیریت نظرسنجی‌ها.

## ویژگی‌ها

- ✅ مدیریت نظرسنجی‌ها از طریق FilamentPHP
- ✅ مدیریت پاسخ‌های نظرسنجی
- ✅ ارسال خودکار پیامک بعد از تحویل سفارش
- ✅ پشتیبانی از API پرسلاین
- ✅ رابط کاربری فارسی
- ✅ قابلیت تنظیم تاخیر ارسال پیامک

## نصب

### ۱. نصب پکیج

```bash
composer require mehrdad-dindar/filament-porsline
```

### ۲. انتشار فایل‌های کانفیگ

```bash
php artisan vendor:publish --tag=filament-porsline-config
```

### ۳. اجرای Migration ها

```bash
php artisan migrate
```

### ۴. تنظیم متغیرهای محیطی

در فایل `.env` متغیرهای زیر را اضافه کنید:

```env
# Porsline API Configuration
PORSLINE_API_KEY=your_api_key_here
PORSLINE_API_BASE_URL=https://survey.porsline.ir/api
PORSLINE_API_TIMEOUT=30

# SMS Configuration
PORSLINE_SMS_ENABLED=true
PORSLINE_SMS_PATTERN_CODE=250000
PORSLINE_SMS_DELAY_DAYS=2

# Email Configuration
PORSLINE_EMAIL_ENABLED=false
```

## استفاده

### مدیریت نظرسنجی‌ها

پس از نصب، بخش "نظرسنجی" در پنل ادمین FilamentPHP اضافه می‌شود که شامل:

- **نظرسنجی‌ها**: مدیریت نظرسنجی‌های موجود
- **پاسخ‌های نظرسنجی**: مشاهده پاسخ‌های دریافتی

### ارسال خودکار پیامک

پکیج به صورت خودکار ۲ روز بعد از تحویل سفارش، پیامک نظرسنجی به مشتری ارسال می‌کند.

### تست اتصال

برای تست اتصال به API پرسلاین:

```bash
php artisan porsline:test-connection
```

## تنظیمات

### کانفیگ پیش‌فرض

```php
return [
    'api' => [
        'base_url' => env('PORSLINE_API_BASE_URL', 'https://survey.porsline.ir/api'),
        'api_key' => env('PORSLINE_API_KEY'),
        'timeout' => env('PORSLINE_API_TIMEOUT', 30),
    ],
    
    'survey' => [
        'default_language' => 2, // 1: English, 2: Persian, 3: Turkish, 4: Arabic
        'auto_create_survey' => true,
        'survey_template' => [
            'name' => 'نظرسنجی رضایت مشتری',
            'description' => 'لطفاً نظرات خود را در مورد خدمات ما ارائه دهید',
        ],
    ],
    
    'sms' => [
        'enabled' => env('PORSLINE_SMS_ENABLED', true),
        'pattern_code' => env('PORSLINE_SMS_PATTERN_CODE', 250000),
        'delay_days' => env('PORSLINE_SMS_DELAY_DAYS', 2),
        'message_template' => 'سلام {customer_name}، لطفاً در نظرسنجی ما شرکت کنید: {survey_url}',
    ],
];
```

## API پرسلاین

این پکیج از API پرسلاین برای مدیریت نظرسنجی‌ها استفاده می‌کند. برای اطلاعات بیشتر به [مستندات API پرسلاین](https://developers.porsline.ir) مراجعه کنید.

## پشتیبانی

برای گزارش مشکلات یا درخواست ویژگی‌های جدید، لطفاً یک Issue ایجاد کنید.

## مجوز

این پکیج تحت مجوز MIT منتشر شده است. 