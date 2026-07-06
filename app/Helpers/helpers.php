<?php
if (!function_exists('userHas')) {
    function userHas($permission)
    {
        return auth()->check() && auth()->user()->can($permission);
    }
}