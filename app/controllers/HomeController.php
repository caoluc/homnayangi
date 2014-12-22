<?php

class HomeController extends BaseController
{

    public function index()
    {
        App::setLocale('vn');
        $date = current_date();
        $chosen = MealService::hasBeenChosenToday();
        $this->viewData['randomLogs'] = RandomMealLog::getLastRandomLogs();
        $meals = Meal::all();
        $meals->load('mealLogs', 'mealPoints');
        $this->viewData['meals'] = $meals;
        $this->viewData['mealLogs'] = MealLog::orderBy('date', 'desc')->take(10)->get();

        $maxDate = MealPoint::max('date');
        $mealPoints = MealPoint::where('date', $maxDate)->get();

        $mealPointsData = [];
        foreach ($mealPoints as $mealPoint) {
            if ($mealPoint->meal->hasBeenChosenThisWeek()) {
                continue;
            }
            $mealPointsData[] = [$mealPoint->meal->name, $mealPoint->meal->getCurrentPoint($chosen)];
        }

        $mealCountData = [];
        foreach ($meals as $meal) {
            $mealCountData[] = [$meal->name, $meal->count];
        }

        $votes = Vote::where('date', $date)->get();
        $votes->load('user', 'meal');

        $this->viewData['votes'] = $votes;
        $this->viewData['mealPointsData'] = $mealPointsData;
        $this->viewData['mealCountData'] = $mealCountData;
        $this->viewData['chosen'] = $chosen;
        return View::make('hello', $this->viewData);
    }

}
