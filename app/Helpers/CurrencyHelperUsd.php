<?php

if (!function_exists('currencyFormatUsd')) {
    function currencyFormatUsd($amount)
    {
        return '$' . number_format($amount, 0, '.', ',');
    }
}
