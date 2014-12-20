<?php

class MealLog extends BaseModel
{
    protected $table = 'meal_logs';
    protected $guarded = ['id'];

    public function meal()
    {
        return $this->belongsTo('Meal');
    }

    public function scopeToday($query)
    {
        return $query->where('date', current_date());
    }

    public function scopeThisWeek($query)
    {
        $time = current_time();
        $weekStart = $time->startOfWeek()->toDateString();
        $weekEnd = $time->endOfWeek()->toDateString();
        return $query->where('date', '>=', $weekStart)
            ->where('date', '<=', $weekEnd);
    }
}