<?php

namespace App\Observers;


use App\Domain\Entities\MonthlySale;
use App\Domain\Entities\Order;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class OrderObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $sumPrice = $order->sum_price;
        $yearMonth = $order->created_at->format('Ym');
        $monthlySale = MonthlySale::where('year_month', $yearMonth)->first();
        if (null === $monthlySale) {
            MonthlySale::create([
                'year_month' => $yearMonth,
                'order_count' => 1,
                'amount' => $sumPrice,
            ]);
        } else {
            MonthlySale::where('id', $monthlySale->id)
                ->update([
                    'year_month' => $yearMonth,
                    'order_count' => $monthlySale->order_count + 1,
                    'amount' => $monthlySale->amount + $sumPrice,
                ]);
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if($order->isDirty('sum_price')){
            $sumPrice = $order->sum_price;
            $yearMonth = $order->created_at->format('Ym');
            $monthlySale = MonthlySale::where('year_month', $yearMonth)->first();
            if (null !== $monthlySale) {
                MonthlySale::create([
                    'year_month' => $yearMonth,
                    'order_count' => 1,
                    'amount' => $sumPrice,
                ]);
            } else {
                MonthlySale::where('id', $monthlySale->id)
                    ->update([
                        'year_month' => $yearMonth,
                        'order_count' => $monthlySale->order_count + 1,
                        'amount' => $monthlySale->amount + $sumPrice,
                    ]);
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
