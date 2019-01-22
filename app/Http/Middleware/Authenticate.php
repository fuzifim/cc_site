<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Jenssegers\Agent\Agent; 
use Theme; 
use Route; 
use WebService;
class Authenticate
{
    protected $_domain; 
	protected $_channel; 
	protected $_theme; 
	protected $_parame;
    protected $auth;
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    public function handle($request, Closure $next)
    {
		$this->_parame=Route::current()->parameters(); 
		$this->_domain=WebService::getDomain($this->_parame['domain']); 
		$this->_channel=$this->_domain->domainJoinChannel->channel; 
		$this->_theme=Theme::uses($this->_channel->channelAttributeTheme->theme->temp_location)->layout('default'); 
		$view = array(
			'channel'=>$this->_channel, 
			'domain'=>$this->_domain, 
		);
        $agent = new Agent();
        if ($this->auth->guest())
        {
            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
				return $this->_theme->of('themes.admin.user.login', $view)->render(); 
            }
        }

        return $next($request);
    }

}
