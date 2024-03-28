<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UnsignedManagerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $filePath; //
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
            $mailMessage = (new MailMessage)
            ->from(config('mail.from.address'), 'AL-KO Company')
            // ->cc('Andrej.Kominek@al-ko.ua') // Копия
            ->subject('Інформування про наявність непідписаних видаткових накладних') // Тема письма
            ->greeting('Доброго дня!') 
            ->line('Інформуємо Вас про наявність непідписаних видаткових накладних з боку партнерів-дилерів, що закріплені за Вами – перелік непідписаних документів в файлі у вкладенні.') // Основной текст
            ->line('У дилерів, які користуються додатком Кабінет дилера, при вході в додаток та кожні 2 тижня щопонеділка формується нагадування про наявність непідписаних документів')
            ->line('Просимо нагадати партнерам про переваги бездокументарного підписання видаткових та попросити скористатися Кабінетом дилера для підписання вже відвантажених видаткових накладних.')
            ->salutation('З повагою, Команда AL-KO'); 

            // Прикрепляем XLS файл
            if ($this->filePath) {
            $mailMessage->attach($this->filePath, [
                'as' => 'Непідписані накладні на ' . date('dmY') . '.xlsx',
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
            }

            return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'filePath' => $this->filePath,
        ];
    }
}
