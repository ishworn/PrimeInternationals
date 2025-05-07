<?php
namespace App\Mail;

use App\Models\Sender;
use App\Models\Receiver;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrackingUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $sender;
    public $receiver;

    public function __construct(Sender $sender, Receiver $receiver)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
    } 

    public function build()
    {
        return $this->view('emails.tracking_updated')
                    ->with([
                        'senderName' => $this->sender->senderName,
                        'trackingId' => $this->sender->trackingId,
                        'receiverName' => $this->receiver->receiverName,
                        'receiverCountry' => $this->receiver->receiverCountry,
                    ])
                    ->subject('Tracking Number Updated');
    }
}
