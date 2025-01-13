<?php
use Carbon\Carbon;

if (!function_exists('dateFormat')) {
    function dateFormat($date, $format = 'm/d/Y') {
        return Carbon::parse($date)->translatedFormat($format);
    }
}
