<?php

class Vote extends BaseModel
{
    protected $table = 'votes';
    protected $guarded = ['id'];

    protected $fillable = ['user_id', 'meal_id', 'message_id', 'update_time', 'send_time', 'date'];

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

    public function user()
    {
        return $this->belongsTo('User');
    }
}