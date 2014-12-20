<?php

class Meal extends BaseModel
{
    protected $table = 'meals';
    protected $guarded = ['id'];

    public function mealLogs()
    {
        return $this->hasMany('MealLog');
    }

    public function mealPoints()
    {
        return $this->hasMany('MealPoint');
    }
}