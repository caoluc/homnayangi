<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateUsers extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'update-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update users list';

    /**
     * Create a new command instance.
     *
     * @return \UpdateUsers
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
        $response = $chatwork->getMembers();
        $members = json_decode($response);
        foreach ($members as $member) {
            $data = obj_to_array($member);
            $user = UserService::createOrUpdate($data);
            $this->info("{$user->id}. {$user->name}");
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
