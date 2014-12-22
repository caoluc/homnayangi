<?php

class Meal extends BaseModel
{
    protected $table = 'meals';
    protected $guarded = ['id'];

    private $chosenThisWeek = null;
    private $currentMealPoint = null;
    private $votePoints = null;

    public function mealLogs()
    {
        return $this->hasMany('MealLog');
    }

    public function votes()
    {
        return $this->hasMany('Vote');
    }

    public function mealPoints()
    {
        return $this->hasMany('MealPoint');
    }

    public function getTodayMealPoints()
    {
        $time = current_time();
        return $this->mealPoints()->where('date', $time->toDateString())->first();
    }

    public function getLastMealPoints()
    {
        return $this->mealPoints()->orderBy('date', 'desc')->first();
    }

    public function randomMealLogs()
    {
        return $this->hasMany('RandomMealLog');
    }

    public function getTodayPoint()
    {
        $todayMealPoint = $this->getTodayMealPoints();
        if ($todayMealPoint) {
            return $todayMealPoint->point;
        } else {
            $mealPoint = $this->attachNewMealPoint();
            return $mealPoint->point;
        }
    }

    public function attachNewMealPoint($point = null, $date = null)
    {
        if ($point === null) {
            $last = $this->getLastMealPoints();
            if ($last) {
                $point = $last->point;
            } else {
                $point = $this->start_point;
            }
        }

        $date = current_date($date);

        $mealPoint = MealPoint::create([
            'point' => $point,
            'date' => $date,
            'meal_id' => $this->id,
        ]);
        return $mealPoint;
    }

    public function createRandomLog($tryCount = 1, $priority = 1, $date = null)
    {
        $date = current_date($date);

        $randomMealLog = RandomMealLog::create([
            'date' => $date,
            'meal_id' => $this->id,
            'priority' => $priority,
            'try_count' => $tryCount,
        ]);

        return $randomMealLog;
    }

    public function createOrUpdateLog($date = null)
    {
        $date = current_date($date);

        $mealLog = MealLog::where('date', $date)->first();
        $mealPoint = $this->getTodayMealPoints();
        if (!$mealLog) {
            $mealLog = MealLog::create([
                'date' => $date,
                'meal_id' => $this->id,
            ]);
            MealPoint::updatePoint($date, $this->id);
        } elseif ($mealLog->meal_id != $this->id) {
            $oldMealPoint = $mealLog->meal->getTodayMealPoints();
            $mealLog->meal->decrement('count');
            $oldMealPoint->increment('point', $mealLog->meal->step_point);
            $mealLog->update(['meal_id' => $this->id]);
            $mealPoint->decrement('point', $this->step_point);
        }
        $this->increment('count');

        return $mealLog;
    }

    public function hasBeenChosenThisWeek()
    {
        if ($this->chosenThisWeek === null) {
            $this->chosenThisWeek = $this->mealLogs()->thisWeek()->count();
        }

        return $this->chosenThisWeek;
    }

    public function getVotePoint($chosen = false)
    {
        if (!$chosen) {
            if ($this->votePoints === null) {
                $votePoint = Config::get('chatwork.vote_point');
                $count = $this->votes()->today()->count();
                $this->votePoints = $count * $votePoint;
            }

            return $this->votePoints;
        }

        return 0;
    }

    public function getCurrentPoint($notCountVotes = false)
    {
        if ($this->currentMealPoint === null) {
            $this->currentMealPoint = $this->getTodayPoint();
            if (!$notCountVotes) {
                $votePoints = $this->getVotePoint();
                $this->currentMealPoint = $this->currentMealPoint + $votePoints;
            }
        }

        return $this->currentMealPoint;
    }

    public static function boot()
    {
        parent::boot();

        // After created
        static::created(function ($meal) {
            $meal->attachNewMealPoint();
        });
    }
}