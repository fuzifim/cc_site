<?php
use App\User;
return [
	'model' => User::class,
	'table' => 'oauth_identities',
	'providers' => [
		'facebook' => [
			'client_id' => env('FACEBOOK_APP_ID'),
			'client_secret' => env('FACEBOOK_APP_SECRET'),
			'redirect_uri' => 'https://taoweb.cungcap.net/social/facebook/login',
			'scope' => ['email', 'public_profile','user_posts','user_photos'],
		],
		'google' => [
			'client_id' => env('GOOGLE_CLIENT_ID'),
			'client_secret' => env('GOOGLE_CLIENT_SECRET'),
			'redirect_uri' => 'https://taoweb.cungcap.net/social/google/login',
			'scope' => [],
		],
		'github' => [
			'client_id' => '12345678',
			'client_secret' => 'y0ur53cr374ppk3y',
			'redirect_uri' => 'https://example.com/your/github/redirect',
			'scope' => [],
		],
		'linkedin' => [
			'client_id' => '12345678',
			'client_secret' => 'y0ur53cr374ppk3y',
			'redirect_uri' => 'https://example.com/your/linkedin/redirect',
			'scope' => [],
		],
		'instagram' => [
			'client_id' => '12345678',
			'client_secret' => 'y0ur53cr374ppk3y',
			'redirect_uri' => 'https://example.com/your/instagram/redirect',
			'scope' => [],
		],
		'soundcloud' => [
			'client_id' => '12345678',
			'client_secret' => 'y0ur53cr374ppk3y',
			'redirect_uri' => 'https://example.com/your/soundcloud/redirect',
			'scope' => [],
		],
	],
];
