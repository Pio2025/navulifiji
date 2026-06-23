<?php

namespace App\Helpers;

if (!function_exists('show_stream_subjects_test')) {
    function show_stream_subjects_test($schID, $options = [])
    {
        return '<div class="alert alert-success">Test helper function working! School ID: ' . $schID . '</div>';
    }
}