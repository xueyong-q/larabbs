<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Auth\AuthenticationException;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easysms)
    {
        $captchaData = \Cache::get($request->captcha_key);

        if (!$captchaData) {
            abort(403, '图片验证码失效');
        }

        if (!hash_equals($captchaData['code'], $request->captcha_code)) {
            // 验证码错误就清除缓存
            \Cache::forget($request->captcha_key);
            throw new AuthenticationException('验证码错误');
        }

        $phone = $captchaData['phone'];

        if (!app()->environment('production')) {
            $code = '1234';
        } else {


            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {
                $result = $easysms->send($phone, [
                    'template' => config('easysms.gateways.aliyun.templates.register'),
                    'data' => [
                        'code' => $code
                    ]
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $e) {
                $message = $e->getException('aliyun')->getMessage();
                abort(500, $message ?: '短信发送异常');
            }
        }

        $key = 'verificationCode_' . Str::random(15);
        $expiredAt = now()->addMinutes(5);

        // 缓存验证码 5 分钟
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);
        // 清除验证码缓存
        \Cache::forget($request->captcha_key);
        
        return response()->json([
            'key' => $key,
            'expired_at' => $expiredAt
        ])->setStatusCode(201);
    }
}
