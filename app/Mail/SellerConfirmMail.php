<?php

// app/Mail/SellerConfirmMail.php

// app/Mail/SellerConfirmMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;

    public function __construct($seller)
    {
        $this->seller = $seller;
    }

    public function build()
    {
        $subject = 'Seller Confirmation'; // Set your email subject

        // Build the email content as plain text
        $message = "Dear admin,\n\n";
        $message .= "A new seller has registered. Please confirm the seller by clicking the link below:\n";
        $message .= url('/seller/confirm/' . $this->seller->id) . "\n\n";
        $message .= "Thank you.";
        
        return $this->view('emails.seller_confirm')
            ->subject($subject)
            ->with([
                'message' => $message,
            ]);
        
        }
}

