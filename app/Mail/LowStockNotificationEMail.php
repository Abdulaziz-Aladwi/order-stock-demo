<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowStockNotificationEMail extends Mailable
{
    use Queueable, SerializesModels;

    protected array $emailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.low_stock_notification')
            ->subject($this->emailData['subject'])
            ->with('data', $this->emailData);
    }
}
