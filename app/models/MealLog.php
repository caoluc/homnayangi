<?php

class MealLog extends BaseModel
{
    protected $table = 'meal_logs';
    protected $guarded = ['id'];

    public function meal()
    {
        return $this->belongsTo('Meal');
    }
}