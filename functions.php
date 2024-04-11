<?php

function sanitizeAttribute($attribute)
{
    $result = stripslashes($attribute);
    $result = strip_tags($result);
    $result = htmlspecialchars($result);
    return trim($result);
}

function validateAttribute($attribute, $filterType = 'string')
{
    $result = match ($filterType) {
        'string' => filter_var($attribute, FILTER_VALIDATE_REGEXP, [
            "options" => [
                "regexp" => "/^[a-zA-Z ]*$/"
            ]
        ]),
        'integer' => filter_var($attribute, FILTER_VALIDATE_INT, [
            "options" => [
                "min_range" => 0,
                "max_range" => 100
            ]
        ]),
        'float' => filter_var($attribute, FILTER_VALIDATE_FLOAT, [
            "options" => [
                "min_range" => 10,
                "max_range" => 1000
            ]
        ]),
    };

    return $result;
}