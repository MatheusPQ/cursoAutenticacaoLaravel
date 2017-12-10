<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class MyResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable){
        return (new MailMessage)
        //->subject().. titulo do email
        ->line('Você está recebendo esta mensagem porque uma requisição de redefinição de senha.....')
        ->action('Reset Password', url(config('app.url').route('password.reset', $this->token, false))) //Botão
        ->line('If you did not request a password reset, no further action is required.');

    }
}