<?php

namespace App\Console\Commands;

use App\Logic\MerchantRepository;

use Illuminate\Console\Command;

class DowngradeExpiredSubscriber extends Command
{
    protected $merchantRepository;
	
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriber:downgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downgrade Expired Subscribers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MerchantRepository $merchantRepository)
    {
        parent::__construct();
		$this->merchantRepository = $merchantRepository;
		//$this->merchantRepository->downgradeExpiredSubscribers();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->merchantRepository->downgradeExpiredSubscribers();
    }
}
