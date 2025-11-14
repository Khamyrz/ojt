<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;

Schedule::command('dtr:auto-send')
    ->weeklyOn(5, '17:01') // Every Friday at 5:01 PM
    ->timezone('Asia/Manila');


