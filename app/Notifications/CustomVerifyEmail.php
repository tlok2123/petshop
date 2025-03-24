<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Carbon\Carbon;
use Firebase\JWT\JWT;

class CustomVerifyEmail extends BaseVerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Xác thực Email')
            ->line('Nhấn vào nút bên dưới để xác thực email của bạn.')
            ->action('Xác thực Email', $verificationUrl)
            ->line('Nếu bạn không tạo tài khoản này, vui lòng bỏ qua email này.');
    }

    protected function verificationUrl($notifiable)
    {
        // Tạo JWT
        $payload = [
            'id' => $notifiable->id,
            'email' => $notifiable->email,
            'exp' => Carbon::now()->addMinutes(60)->timestamp, // Token hết hạn sau 60 phút
        ];

        $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        // Lấy URL từ config
        $frontendUrl = env('APP_USER_URL', 'http://localhost:8080');

        // URL đúng chuẩn frontend cần: frontend-url/verify-email?token=JWT
        $verificationUrl = "{$frontendUrl}/verify-email?token={$jwt}";

        // Ghi log để debug
        \Log::info("Verification URL: " . $verificationUrl);

        return $verificationUrl;
    }


}
