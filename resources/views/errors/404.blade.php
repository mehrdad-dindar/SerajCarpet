<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>صفحه مورد نظر یافت نشد - 404</title>
    <script src="https://cdn.tailwindcss.com "></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">

<div class="max-w-4xl w-full mx-auto bg-white shadow-xl rounded-2xl overflow-hidden flex flex-col md:flex-row">
    <!-- Left Side - Image -->
    <div class="md:w-1/2 bg-blue-50 p-8 flex items-center justify-center">
        <img src="https://picsum.photos/600/400 " alt="فرش تمیز" class="rounded-lg shadow-md w-full h-auto object-cover max-h-[400px]">
    </div>

    <!-- Right Side - Content -->
    <div class="md:w-1/2 p-8 text-center md:text-right space-y-6 flex flex-col justify-center">
        <h1 class="text-6xl font-extrabold text-blue-700">404</h1>
        <h2 class="text-2xl font-bold text-gray-800">صفحه مورد نظر یافت نشد!</h2>
        <p class="text-gray-600 leading-relaxed">
            متاسفیم! به نظر می‌رسد ما نتوانستیم صفحه مورد نظر شما را پیدا کنیم.
            ممکن است لینک منقضی شده یا نادرست باشد.
        </p>

        <div class="mt-6">
            <a href="{{ url()->previous() ?: route('customer.panel.index') }}"
               class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                بازگشت به صفحه قبل
            </a>
        </div>
    </div>
</div>

</body>
</html>
