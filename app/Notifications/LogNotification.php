<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LogNotification extends Notification
{
    use Queueable;

    public $logMessage;

    public function __construct($logMessage)
    {
        $this->logMessage = $logMessage;
    }


    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->from('Команда разработки')
                    ->subject('Лог ошибок')
                    ->greeting('Доброго дня!') 
                    ->line('Ниже представлен лог ошибок:')
                    ->line($this->logMessage)
                    ->line('Пожалуйста, проверьте логи.')
                    ->salutation('Спасибо!'); 
                    
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
