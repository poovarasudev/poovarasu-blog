<?php

function notifyAdmin() {
    if (env('NOTIFY_ADMIN_WEBHOOK')) {
        $curl = curl_init(env('NOTIFY_ADMIN_WEBHOOK'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, ['payload' => json_encode([
            'text' => 'A New User Detected !!!'
        ])]);
        curl_exec($curl);
        curl_close($curl);
    }
}

function getAppEnv()
{
    $appEnv = strtolower(config('app.env'));
    return in_array($appEnv, ['testing', 'production']) ? $appEnv : 'development';
}

function isAppEnvProduction()
{
    return getAppEnv() === 'production';
}
