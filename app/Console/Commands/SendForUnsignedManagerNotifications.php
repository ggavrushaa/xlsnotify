<?php

namespace App\Console\Commands;

use App\Models\Manager;

use Illuminate\Console\Command;
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
    ini_set('memory_limit', '556M');
    $reportService = new ManagerReportService();

    $managers = Manager::whereHas('contracts.orders.salesInvoices', function ($query) {
        $query->where('status', '!=', 'customer-signed')
              ->where('date_sale', '>', '2023-12-31');
    })->get();

    foreach ($managers as $manager) {
        $invoices = collect();

        foreach ($manager->contracts as $contract) {
            foreach ($contract->orders as $order) {
                foreach ($order->salesInvoices as $invoice) {
                    if ($invoice->status != 'customer-signed' && $invoice->date_sale > '2023-12-31') {
                        $invoices->push($invoice);
                    }
                }
            }
        }

        if ($invoices->isEmpty()) {
            $this->info("No unsigned invoices for manager ID {$manager->id}.");
            continue; 
        }
        // Генерируем XLS файл для неподписанных накладных
        $filePath = $reportService->generateXlsForManager($invoices);
        
        // Отправляем уведомление
        if (!filter_var($manager->email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Invalid email address for manager ID {$manager->id}.");
            continue; // Пропустить отправку уведомления этому менеджеру
        }
        $manager->notify(new UnsignedManagerNotification($filePath));
    }
  }
}
