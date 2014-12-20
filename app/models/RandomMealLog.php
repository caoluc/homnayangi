<?php

class RandomMealLog extends BaseModel
{
    protected $table = 'random_meal_logs';
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

    public static function getNextTryCount($date = null)
    {
        $date = current_date($date);

        $current = self::where('date', $date)->orderBy('try_count', 'desc')->select('try_count')->first();
        return $current ? $current->try_count + 1 : 1;
    }
}