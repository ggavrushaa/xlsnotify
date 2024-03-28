<?php

namespace App\Console\Commands;

use App\Models\Manager;
use Illuminate\Console\Command;
use App\Models\OrderSalesInvoice;
use Illuminate\Support\Facades\Mail;
use App\Notifications\LogNotification;
use App\Services\ManagerReportService;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UnsignedManagerNotification;

class SendForUnsignedManagerNotifications extends Command
{
    
    protected $signature = 'unsigned:notifications';

    protected $description = 'Command for sending notifications for unsigned managers';

    protected $logMessages = [];

    public function handle()
    {
       $this->warn('In proccess');
        
        $this->getUnsignedManagers();

          // Проверяем
          if (!empty($this->logMessages)) {
            $logMessage = implode("\n", $this->logMessages); // Преобразуем массив в одну строку
            // Отправляем 
            Notification::route('mail', env('RESPONSIBLE_EMAIL', 'gavrilnikitin2020@gmail.com'))->notify(new LogNotification($logMessage));
        }

        $this->info('Finished');
    }

    public function getUnsignedManagers()
{
    //прилетала ошибка с памятью
    ini_set('memory_limit', '556M');
    $reportService = new ManagerReportService();

    $salesInvoices = OrderSalesInvoice::with('order.contract.manager')
    ->where('status', '!=', 'customer-signed')
    ->where('date_sale', '>', '2023-12-31')
    ->get();

    $managerInvoices = $salesInvoices->groupBy(function ($invoice) {
        if ($invoice->order && $invoice->order->contract) {
            return $invoice->order->contract->manager_id;
        }
        return null;
    });

    foreach ($managerInvoices as $managerId => $invoices) {
        $manager = Manager::find($managerId);
        
        if (!$manager) {
            $errorMessage = "Не найден менеджер с ID {$managerId}";
            $this->error($errorMessage);
            $this->logMessages[] = $errorMessage; // Добавляем сообщение в лог
            continue;
        }
        
        if ($invoices->isEmpty()) {
            $infoMessage = "Все накладные подписаны у менеджера с ID {$managerId}.";
            $this->info($infoMessage);
            continue;
        }
    
        //  XLS файл для неподписанных
        $filePath = $reportService->generateXlsForManager($invoices);

        if (!filter_var($manager->email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Некорректный email адрес для менеджера с ID {$managerId}.";
            $this->error($errorMessage);
            $this->logMessages[] = $errorMessage; // Добавляем сообщение в лог
            continue; 
        }
        $manager->notify(new UnsignedManagerNotification($filePath));
    }
  }
}
