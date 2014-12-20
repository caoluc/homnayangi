<?php

use Carbon\Carbon;

function current_time()
{
    $dt = Carbon::now();
    return $dt;
    // return $dt->subDay();
    // return $dt->addDay();
}

