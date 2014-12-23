<?php

class MealService
{
    public static function randomMeal()
    {
        $tryCount = RandomMealLog::getNextTryCount();
        $randomizer = new BiasRandom();
        $meals = Meal::all();
        foreach ($meals as $meal) {
            if ($meal->hasBeenChosenThisWeek()) {
                continue;
            }

            $randomizer->addElement($meal->name, $meal->getCurrentPoint());
        }

        $max = Config::get('random.max');
        $results = $randomizer->random($max);

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