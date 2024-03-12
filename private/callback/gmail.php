<?php

require realpath(__DIR__) . DIRECTORY_SEPARATOR . "Callback.php";

$callback->social = 'gmail';

$data = $callback->cURLToken(false);

$userData = $callback->cURLUser($data);

$user['nick'] = str_replace(' ', '', $userData->given_name);
$user['email'] = $userData->email;
$user['avatar'] = str_replace('s96-c', 's160-c', $userData->picture);

$callback->OAuthComplete($user);