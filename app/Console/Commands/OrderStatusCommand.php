<?php

namespace App\Console\Commands;

use App\Jobs\OrderRejectJob;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class OrderStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check order status for auto-reject';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $orders = Order::query()
                ->where('status', 'awaiting')
                ->whereDate('created_at', '>', Carbon::now()->subDay()->toDateString())
                ->with('products')
                ->get();

            foreach ($orders as $order) {
                OrderRejectJob::dispatch($order);
            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
        return Command::SUCCESS;
    }
}
