<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateMealPoint extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'create-meal-point';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new meal point for today';

    /**
     * Create a new command instance.
     *
     * @return \CreateMealPoint
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
        MealService::createMealPoints();
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
