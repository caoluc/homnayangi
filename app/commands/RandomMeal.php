<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RandomMeal extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'random-meal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Random Meal for Today';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $mealArray = ['Bánh tráng', 'bún đậu', 'bún cá', 'lotte', 'bánh đa trộn'];
        $random = '[info]Hôm nay chúng ta sẽ ăn: ' . $mealArray[array_rand($mealArray)]
            . '. Xem thêm thông tin tại http://homnayangi.thangtd.com[/info]';
        $roomId = Config::get('chatwork.room');
        $apiToken = isset($_ENV['chatwork_api_token']) ? $_ENV['chatwork_api_token'] : '';
        $chatwork = new Chatwork($apiToken, $roomId, $random);
        $response = $chatwork->sendMessage();
        $this->info($response);
    }
}
