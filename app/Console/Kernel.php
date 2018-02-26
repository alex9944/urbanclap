<?php

namespace App\Console;

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
        //\App\Console\Commands\DeleteExpiredActivations::class,
		\App\Console\Commands\DowngradeExpiredSubscriber::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('codingo:delete-expired-activations')
                 //->daily();
				 
		$schedule->command('subscriber:downgrade')
                 ->daily();
				 
		//$schedule->command('subscriber:downgrade');
    }
}
