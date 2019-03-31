<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['domain' => '{domain}'], function()
{
    Route::group(['prefix' => 'social'], function () {
        Route::get('/{provider}/authorize',array(
            'as' => 'login.social.authorize',
            'uses'=> 'UserController@socialAuthorize'));
        Route::get('/{provider}/login', 'UserController@socialLogin');
    });
    Route::group([
        'middleware' => ['api', 'cors'],
        'namespace' => $this->namespace,
        'prefix' => 'api',
    ], function () {
        Route::post('/post', array(
            'as' => 'post.api',
            'uses' => 'TestController@postApi'));
    });
    Route::get('set_cookie',function(){
        return response(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII='), 200)->header('Content-Type', 'image/png');
        /* Returns 1px transparent image */
    });
    Route::get('getSession',function(){
        return response(Session::getId(), 200)->header('Content-Type', 'application/json');
    });
    Route::get('/test', array(
        'as' => 'test',
        'uses' => 'SchedulingController@keywordSuggest'));
    Route::get('/importdata', array(
        'as' => 'test',
        'uses' => 'TestController@importData'));
    Route::get('/today/{value}/{random}', array(
        'as' => 'today',
        'uses' => 'TestController@today'));
    Route::get('/notfound/{value}', array(
        'as' => 'notfound',
        'uses' => 'TestController@notfound'));
    Route::get('/', array(
        'as' => 'channel.home',
        'uses' => 'SitePublicController@index'));
    //--Pages
    Route::get('/rss', array(
        'as' => 'pages.rss',
        'uses' => 'SitemapsController@rss'));
    Route::get('/sitemap', array(
        'as' => 'pages.sitemap',
        'uses' => 'SitemapsController@index'));
    Route::get('/sitemap/all', array(
        'as' => 'pages.sitemap.sub',
        'uses' => 'SitemapsController@sub'));
    Route::get('/web-design', array(
        'as' => 'pages.web.design',
        'uses' => 'PagesController@webDesign'));
    Route::get('/domain', array(
        'as' => 'pages.domain',
        'uses' => 'PagesController@domain'));
    Route::get('/email', array(
        'as' => 'pages.email',
        'uses' => 'PagesController@email'));
    Route::get('/hosting', array(
        'as' => 'pages.hosting',
        'uses' => 'PagesController@hosting'));
    Route::get('/cloud', array(
        'as' => 'pages.cloud',
        'uses' => 'PagesController@cloud'));
    Route::get('/price', array(
        'as' => 'pages.price',
        'uses' => 'PagesController@Price'));
    Route::get('/goto/{url}', array(
        'as' => 'go.to.url',
        'uses' => 'PagesController@gotoUrl'))->where('url', '.*');
    //-- End Pages
    //--Tools
    Route::get('/domain/country/{iso}', array(
        'as' => 'domain.country.iso',
        'uses' => 'DomainController@getDomainByCountryCode'));
    Route::get('/domain/ip/{ip}', array(
        'as' => 'domain.by.ip',
        'uses' => 'DomainController@getDomainByIp'));
    Route::get('/domain/top/view', array(
        'as' => 'domain.top.view',
        'uses' => 'DomainController@getDomainTopView'));
    Route::post('/domain-update-info', array(
        'as' => 'domain.update.info',
        'uses' => 'DomainController@updateDomainInfo'));
    Route::post('/domain-disable-ads', array(
        'as' => 'domain.disable.ads',
        'uses' => 'DomainController@disableAds'));
    Route::post('/domain-active-ads', array(
        'as' => 'domain.active.ads',
        'uses' => 'DomainController@activeAds'));
    //--End Tools
    Route::get('/movePostAttribute', array(
        'as' => 'channel.post.move.attribute',
        'uses' => 'PostsController@moveAttribute'));
    Route::group(['prefix' => 'post'], function () {
        Route::get('/list', array(
            'as' => 'post.list',
            'uses' => 'PostsController@postList'));
        Route::get('/me', array(
            'as' => 'post.me',
            'uses' => 'PostsController@postMe'));
        Route::get('/billing', array(
            'as' => 'post.billing',
            'uses' => 'PostsController@postBilling'));
        Route::get('/add', array(
            'as' => 'post.add',
            'uses' => 'PostsController@postAdd'));
        Route::get('/edit/{id}', array(
            'as' => 'post.edit',
            'uses' => 'PostsController@postEdit'));
        Route::post('/add', array(
            'as' => 'post.add.request',
            'uses' => 'PostsController@postAddRequest'));
        Route::post('/remove', array(
            'as' => 'post.remove.request',
            'uses' => 'PostsController@postsRemove'));
    });
    Route::group(['middleware' => ['role:admin|manage|member']], function () {

    });
    Route::group(['prefix' => 'inet'], function () {
        Route::get('/signin', array(
            'as' => 'inet.signin',
            'uses' => 'DomainController@inetSignin'));
        Route::group(['prefix' => 'domain'], function () {
            Route::post('/check', array(
                'as' => 'inet.domain.check',
                'uses' => 'DomainController@inetDomainCheck'));
        });
    });
    Route::group(['prefix' => 'upload'], function () {
        Route::post('/tmp', array(
            'as' => 'upload.tmp',
            'uses' => 'MediaController@uploadToTmp'));
        Route::post('/tmp/delete', array(
            'as' => 'tmp.delete',
            'uses' => 'MediaController@delTmp'));
    });
    Route::group(['prefix' => 'settings'], function () {
        Route::group(['prefix' => 'ssl'], function () {
            Route::post('/active', array(
                'as' => 'channel.ssl.active',
                'uses' => 'ChannelController@sslActive'));
        });
        Route::group(['prefix' => 'theme'], function () {
            Route::get('/', array(
                'as' => 'channel.theme',
                'uses' => 'ChannelController@themeGet'));
        });
        Route::group(['prefix' => 'domain'], function () {
            Route::get('/', array(
                'as' => 'channel.domain',
                'uses' => 'ChannelController@domainGet'));
        });
        Route::group(['prefix' => 'members'], function () {
            Route::get('/', array(
                'as' => 'channel.members',
                'uses' => 'ChannelController@membersGet'));
        });
        Route::group(['prefix' => 'statistics'], function () {
            Route::get('/', array(
                'as' => 'channel.statistics',
                'uses' => 'ChannelController@statisticsGet'));
        });
        Route::group(['prefix' => 'upgrade'], function () {
            Route::get('/', array(
                'as' => 'channel.upgrade',
                'uses' => 'ChannelController@upgradeShow'));
        });
    });
    Route::group(['prefix' => 'keyword'], function () {
        Route::get('/', array(
            'as' => 'keyword.old.list',
            'uses' => 'KeywordsController@redirectIndex'));
        Route::get('/{slug}', array(
            'as' => 'keyword.old.show',
            'uses' => 'KeywordsController@redirectShow'));
    });
    Route::group(['prefix' => 'k'], function () {
        Route::get('/', array(
            'as' => 'keyword.list',
            'uses' => 'KeywordsController@index'));
        Route::get('/{slug}', array(
            'as' => 'keyword.show',
            'uses' => 'KeywordsController@show'));
    });
    Route::group(['prefix' => 'sitelink'], function () {
        Route::get('/', function () {
            return redirect()->route('topsites.list', config('app.url'),301);
        });
        Route::get('/{slug}', array(
            'as' => 'sitelink.show',
            'uses' => 'SitelinkController@show'));
    });
    Route::group(['prefix' => 'topsites'], function () {
        Route::get('/', array(
            'as' => 'topsites.list',
            'uses' => 'SitelinkController@list'));
    });
    Route::group(['prefix' => 'company'], function () {
        Route::post('/save', array(
            'as' => 'company.save',
            'uses' => 'CompanyController@saveCompany'));
        Route::post('/channel/delete', array(
            'as' => 'company.channel.delete',
            'uses' => 'CompanyController@companyChannelDelete'));
        Route::get('/', array(
            'as' => 'company.list',
            'uses' => 'CompanyController@companyViewSlug'));
        Route::get('/{slug}', array(
            'as' => 'company.view.slug',
            'uses' => 'CompanyController@companyViewSlug'));
    });
    Route::group(['prefix' => 'keywords'], function () {
        Route::get('/{postId}', array(
            'as' => 'keywords.list',
            'uses' => 'KeywordsController@keywordList'));
    });
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/dashboard', array(
            'as' => 'channel.admin.dashboard',
            'uses' => 'SitePublicController@dashboard'));
        Route::get('/setting', array(
            'as' => 'channel.admin.setting',
            'uses' => 'ChannelController@setting'));
        Route::post('/setting', array(
            'as' => 'channel.admin.setting.update',
            'uses' => 'ChannelController@settingUpdate'));
        Route::get('/contact', array(
            'as' => 'channel.admin.contact',
            'uses' => 'SitePublicController@contactConfig'));
        Route::post('/contact', array(
            'as' => 'channel.admin.contact.update',
            'uses' => 'SitePublicController@contactUpdate'));
        Route::get('/theme', array(
            'as' => 'channel.admin.theme',
            'uses' => 'SitePublicController@theme'));
        Route::post('/upload', array(
            'as' => 'channel.upload.file',
            'uses' => 'MediaController@uploadFile'));
        Route::group(['prefix' => 'channel'], function () {
            Route::post('/address', array(
                'as' => 'channel.address.save',
                'uses' => 'ChannelController@addressSave'));
            Route::post('/address/delete', array(
                'as' => 'channel.address.delete',
                'uses' => 'ChannelController@addressDelete'));
            Route::post('/email', array(
                'as' => 'channel.email.save',
                'uses' => 'ChannelController@emailSave'));
            Route::post('/email/delete', array(
                'as' => 'channel.email.delete',
                'uses' => 'ChannelController@emailDelete'));
            Route::post('/phone', array(
                'as' => 'channel.phone.save',
                'uses' => 'ChannelController@phoneSave'));
            Route::post('/phone/delete', array(
                'as' => 'channel.phone.delete',
                'uses' => 'ChannelController@phoneDelete'));
            Route::post('/name', array(
                'as' => 'channel.name.save',
                'uses' => 'ChannelController@nameSave'));

            Route::get('/{id}/attribute/image/cover', array(
                'as' => 'channel.attribute.image.cover',
                'uses' => 'ChannelController@channelAttributeImageCover'));
            Route::post('/attribute/media', array(
                'as' => 'channel.attribute.media.add',
                'uses' => 'ChannelController@channelAttributeMediaAdd'));
            Route::post('/attribute/media/logo', array(
                'as' => 'channel.attribute.media.logo',
                'uses' => 'ChannelController@channelAttributeMediaLogoChange'));
            Route::post('/attribute/media/background', array(
                'as' => 'channel.attribute.media.background',
                'uses' => 'ChannelController@channelAttributeMediaBackgroundChange'));
            Route::post('/attribute/media/background/delete', array(
                'as' => 'channel.attribute.media.background.delete',
                'uses' => 'ChannelController@channelAttributeMediaBackgroundChangeDelete'));
            Route::post('/attribute/color', array(
                'as' => 'channel.attribute.color',
                'uses' => 'ChannelController@channelAttributeColor'));
            Route::post('/attribute/color/reset', array(
                'as' => 'channel.attribute.color.reset',
                'uses' => 'ChannelController@channelAttributeColorReset'));
            Route::post('/attribute/media/delete', array(
                'as' => 'channel.attribute.media.delete',
                'uses' => 'ChannelController@channelAttributeMediaDelete'));
        });
        Route::group(['prefix' => 'posts'], function () {
            Route::get('/add', array(
                'as' => 'channel.post.add',
                'uses' => 'PostsController@postsAdd'));
            Route::get('/edit/{id}', array(
                'as' => 'channel.post.edit',
                'uses' => 'PostsController@postsEdit'));
            Route::post('/save', array(
                'as' => 'channel.post.save',
                'uses' => 'PostsController@postsSave'));
            Route::post('/delete', array(
                'as' => 'channel.post.delete',
                'uses' => 'PostsController@postsDelete'));
            Route::get('/{id}/attribute/gallery', array(
                'as' => 'channel.post.attribute.gallery',
                'uses' => 'PostsController@postAttributeGallery'));
            Route::post('/attribute/add', array(
                'as' => 'channel.post.attribute.add',
                'uses' => 'PostsController@postAttributeAdd'));
            Route::post('/attribute/delete/price', array(
                'as' => 'channel.post.attribute.delete.price',
                'uses' => 'PostsController@postAttributeDeletePrice'));
            Route::post('/attribute/media/delete', array(
                'as' => 'post.attribute.media.delete',
                'uses' => 'PostsController@postAttributeMediaDelete'));
            Route::post('/attribute/gallery/insert', array(
                'as' => 'channel.post.attribute.gallery.insert',
                'uses' => 'PostsController@postAttributeGalleryInsert'));
            Route::post('/attribute/options/get', array(
                'as' => 'channel.post.attribute.options.get',
                'uses' => 'PostsController@postGetOptions'));
            Route::post('/attribute/options/post', array(
                'as' => 'channel.post.attribute.options.post',
                'uses' => 'PostsController@postAddOptions'));
            Route::group(['prefix' => 'join'], function () {
                Route::get('/category/list', array(
                    'as' => 'post.join.category.list',
                    'uses' => 'PostsController@postJoinCategoryList'));
                Route::post('/category/add', array(
                    'as' => 'post.join.category.add',
                    'uses' => 'PostsController@postJoinCategoryAdd'));
                Route::post('/category/delete', array(
                    'as' => 'post.join.category.delete',
                    'uses' => 'PostsController@postJoinCategoryDelete'));
            });
        });
        Route::group(['prefix' => 'trash'], function () {
            Route::get('/', array(
                'as' => 'channel.trash',
                'uses' => 'ChannelController@trashGet'));
            Route::get('/list', array(
                'as' => 'channel.trash.list',
                'uses' => 'ChannelController@trashList'));
        });
        Route::group(['prefix' => 'category'], function () {
            Route::get('/one/{id}', array(
                'as' => 'channel.category.one',
                'uses' => 'CategoryController@getCategoryById'));
            Route::get('/list', array(
                'as' => 'channel.category.list',
                'uses' => 'CategoryController@categoryList'));
            Route::get('/select', array(
                'as' => 'channel.category.select',
                'uses' => 'CategoryController@categorySelect'));
            Route::post('/category', array(
                'as' => 'channel.category.save',
                'uses' => 'CategoryController@categorySave'));
            Route::post('/add', array(
                'as' => 'channel.category.add',
                'uses' => 'CategoryController@categoryAdd'));
            Route::post('/delete', array(
                'as' => 'channel.category.delete',
                'uses' => 'CategoryController@categoryDelete'));
        });

        Route::group(['prefix' => 'domain'], function () {
            Route::get('/list', array(
                'as' => 'channel.domain.list',
                'uses' => 'DomainController@domainList'));
            Route::post('/add', array(
                'as' => 'channel.domain.add',
                'uses' => 'DomainController@domainAddToChannel'));
            Route::post('/add/new', array(
                'as' => 'channel.domain.add.new',
                'uses' => 'DomainController@addNewDomain'));
            Route::post('/delete', array(
                'as' => 'channel.domain.delete',
                'uses' => 'DomainController@domainDelete'));
        });
        Route::group(['prefix' => 'hosting'], function () {
            Route::post('/remove', array(
                'as' => 'hosting.remove',
                'uses' => 'HostingController@hostingRemove'));
            Route::post('/add', array(
                'as' => 'hosting.add',
                'uses' => 'HostingController@hostingAdd'));
            Route::get('/list', array(
                'as' => 'channel.hosting.list',
                'uses' => 'HostingController@hostingList'));
            Route::get('/change/domain/{id}', array(
                'as' => 'channel.hosting.change.domain',
                'uses' => 'HostingController@changeDomain'));
            Route::post('/change/domain/{id}', array(
                'as' => 'channel.hosting.change.domain.save',
                'uses' => 'HostingController@changeDomainSave'));
            Route::post('/delete', array(
                'as' => 'channel.hosting.delete',
                'uses' => 'HostingController@hostingDelete'));
            Route::get('/login', array(
                'as' => 'channel.hosting.login',
                'uses' => 'HostingController@getLoggedInUrl'));
        });
        Route::group(['prefix' => 'cloud'], function () {
            Route::post('/remove', array(
                'as' => 'cloud.remove',
                'uses' => 'CloudController@cloudRemove'));
            Route::post('/add', array(
                'as' => 'cloud.add',
                'uses' => 'CloudController@cloudAdd'));
            Route::get('/list', array(
                'as' => 'channel.cloud.list',
                'uses' => 'CloudController@cloudList'));
        });
        Route::group(['prefix' => 'mailserver'], function () {
            Route::post('/remove', array(
                'as' => 'mailserver.remove',
                'uses' => 'MailServerController@mail_serverRemove'));
            Route::post('/add', array(
                'as' => 'mailserver.add',
                'uses' => 'MailServerController@mail_serverAdd'));
            Route::get('/list', array(
                'as' => 'channel.mailserver.list',
                'uses' => 'MailServerController@mail_serverList'));
        });
    });
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/info', array(
            'as' => 'channel.profile.info',
            'uses' => 'UserController@profile'));
        Route::post('/resend', array(
            'as' => 'channel.profile.resend',
            'uses' => 'UserController@profileResendCode'));
        Route::get('/note', array(
            'as' => 'channel.profile.note',
            'uses' => 'UserController@profileNote'));
        Route::post('/note/get', array(
            'as' => 'channel.profile.note.get',
            'uses' => 'UserController@profileNoteGet'));
        Route::post('/note/save', array(
            'as' => 'channel.profile.note.save',
            'uses' => 'UserController@profileNoteSave'));
        Route::post('/avata', array(
            'as' => 'profile.logo.change',
            'uses' => 'UserController@profileAvataChange'));
        Route::post('/save', array(
            'as' => 'profile.save',
            'uses' => 'UserController@profileSave'));
        Route::post('/joinstaff', array(
            'as' => 'channel.profile.joinstaff',
            'uses' => 'UserController@profileJoinStaff'));
        Route::post('/joinchannel', array(
            'as' => 'channel.profile.joinchannel',
            'uses' => 'ChannelController@profileJoinChannel'));
        Route::post('/addusermanager', array(
            'as' => 'channel.profile.addusermanager',
            'uses' => 'ChannelController@profileAddUserManager'));
        Route::post('/removeusermanager', array(
            'as' => 'channel.profile.removeusermanager',
            'uses' => 'ChannelController@profileRemoveUserManager'));
        Route::post('/changepass', array(
            'as' => 'channel.profile.changepass',
            'uses' => 'UserController@changePass'));
    });
    Route::group(['prefix' => 'members'], function () {
        Route::get('/list', array(
            'as' => 'channel.members.list',
            'uses' => 'UserController@memberList'));
        Route::post('/role/get', array(
            'as' => 'channel.members.role.get',
            'uses' => 'UserController@memberRoleGet'));
        Route::post('/role/save', array(
            'as' => 'channel.members.role.save',
            'uses' => 'UserController@memberRoleSave'));
    });
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/sales', array(
            'as' => 'channel.profile.sales',
            'uses' => 'UserController@profileSales'));
        Route::get('/customers', array(
            'as' => 'channel.profile.customers',
            'uses' => 'UserController@profileCustomers'));
        Route::get('/voucher', array(
            'as' => 'channel.profile.voucher',
            'uses' => 'UserController@profileVoucher'));
        Route::get('/bank', array(
            'as' => 'channel.profile.bank',
            'uses' => 'UserController@profileBank'));
        Route::post('/bank', array(
            'as' => 'channel.profile.bank.request',
            'uses' => 'UserController@profileBankRequest'));
    });
    Route::group(['middleware' => ['role:admin|manage']], function () {
        Route::group(['prefix' => 'admin'], function () {
            Route::group(['prefix' => 'tools'], function () {
                Route::group(['prefix' => 'email'], function () {
                    Route::get('/list', array(
                        'as' => 'tools.email.list',
                        'uses' => 'SitePublicController@emailList'));
                });
            });
            Route::group(['prefix' => 'service'], function () {
                Route::get('/list/expired', array(
                    'as' => 'service.list.expired',
                    'uses' => 'ServiceController@listExpired'));
            });
        });
    });
    Route::group(['prefix' => 'service'], function () {
        Route::post('/get/attribute/id', array(
            'as' => 'service.get.attribute.id',
            'uses' => 'ServiceController@getAttributeById'));
    });
    Route::group(['prefix' => 'pay'], function () {
        Route::get('/', array(
            'as' => 'pay.cart',
            'uses' => 'PaymentController@payCart'));
        Route::post('/', array(
            'as' => 'pay.cart.send',
            'uses' => 'PaymentController@payCartSend'));
        Route::post('/quanlity', array(
            'as' => 'pay.cart.quanlity',
            'uses' => 'PaymentController@updateQualityCart'));
        Route::post('/create', array(
            'as' => 'pay.create',
            'uses' => 'PaymentController@payCreate'));
        Route::get('/check', array(
            'as' => 'pay.check',
            'uses' => 'PaymentController@payCheck'));
        Route::get('/add', array(
            'as' => 'pay.add',
            'uses' => 'PaymentController@payAdd'));
        Route::post('/add', array(
            'as' => 'pay.add.money',
            'uses' => 'PaymentController@addMoney'));
        Route::post('/order/pending/delete', array(
            'as' => 'pay.order.pending.delete',
            'uses' => 'PaymentController@payOrderPendingDelete'));
        Route::get('/check/success', array(
            'as' => 'pay.check.success',
            'uses' => 'PaymentController@paymentCheckSuccess'));
        Route::get('/history', array(
            'as' => 'pay.history',
            'uses' => 'PaymentController@payHistory'));
        Route::post('/order/delete', array(
            'as' => 'pay.order.delete',
            'uses' => 'PaymentController@orderDelete'));
    });
    Route::group(['prefix' => 'cart'], function () {
        Route::post('/buy', array(
            'as' => 'buy.now',
            'uses' => 'CartController@buyNow'));
        Route::post('/buy/send', array(
            'as' => 'buy.now.send',
            'uses' => 'CartController@buyNowSend'));
        Route::post('/', array(
            'as' => 'create.cart',
            'uses' => 'CartController@createCart'));
        Route::post('/domain/session', array(
            'as' => 'cart.domain.session',
            'uses' => 'CartController@addDomainSession'));
        Route::get('/domain/info', array(
            'as' => 'cart.domain.info',
            'uses' => 'CartController@updateDomainInfo'));
        Route::post('/domain/info', array(
            'as' => 'cart.domain.info.process',
            'uses' => 'CartController@updateDomainInfoProcess'));
        Route::post('/remove', array(
            'as' => 'cart.remove',
            'uses' => 'CartController@cartRemove'));
        Route::post('/add', array(
            'as' => 'cart.add',
            'uses' => 'CartController@cartAdd'));
        Route::get('/show', array(
            'as' => 'cart.show',
            'uses' => 'CartController@cartShow'));
        Route::post('/order', array(
            'as' => 'channel.cart.order',
            'uses' => 'CartController@cartOrder'));
        Route::post('/delete', array(
            'as' => 'channel.cart.order.delete',
            'uses' => 'CartController@cartOrderDelete'));
        Route::post('/attribute/delete', array(
            'as' => 'cart.attribute.delete',
            'uses' => 'CartController@cartAttributeDelete'));
    });
    Route::group(['prefix' => 'domain'], function () {
        Route::post('/get/id', array(
            'as' => 'domain.get.id',
            'uses' => 'DomainController@domainGetId'));
        Route::post('/save/id', array(
            'as' => 'domain.save.id',
            'uses' => 'DomainController@domainSaveId'));
        Route::post('/check', array(
            'as' => 'channel.domain.check',
            'uses' => 'DomainController@domainCheck'));
        Route::post('/set/primary', array(
            'as' => 'channel.domain.set.primary',
            'uses' => 'DomainController@domainSetPrimary'));
    });
    Route::group(['prefix' => 'hosting'], function () {
        Route::post('/get/id', array(
            'as' => 'hosting.get.id',
            'uses' => 'HostingController@hostingGetId'));
        Route::post('/save/id', array(
            'as' => 'hosting.save.id',
            'uses' => 'HostingController@hostingSaveId'));
    });
    Route::group(['prefix' => 'cloud'], function () {
        Route::get('/show', array(
            'as' => 'cloud.show',
            'uses' => 'CloudController@cloudShow'));
        Route::post('/get/id', array(
            'as' => 'cloud.get.id',
            'uses' => 'CloudController@cloudGetId'));
        Route::post('/save/id', array(
            'as' => 'cloud.save.id',
            'uses' => 'CloudController@cloudSaveId'));
    });
    Route::group(['prefix' => 'mail_server'], function () {
        Route::post('/get/id', array(
            'as' => 'mail_server.get.id',
            'uses' => 'MailServerController@mail_serverGetId'));
        Route::post('/save/id', array(
            'as' => 'mail_server.save.id',
            'uses' => 'MailServerController@mail_serverSaveId'));
    });
    Route::group(['prefix' => 'comment'], function () {
        Route::post('/add', array(
            'as' => 'channel.comment.add',
            'uses' => 'CommentController@commentAdd'));
        Route::post('/del', array(
            'as' => 'channel.comment.del',
            'uses' => 'CommentController@commentDel'));
    });
    Route::group(['prefix' => 'likes'], function () {
        Route::post('/add', array(
            'as' => 'channel.like.add',
            'uses' => 'LikeController@likeAdd'));
    });
    Route::group(['prefix' => 'select'], function () {
        Route::post('/add', array(
            'as' => 'channel.select.add',
            'uses' => 'SelectController@selectAdd'));
        Route::post('/delete', array(
            'as' => 'channel.select.delete',
            'uses' => 'SelectController@selectDelete'));
        Route::post('/send', array(
            'as' => 'channel.select.send',
            'uses' => 'SelectController@selectSend'));
        Route::get('/get', array(
            'as' => 'channel.select.get',
            'uses' => 'SelectController@selectGet'));
    });
    Route::get('/contact', array(
        'as' => 'channel.contact',
        'uses' => 'SitePublicController@contact'));
    Route::post('/contact', array(
        'as' => 'channel.contact.request',
        'uses' => 'SitePublicController@contactRequest'));
    Route::get('/login/redirect', array(
        'as' => 'channel.login.redirect',
        'uses' => 'UserController@loginRedirect'));
    Route::get('/login', array(
        'as' => 'channel.login',
        'uses' => 'UserController@login'));
    Route::post('/login', array(
        'as' => 'channel.login.request',
        'uses' => 'UserController@loginRequest'));
    Route::get('/user/active/{code}', array(
        'as' => 'channel.user.active.code',
        'uses' => 'UserController@userActiveCode'));
    Route::get('/forgotpassword', array(
        'as' => 'forgot.password',
        'uses' => 'Auth\PasswordController@forgotPassword'));
    Route::post('/forgotpassword', array(
        'as' => 'forgot.password.request',
        'uses' => 'Auth\PasswordController@forgotPasswordRequest'));
    Route::get('/password/reset/{token}', array(
        'as' => 'forgot.password.reset',
        'uses' => 'Auth\PasswordController@getReset'));
    Route::post('/password/reset/{token}', array(
        'as' => 'forgot.password.reset.request',
        'uses' => 'Auth\PasswordController@postReset'));
    Route::get('/register', array(
        'as' => 'channel.register',
        'uses' => 'UserController@register'));
    Route::post('/register', array(
        'as' => 'channel.register.request',
        'uses' => 'UserController@registerRequest'));
    Route::get('/logout', array(
        'as' => 'channel.logout',
        'uses' => 'UserController@logout'));
    Route::group(['prefix' => 'regions'], function () {
        Route::group(['prefix' => 'json'], function () {
            Route::get('/region/list', array(
                'as' => 'regions.json.list',
                'uses' => 'SitePublicController@getRegionAll'));
            Route::get('/subregion/list/{id}', array(
                'as' => 'subregion.json.list',
                'uses' => 'SitePublicController@getSubRegionByIdRegion'));
            Route::post('/subregion/list', array(
                'as' => 'subregion.json.list.post',
                'uses' => 'SitePublicController@postSubRegionByIdRegion'));
            Route::post('/district/list', array(
                'as' => 'district.json.list.post',
                'uses' => 'SitePublicController@postDistrictByIdSubRegion'));
            Route::post('/ward/list', array(
                'as' => 'ward.json.list.post',
                'uses' => 'SitePublicController@postWardByIdDistrict'));
            Route::get('/fields', array(
                'as' => 'channel.json.fields',
                'uses' => 'SitePublicController@getFields'));
        });
    });
    Route::group(['prefix' => 'channel'], function () {
        Route::get('/me', array(
            'as' => 'channel.me',
            'uses' => 'ChannelController@channelMe'));
        Route::post('/select/packge', array(
            'as' => 'channel.select.packge',
            'uses' => 'ChannelController@selectPackge'));
        Route::post('/add/step1', array(
            'as' => 'channel.add.step1',
            'uses' => 'ChannelController@channelAddStep1'));
        Route::post('/add/step2', array(
            'as' => 'channel.add.step2',
            'uses' => 'ChannelController@channelAddStep2'));
        Route::post('/add/step3', array(
            'as' => 'channel.add.step3',
            'uses' => 'ChannelController@channelAddStep3'));
        Route::post('/add/step4', array(
            'as' => 'channel.add.step4',
            'uses' => 'ChannelController@channelAddStep4'));
        Route::post('/add/step5', array(
            'as' => 'channel.add.step5',
            'uses' => 'ChannelController@channelAddStep5'));
        Route::post('/update', array(
            'as' => 'channel.update.id',
            'uses' => 'ChannelController@channelUpdateId'));
        Route::post('/update', array(
            'as' => 'channel.update',
            'uses' => 'ChannelController@updateVersion'));
        Route::post('/reupdate', array(
            'as' => 'channel.re.update',
            'uses' => 'ChannelController@reUpdateVersion'));
        Route::get('/', array(
            'as' => 'channel.list',
            'uses' => 'ChannelController@channelList'));
        Route::get('/free', array(
            'as' => 'channel.list.free',
            'uses' => 'ChannelController@channelListFree'));
        Route::get('/packge/json', array(
            'as' => 'channel.packge.json',
            'uses' => 'ChannelController@channelPackge'));
        Route::get('/add', array(
            'as' => 'channel.add',
            'uses' => 'ChannelController@channelAdd'));
        Route::post('/add', array(
            'as' => 'channel.add.request',
            'uses' => 'ChannelController@channelAddRequest'));
        Route::get('/add/info', array(
            'as' => 'channel.add.info',
            'uses' => 'ChannelController@channelAddInfo'));
        Route::post('/free', array(
            'as' => 'channel.free',
            'uses' => 'ChannelController@createChannelFree'));
        Route::post('/create', array(
            'as' => 'channel.create.request',
            'uses' => 'ChannelController@createChannel'));
        Route::get('/{slug}', array(
            'as' => 'channel.view.slug',
            'uses' => 'ChannelController@channelViewSlug'));
    });
    Route::group(['prefix' => 'payment'], function () {
        Route::post('/request', array(
            'as' => 'channel.payment.request',
            'uses' => 'PaymentController@paymentNL'));
        Route::get('/check', array(
            'as' => 'channel.payment.check',
            'uses' => 'PaymentController@checkPayment'));
    });
    Route::group(['prefix' => 'voucher'], function () {
        Route::post('/check', array(
            'as' => 'voucher.check',
            'uses' => 'CartController@voucherCheck'));
    });
    Route::group(['prefix' => 'field'], function () {
        Route::get('/{id}-{slug}', array(
            'as' => 'field.slug',
            'uses' => 'FieldController@show'))
            ->where('slug', '.*');
        Route::get('/{fieldSlug}/{location}', array(
            'as' => 'field.get.channel.by.location',
            'uses' => 'FieldController@getChannelByFieldLocation'))
            ->where('location', '.*');
        Route::get('/select', array(
            'as' => 'field.get.select',
            'uses' => 'FieldController@fieldSelect'));
    });
    Route::group(['prefix' => 'search'], function () {
        Route::get('/', array(
            'as' => 'search.query',
            'uses' => 'SearchController@getSearchQuery'));
        Route::get('/company', array(
            'as' => 'search.company',
            'uses' => 'SearchController@searchCompany'));
        Route::get('/all', array(
            'as' => 'search.all',
            'uses' => 'SearchController@searchAll'));
        Route::get('/{slug}', array(
            'as' => 'search.slug',
            'uses' => 'SearchController@searchSlug'));
    });
    Route::get('/cron/{type}', array(
        'as' => 'cron.start',
        'uses' => 'CronController@index'));
    Route::get('/404', array(
        'as' => '404',
        'uses' => 'SitePublicController@404'));
    Route::get('/{slug}', array(
        'as' => 'channel.slug',
        'uses' => 'SlugController@slug'))
        ->where('slug', '.*');
});