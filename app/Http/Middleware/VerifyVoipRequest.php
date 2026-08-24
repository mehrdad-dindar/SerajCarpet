<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyVoipRequest
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = config('services.voip.api_key');
        $allowedIp = config('services.voip.allowed_ip');

        // بررسی API Key در هدر درخواست
        if ($request->header('X-API-KEY') !== $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid API Key'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // بررسی IP (اختیاری اما به شدت توصیه می‌شود)
        // اگر در محیط لوکال یا تست هستید می‌توانید این بخش را موقتا کامنت کنید
//        if ($allowedIp && $request->ip() !== $allowedIp) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Forbidden: IP not whitelisted'
//            ], Response::HTTP_FORBIDDEN);
//        }

        return $next($request);
    }
}
