<?php

return [
	
	/**
	 * Application Name.
	 */
	'application_name' => 'Youtube API',

	/**
	 * Client ID.
	 */
	//'client_id' => '173749234082-pmueohgikflpokjbr74omog19hp107vq.apps.googleusercontent.com',
	'client_id' => env('GOOGLE_CLIENT_ID'),

	/**
	 * Client Secret.
	 */
	//'client_secret' => 'FlAC8i-FnbH3cRFxpj3pXr3T',
	'client_secret' => env('GOOGLE_CLIENT_SECRET'),

	/**
	 * Route Base URI. You can use this to prefix all route URI's.
	 * Example: 'admin', would prefix the below routes with 'http://domain.com/admin/'
	 */
	'route_base_uri' => 'youtube',

	/**
	 * Redirect URI, this does not include your TLD.
	 * Example: 'callback' would be http://domain.com/callback
	 */
	'redirect_uri' => 'callback',

	/**
	 * The autentication URI in with you will require to first authorize with Google.
	 */
	'authentication_uri' => 'auth',

	/**
	 * Access Type
	 */
	'access_type' => 'offline',

	/**
	 * Approval Prompt
	 */
	'approval_prompt' => 'auto',
	//'approval_prompt' => 'force',
	

	/**
	 * Scopes.
	 */
	 //'https://www.googleapis.com/auth/youtubepartner',
	//'https://www.googleapis.com/auth/youtube.force-ssl'
	 
	 //'https://www.googleapis.com/auth/youtube.readonly'
	'scopes' => [
		'https://www.googleapis.com/auth/youtube',
		'https://www.googleapis.com/auth/youtube.upload',
		'https://www.googleapis.com/auth/youtube.readonly'
	],

	/**
	 * Developer key.
	 */
	'developer_key' => getenv('GOOGLE_DEVELOPER_KEY')

];