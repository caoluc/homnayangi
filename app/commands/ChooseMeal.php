<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ChooseMeal extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'choose-meal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Choose meal command.';

    /**
     * Create a new command instance.
     *
     * @return \ChooseMeal
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
        $mealId = $this->option('meal');
        $date = current_date();
        if ($mealId === false) {
            $tryCount = $this->option('try-count');
            if ($tryCount == -1) {
                $tryCount = RandomMealLog::getNextTryCount() - 1;
            }
            $priority = $this->option('priority');
            if ($tryCount < 1 || $priority < 1) {
                $this->error('Invalid try count or priority options');
                return;
            }
            $randomLog = RandomMealLog::where('date', $date)->where('try_count', $tryCount)->where('priority', $priority)->first();
            $meal = $randomLog->meal;
        } else {
            $meal = Meal::find($mealId);
        }

        if (!$meal) {
            $this->error('Invalid meal');
            return;
        }

        $meal->createOrUpdateLog();
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
            ['meal', null, InputOption::VALUE_OPTIONAL, 'Directly chose meal by id.', false],
            ['try-count', null, InputOption::VALUE_OPTIONAL, 'Choose meal by try count and priority.', -1],
            ['priority', null, InputOption::VALUE_OPTIONAL, 'Choose meal by try count and priority.', 1],
        ];
    }

}
