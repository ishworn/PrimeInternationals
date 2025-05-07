<?php


namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sender;
    public $receiver;
    public $pdfPath;
    public $attachmentOptions;
    public $billings;

    public function __construct($sender, $receiver, $pdfPath,  $attachmentOptions = [])
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->pdfPath = $pdfPath;
        $this->attachmentOptions = $attachmentOptions;
       
    }

    public function build()
    {
        return $this->view('emails.invoice')
                    ->subject('Your Invoice from Prime Gurkha Logistics')
                    ->attach($this->pdfPath, $this->attachmentOptions)
                    ->with([
                        'sender' => $this->sender,
                        'receiver' => $this->receiver,
                      
                    ]);
    }
}
