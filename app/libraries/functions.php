<?php

use Carbon\Carbon;

/**
 * @return Carbon time
 */
function current_time()
{
    $dt = Carbon::now();
    return $dt;
    // return $dt->subDays(1);
    // return $dt->addDays(2);
}

/**
 * @param Carbon|string|null $date
 * @return string
 */
function current_date($date = null)
{
    if ($date === null) {
        $time = current_time();
        $date = $time->toDateString();
    } elseif ($date instanceof Carbon) {
        $date = $date->toDateString();
    }
    return $date;
}

