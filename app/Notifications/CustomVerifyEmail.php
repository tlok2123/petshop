<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

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
        return URL::signedRoute(
            'verification.verify',
            ['id' => $notifiable->id, 'hash' => sha1($notifiable->email)],
            now()->addMinutes(60)
        );
    }
}
