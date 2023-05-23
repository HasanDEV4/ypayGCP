<?php

namespace App\Console;

use App\Jobs\SampleJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $amc_cust_profile_controller = new \App\Http\Controllers\AmcCustProfileController();
            $amc_cust_profile_controller->kyc_process();
            $amc_cust_profile_controller->accounts_opening_confirmation();
            $amc_cust_profile_controller->akd_kyc();
            // $amc_cust_profile_controller->jsil_kyc_process();
            // $amc_cust_profile_controller->jsil_accounts_opening_confirmation();

            $investmen_controller = new \App\Http\Controllers\InvestmentController();
            $investmen_controller->investment_process();
            $investmen_controller->investment_inquiry();
            $investmen_controller->akd_investment();
            // $investmen_controller->jsil_investment_process();

            $redemption_controller = new \App\Http\Controllers\RedemptionController();
            $redemption_controller->redemption_process();
            $redemption_controller->redemption_inquiry();
            // $redemption_controller->jsil_redemption_process();

            $amc_fund_controller = new \App\Http\Controllers\AmcFundController();
            $amc_fund_controller->update_fund_data();

        })->everyThirtyMinutes();

        $schedule->call(function () {
            $fund_controller = new \App\Http\Controllers\FundController();
            $fund_controller->update_funds_data();
        })->everySixHours();

         $schedule->call(function () {
            $amc_fund_controller = new \App\Http\Controllers\AmcFundController();
            $amc_fund_controller->check_eb_jobs();

        })->everyMinute();


        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
