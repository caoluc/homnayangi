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
        $randoms = MealService::randomMeal();
        $text = "\n  ";
        foreach ($randoms as $key => $random) {
            $text .= ($key + 1) . ':  ' . $random . " \n  ";
        }

        $message = '[info][title]' . current_date() . '[/title]Hôm nay chúng ta sẽ ăn: ' . $text . 'Xem thêm thông tin tại http://truanayangi.thangtd.com[/info]';
        $sendMessage = $this->option('send-message');
        if ($sendMessage) {
            $roomId = Config::get('chatwork.room');
            $apiToken = isset($_ENV['chatwork_api_token']) ? $_ENV['chatwork_api_token'] : '';
            $chatwork = new Chatwork($apiToken, $roomId, $message);
            $response = $chatwork->sendMessage();
            $this->info($response);
        }

        $this->info($message);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['send-message', null, InputOption::VALUE_NONE, 'Do not send Chatwork Message'],
        ];
    }
}
