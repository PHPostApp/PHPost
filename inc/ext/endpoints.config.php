<?php

$getEndPoints = [
	'github' => [
		'authorize_url' => 'https://github.com/login/oauth/authorize',
		'token' => "https://github.com/login/oauth/access_token",
		'user' => "https://api.github.com/user",
		'scope' => "user"
	],
	'discord' => [
		'authorize_url' => 'https://discord.com/oauth2/authorize',
		'token' => "https://discord.com/api/oauth2/token",
		'user' => "https://discord.com/api/v10/users/@me",
		'scope' => "email identify"
	],
	'gmail' => [
		'authorize_url' => 'https://accounts.google.com/o/oauth2/auth',
		'token' => "https://accounts.google.com/o/oauth2/token",
		'user' => "https://www.googleapis.com/oauth2/v2/userinfo",
		'scope' => "https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile"
	],
	'facebook' => [
		'authorize_url' => 'https://www.facebook.com/v18.0/dialog/oauth',
		'token' => "https://graph.facebook.com/oauth/access_token",
		'user' => "https://graph.facebook.com/v18.0/me?fields=id,name,email,picture,short_name",
		'scope' => "email,public_profile"
	],
	'twitter' => [
		'authorize_url' => 'https://api.twitter.com/oauth/authenticate',
		'token' => "https://api.twitter.com/oauth/access_token",
		'user' => "https://graph.facebook.com/v18.0/me?fields=id,name,email,picture,short_name",
		'scope' => "email,public_profile"
	]
];