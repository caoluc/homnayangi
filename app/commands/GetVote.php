<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;

class GetVote extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'get-vote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get vote from members in Chatwork';

    /**
     * Create a new command instance.
     *
     * @return \GetVote
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
        $roomId = Config::get('chatwork.room');
        $apiToken = isset($_ENV['chatwork_api_token']) ? $_ENV['chatwork_api_token'] : '';
        $chatwork = new Chatwork($apiToken, $roomId);
        $messages = json_decode($chatwork->getMessages());
        if (!$messages) {
            return;
        }
        foreach ($messages as $message) {
            $data = obj_to_array($message);
            $dt = Carbon::createFromTimestamp($data['send_time']);
            $startTime = Config::get('chatwork.vote_start_time');
            $endTime = Config::get('chatwork.vote_end_time');
            $time = $dt->format('H:i:s');
            if (!($startTime <= $time && $time <= $endTime)) {
                continue;
            }

            $meal = VoteService::getVote($data['body']);
            if ($meal) {
                $user = User::where('account_id', $data['account']['account_id'])->first();
                if ($user->isBanned()) {
                    $message = "Account của bạn đang bị Ban nên vote không có hiệu lực. :#)\nHãy Ngọt Lòng box để được Unban!\n";
                    $this->info($message);
                    $chatwork->setMessage($message);
                    $chatwork->sendReply($data['account']['account_id'], $data['account']['name'], $data['message_id']);
                } else {
                    $vote = VoteService::createOrUpdateVote($user, $meal, $data);
                    if ($vote) {
                        $message = "Bạn đã vote thành công cho món ăn: '{$meal->name}'.\nXem thêm thông tin chi tiết tại http://homnayangi.thangtd.com/";
                        $this->info($message);
                        $chatwork->setMessage($message);
                        $chatwork->sendReply($data['account']['account_id'], $data['account']['name'], $data['message_id']);
                    }
                }
            }
        }
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
        return [];
    }

}
