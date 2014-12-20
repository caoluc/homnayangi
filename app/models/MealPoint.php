<?php

class MealPoint extends BaseModel
{
    protected $table = 'meal_points';
    protected $guarded = ['id'];

    public function meal()
    {
        return $this->belongsTo('Meal');
    }
}