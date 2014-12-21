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

        $current = self::where('date', $date)->max('try_count');
        return $current ? $current + 1 : 1;
    }

    public static function getLastRandomLogs()
    {
        $date = current_date();
        $currentTryCount = self::getNextTryCount() - 1;
        $lastLogs = RandomMealLog::where('date', $date)->where('try_count', $currentTryCount)->get();

        return $lastLogs;
    }
}