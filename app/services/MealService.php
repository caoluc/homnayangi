<?php

use BiasedRandom\Randomizer;
use BiasedRandom\Element;

class MealService
{
    public static function randomMeal()
    {
        $tryCount = RandomMealLog::getNextTryCount();
        $randomizer = new Randomizer();
        $meals = Meal::all();
        foreach ($meals as $meal) {
            if ($meal->hasBeenChosenThisWeek()) {
                continue;
            }
            $element = new Element($meal->name, $meal->getCurrentPoint());
            $randomizer->add($element);
        }
        $results = [];
        $max = Config::get('random.max');
        while (count($results) < $max) {
            $random = $randomizer->get();
            if (!in_array($random, $results)) {
                $results[] = $random;
            }
        }

        foreach ($results as $key => $result) {
            $found = false;
            foreach ($meals as $meal) {
                if ($found) {
                    continue;
                }
                if ($meal->name === $result) {
                    $meal->createRandomLog($tryCount, $key + 1);
                    $found = true;
                }
            }
        }

        return $results;
    }

    public static function chooseMeal($meal)
    {
        if (is_int($meal)) {
            $meal = Meal::find($meal);
        }

        if (!$meal || !($meal instanceof Meal)) {
            return false;
        }

        $meal->createLog();
    }

    public static function createMealPoints()
    {
        $meals = Meal::all();
        foreach ($meals as $meal) {
            if (!$meal->getTodayMealPoints()) {
                $meal->attachNewMealPoint();
            }
        }
    }

    public static function hasBeenChosenToday()
    {
        $count = MealLog::where('date', current_date())->count();
        return $count != 0;
    }

    public static function getAvailableMeals()
    {
        $meals = Meal::all();
        $results = [];
        foreach ($meals as $meal) {
            if (!$meal->hasBeenChosenThisWeek()) {
                $results[] = $meal;
            }
        }

        return $results;
    }
}