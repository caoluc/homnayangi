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
    // return $dt->addDays(3);
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

/**
 * @param $string
 * @return string
 */
function to_date_string($string)
{
    $dt = Carbon::createFromFormat('Y-m-d', $string);
    return $dt->format('l');
}

/**
 * @param $obj
 * @return array
 */
function obj_to_array($obj)
{
    $array = [];
    foreach ($obj as $key => $value) {
        if ($value instanceof stdClass) {
            $value = obj_to_array($value);
        }
        $array[$key] = $value;
    }

    return $array;
}

