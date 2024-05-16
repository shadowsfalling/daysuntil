<?php

namespace DaysUntil;

class Middleware
{

    function handleCors()
    {
        // allow requests from ionic app
        // todo: add other origins of the frontends here
        $allowedOrigins = [
            'http://localhost:8100',
        ];

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization');
            header('Access-Control-Allow-Credentials: true');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit;
        }
    }
}
