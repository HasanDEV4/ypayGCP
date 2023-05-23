<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use function App\Libraries\Helpers\sendEmail;

class SampleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // email notificaion 
        $url  = 'https://networks.ypayfinancial.com/api/mailv1/email_verification.php';
        $body = ['email' => 'kk442242@gmail.com', 'name' => 'Karan Kumar', 'token' => '1234'];
        sendEmail($body,$url);
    }
}
