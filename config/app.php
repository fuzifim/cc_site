<?php

return [
    //count item config
	'link_media' => '//img.cungcap.net/',
	'item_list_website_group'=>2,
    'item_company_list_group'=>5,
	'item_adsmember' =>8,
	'item_search'=>20,
	'item_list_website'=>4,
    'item_list_ads_by_template_index'=>4,
	'item_adsrelate'=>8,
    'item_company_list'=>10,
    'manage_item_user'=>6,
    'ads_top_home' =>10,
    'child_category_home'=>3,
    'list_category_home'=>15,
    'seo_title'=>'',
	'email'=>'contact@cungcap.net',
	'phone'=>'0903706288',
    'emailfix'=>'contact@cungcap.net',
    // Application Name
    'name' => 'Cung Cap',
    'title_default'=>'Quảng cáo đa kênh',
    'keywords_default' => '',
    'description_default' => 'Tạo website có luôn kênh quảng cáo hoàn toàn miễn phí và còn nhiều hơn thế nữa... ',
	'inet_email' => env('inet_email'),
	'inet_pass' => env('inet_pass'),
    // Facebook App ID
    'facebook_app_id' => env('FACEBOOK_APP_ID'),
    // Facebook App Secret
    'facebook_app_secret' => env('FACEBOOK_APP_SECRET'),
    // Facebook Page Link
    'facebook_page_url' => env('FACEBOOK_PAGE'),
    
    // Google Page link
    'google_page_url' => '',

    // Twister Page Link
    'twister_page_url' => '',
	
	//list regions and subregion item Promotion
	'number_ads_promotion'=>12,
	
    //list customer index
    'customers_item_index'=>'20',
    //Limit number ads display in category Homepage
    'number_ads_category_home' => 8,
    
    //Limit number ads display in type ads homepage
    'number_ads_type_home' => 10 ,

    //Limit number ads display in Category, Type , Location
    'number_ads' => 20,
    'number_ads_location' => 10,
    //Limit number ads display in Category, Type , Location
    'number_ads_manage' => 20 ,

    'app_email' => 'contact@cungcap.net',
	'mail_admin' => 'fuzifim@gmail.com',
    'app_phone' => '0903706288',
	'NGANLUONG_RECEIVER'				=>env('NGANLUONG_RECEIVER'),
	'NGANLUONG_MERCHANT_ID'				=>env('NGANLUONG_MERCHANT_ID'),
	'NGANLUONG_MERCHANT_PASS'			=>env('NGANLUONG_MERCHANT_PASS'),
	'NGANLUONG_URL_API'					=>env('NGANLUONG_URL_API'),
	/*Config order thanh toan*/
	'vpc_time_end'                      => 6,// thời gian int theo tháng
	'vpc_voucher'                       =>5/100,//giam gia 5%;
	'vpc_Amount'						=> 880000,//giá tiền 880.000
	'vpc_Currency' 						=> 'VND',
	'vpc_Locale'						=> 'vn',
	
	//'vpc_SECURE_SECRET'					=> '198BE3F2E8C75A53F38C1C4A5B6DBA27',
	'vpc_SECURE_SECRET'					=> env('VPC_SECURE_SECRET'),
	
	//'vpc_AccessCode'					=> 'ECAFAB',
	'vpc_AccessCode'					=> env('VPC_AccessCode'),
	
	//'virtualPaymentClientURL'			=> 'https://sandbox.napas.com.vn/gateway/vpcpay.do',
	'virtualPaymentClientURL'			=> 'https://payment.napas.com.vn/gateway/vpcpay.do',
	
	'vpc_Version'						=> '2.0',
	'vpc_Command'						=> 'pay',
	
	//'vpc_Merchant'                      => 'SMLTEST',
	'vpc_Merchant'                  	 => env('VPC_Merchant'),
	
	'vpc_ReturnURL'                     => 'http://dakenh.net/atm/',
	'vpc_BackURL'                       => 'http://dakenh.net/dashboard', 
	'payment_token'						=> env('Payment_token_key'),
	'payment_checksum'					=> env('Payment_check_sum_key'),
	'payment_enscript'					=> env('Payment_encrypt_key'), 
	'DigitaloceanAccessTokens'			=> env('DigitaloceanKey'), 
    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG'),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */
	'url' => env('APP_DOMAIN'), 
	'ip_address' => env('APP_IP'),
    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Ho_Chi_Minh',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locales' => ['vn' => 'Việt Nam'],

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'vn',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'single'),
    'enabled' => DIRECTORY_SEPARATOR == '/',
    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        WebService\Providers\WebServiceProvider::class,
        Laravel\Socialite\SocialiteServiceProvider::class,
        AdamWathan\EloquentOAuthL5\EloquentOAuthServiceProvider::class,
        App\Providers\HelperProvider::class,
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
		Darryldecode\Cart\CartServiceProvider::class,
        Facuz\Theme\ThemeServiceProvider::class,
		Zizaco\Entrust\EntrustServiceProvider::class,
		GoogleMaps\ServiceProvider\GoogleMapsServiceProvider::class,
		Elasticquent\ElasticquentServiceProvider::class,
        Jenssegers\Mongodb\MongodbServiceProvider::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [
        'Notification'=> Illuminate\Support\Facades\Notification::class,
        'App'       => Illuminate\Support\Facades\App::class,
        'Artisan'   => Illuminate\Support\Facades\Artisan::class,
        'Auth'      => Illuminate\Support\Facades\Auth::class,
        'Blade'     => Illuminate\Support\Facades\Blade::class,
        'Bus'       => Illuminate\Support\Facades\Bus::class,
        'Cache'     => Illuminate\Support\Facades\Cache::class,
        'Config'    => Illuminate\Support\Facades\Config::class,
        'Cookie'    => Illuminate\Support\Facades\Cookie::class,
        'Crypt'     => Illuminate\Support\Facades\Crypt::class,
        'DB'        => Illuminate\Support\Facades\DB::class,
        'Eloquent'  => Illuminate\Database\Eloquent\Model::class,
        'Event'     => Illuminate\Support\Facades\Event::class,
        'File'      => Illuminate\Support\Facades\File::class,
        'Gate'      => Illuminate\Support\Facades\Gate::class,
        'Hash'      => Illuminate\Support\Facades\Hash::class,
        'Input'     => Illuminate\Support\Facades\Input::class,
        'Lang'      => Illuminate\Support\Facades\Lang::class,
        'Log'       => Illuminate\Support\Facades\Log::class,
        'Mail'      => Illuminate\Support\Facades\Mail::class,
        'Password'  => Illuminate\Support\Facades\Password::class,
        'Queue'     => Illuminate\Support\Facades\Queue::class,
        'Redirect'  => Illuminate\Support\Facades\Redirect::class,
        'Redis'     => Illuminate\Support\Facades\Redis::class,
        'Request'   => Illuminate\Support\Facades\Request::class,
        'Response'  => Illuminate\Support\Facades\Response::class,
        'Route'     => Illuminate\Support\Facades\Route::class,
        'Schema'    => Illuminate\Support\Facades\Schema::class,
        'Session'   => Illuminate\Support\Facades\Session::class,
        'Storage'   => Illuminate\Support\Facades\Storage::class,
        'URL'       => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View'      => Illuminate\Support\Facades\View::class,
        'Form' 		=> Collective\Html\FormFacade::class,
		'HTML' 		=> Collective\Html\HtmlFacade::class,
        'WebService'=>WebService\Facades\WebService::class,
        'Agent'     => Jenssegers\Agent\Facades\Agent::class,
        'Socialite' => Laravel\Socialite\Facades\Socialite::class,
        'Site' 		=> App\Helpers\TemplatePublicHelper::class,
        'SocialAuth' => AdamWathan\EloquentOAuth\Facades\OAuth::class,
        'Str'      	=> Illuminate\Support\Str::class,
        'SSH' 		=> Collective\Remote\RemoteFacade::class,
		'Cart' 		=> Darryldecode\Cart\Facades\CartFacade::class, 
		'Theme' 	=> Facuz\Theme\Facades\Theme::class,
		'Entrust'   => Zizaco\Entrust\EntrustFacade::class,
		'GoogleMaps' => GoogleMaps\Facade\GoogleMapsFacade::class,
		'Es' => Elasticquent\ElasticquentElasticsearchFacade::class
    ],

];
