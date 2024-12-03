<?php

namespace App\Jobs\OnStockUpdated;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Mail\LowStockNotificationEMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendLowStockEmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Ingredient $ingredient;
    protected $emailData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Ingredient $ingredient, array $emailData)
    {
        $this->ingredient = $ingredient;
        $this->emailData = $emailData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            Mail::to($this->emailData['recipient'])->send(new LowStockNotificationEMail($this->emailData));
            $this->ingredient->update(['email_notification_sent' => true]);
            DB::commit();
        } catch(\Exception $exception) {
            DB::rollBack();
            Log::error('Exception in processing SendLowStockEmailNotificationJob', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace()
            ]);
            throw $exception;
        }
    }
}
