<?php

namespace Modules\SellerModule\App\Jobs;

use App\Mail\SellerConfirmMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendSellerConfirmMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $seller;
    public function __construct($seller)
    {
        $this->seller = $seller;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to("pramuditaadit2810@gmail.com")->send(new SellerConfirmMail($this->seller));
    }
}
