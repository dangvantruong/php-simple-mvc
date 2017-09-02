<?php

if (!function_exists('dd')) {
    function dd($data = null)
    {
        var_dump($data); die;
    }
}
