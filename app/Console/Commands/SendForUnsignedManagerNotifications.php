<?php

namespace App\Console\Commands;

use App\Models\Manager;
use App\Models\OrderSalesInvoice;
use Illuminate\Support\Facades\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Services\ManagerReportService;
use App\Notifications\UnsignedManagerNotification;

class SendForUnsignedManagerNotifications extends Command
{
    
    protected $signature = 'unsigned:notifications';

    protected $description = 'Command for sending notifications for unsigned managers';

    public function handle()
    {
       $this->warn('In proccess');
        
        $this->getUnsignedManagers();

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
            $this->error("Manager with ID {$managerId} not found.");
            continue;
        }
        
        if ($invoices->isEmpty()) {
            $this->info("No unsigned invoices for manager ID {$managerId}.");
            continue;
        }
    
        // Генерируем XLS файл для неподписанных накладных
        $filePath = $reportService->generateXlsForManager($invoices);
        
        // Отправляем уведомление
        if (!filter_var($manager->email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Invalid email address for manager ID {$managerId}.");
            continue; 
        }
        
        // Используем Eloquent модель для отправки уведомления
        $manager->notify(new UnsignedManagerNotification($filePath));
    }
  }
}
