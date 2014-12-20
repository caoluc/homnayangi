<?php

class MealPoint extends BaseModel
{
    protected $table = 'meal_points';
    protected $guarded = ['id'];

    public function meal()
    {
        return $this->belongsTo('Meal');
    }

    public function scopeToday($query)
    {
        $time = current_time();
        return $query->where('date', $time->toDateString());
    }

    public static function updatePoint($date, $mealId)
    {
        DB::Table('meal_points')
            ->join('meals', 'meals.id', '=', 'meal_points.meal_id')
            ->where('date', $date)
            ->where('meal_id', '<>', $mealId)
            ->increment('meal_points.point', 'meals.step_point');
    }
}