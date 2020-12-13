<?php

function notifyAdmin() {
    $curl = curl_init('https://hooks.slack.com/services/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');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, ['payload' => json_encode([
        'text' => 'A New User Detected !!!'
    ])]);
    curl_exec($curl);
    curl_close($curl);
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
