<?php

function notifyAdmin($message = 'Blog - A New User Detected !!!') {
    if (env('NOTIFY_ADMIN_WEBHOOK')) {
        $curl = curl_init(env('NOTIFY_ADMIN_WEBHOOK'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, ['payload' => json_encode([
            'text' => $message
        ])]);
        curl_exec($curl);
        curl_close($curl);
    }
}

function getAppEnv()
{
    $appEnv = strtolower(env('APP_ENV'));
    return in_array($appEnv, ['testing', 'production']) ? $appEnv : 'development';
}

function isAppEnvProduction()
{
    return getAppEnv() === 'production';
}
