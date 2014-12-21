<?php

class HomeController extends BaseController
{

    public function index()
    {
        $this->viewData['randomLogs'] = RandomMealLog::getLastRandomLogs();
        $meals = Meal::all();
        $meals->load('mealLogs', 'mealPoints');
        $this->viewData['meals'] = $meals;
        $this->viewData['mealLogs'] = MealLog::orderBy('date', 'desc')->take(10)->get();

        $maxDate = MealPoint::max('date');
        $mealPoints = MealPoint::where('date', $maxDate)->orderBy('point', 'desc')->get();

        $mealPointsData = [];
        foreach ($mealPoints as $mealPoint) {
            if ($mealPoint->meal->hasBeenChosenThisWeek()) {
                continue;
            }
            $mealPointsData[] = [$mealPoint->meal->name, $mealPoint->point];
        }

        $mealCountData = [];
        foreach ($meals as $meal) {
            $mealCountData[] = [$meal->name, $meal->count];
        }

        $this->viewData['mealPointsData'] = $mealPointsData;
        $this->viewData['mealCountData'] = $mealCountData;
        return View::make('hello', $this->viewData);
    }

}
