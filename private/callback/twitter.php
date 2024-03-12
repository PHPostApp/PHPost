<?php

require realpath(__DIR__) . DIRECTORY_SEPARATOR . "Callback.php";

$callback->social = 'twitter';

$data = $callback->cURLToken();

$userData = $callback->cURLUser($data);

var_dump($userData);
die;

$user['nick'] = $userData->name;
$user['avatar'] = $userData->avatar_url;
$user['email'] = $userData->email;

$callback->OAuthComplete($user);