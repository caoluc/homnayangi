<?php

class VoteService
{
    public static function getVote($string)
    {
        $pos = strrpos($string, '@vote ');
        if ($pos === false) {
            return false;
        }

        $end = strrpos($string, "\n", $pos);
        if ($end === false) {
            $end = strlen($string);
        }

        $text = substr($string, $pos + 6, $end);
        if (is_numeric($text)) {
            $meal = Meal::where('id', $text)->first();
        } else {
            $meal = Meal::where('name', $text)->first();
        }
        if ($meal) {
            return $meal;
        }
        return false;
    }

    public static function createOrUpdateVote($user, $meal, $data)
    {
        if (!($user instanceof User) || !($meal instanceof Meal)) {
            return false;
        }

        $date = current_date();
        $vote = Vote::where('user_id', $user->id)->where('date', $date)->first();

        $input = [
            'user_id' => $user->id,
            'meal_id' => $meal->id,
            'message_id' => $data['message_id'],
            'update_time' => $data['update_time'],
            'send_time' => $data['send_time'],
            'date' => $date,
        ];

        if ($vote) {
            if ($data['message_id'] <= $vote->message_id) {
                return false;
            }
            $vote->update($input);
        } else {
            $vote = Vote::create($input);
        }

        return $vote;
    }
} 