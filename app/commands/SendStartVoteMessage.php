<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SendStartVoteMessage extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'send-start-vote-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users that can vote from now.';

    /**
     * Create a new command instance.
     *
     * @return \SendStartVoteMessage
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
        $users = User::all();
        $message = '';
        foreach ($users as $user) {
            $message .= "[To:{$user->account_id}] {$user->name}\n";
        }
        $message .= "Mời mọi người vote cho bữa ăn ngày hôm nay. Cấu trúc vote là\n" .
                 "[info]#vote {meal_id or meal_name}[/info]\n" .
                 "Ví dụ như #vote 1 hoặc #vote Bún Chả\n" .
                 "Danh sách các món ăn của ngày hôm nay như sau: \n";

        $meals = MealService::getAvailableMeals();
        foreach ($meals as $meal) {
            $message .= "{$meal->id}. {$meal->name}\n";
        }

        $votePoint = Config::get('chatwork.vote_point');
        $message .= "Mỗi vote sẽ làm tăng số điểm của món ăn thêm $votePoint điểm. Kết quả random sẽ được công bố vào 11h hôm nay.\n" .
                    "Xem thêm thông tin chi tiết tại http://homnayangi.thangtd.com";
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
