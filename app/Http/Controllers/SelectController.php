<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Request;
use Illuminate\Support\Str;
use Auth;
use App\Permission;
use App\Role; 
use App\Model\Channel_role;
use App\User;
use App\Model\Messages; 
use App\Model\Posts;
use App\Model\Posts_join;
use App\Model\Posts_attribute; 
use Carbon\Carbon;
use Theme;
use WebService; 
use Route; 
use Redirect; 
use Response; 
use Input; 
use Validator; 
use Cart; 
class SelectController extends Controller
{
	protected $_domain; 
	protected $_channel; 
	protected $_channelColor; 
	protected $_theme; 
	protected $_parame; 
	protected $_security; 
	public function __construct(){
		$this->_security=false; 
		$this->_parame=Route::current()->parameters(); 
		$this->_domain=WebService::getDomain($this->_parame['domain']);
        if(empty($this->_domain->domain)){
            return Redirect::to('//'.config('app.url'));
        }elseif($this->_domain->domain_primary!='default'){
            foreach($this->_domain->domainJoinChannel->channel->domainAll as $domain){
                if($domain->domain->domain_primary=='default'){
                    return Redirect::to('//'.$domain->domain->domain);
                }
            }
        }else{
            $this->_channel=$this->_domain->domainJoinChannel->channel;
            $this->_theme=Theme::uses($this->_channel->channelAttributeTheme->theme->temp_location)->layout('default');
            //$this->_channel->increment('channel_view',1);
            if(Auth::check()){
                $user=Auth::user();
                if($user->hasRole(['admin', 'manage'])){
                    $this->_security=true;
                }
                $getRoleChannel=Channel_role::where('parent_id','=',$this->_channel->id)->where('user_id','=',Auth::user()->id)->first();
                if(!empty($getRoleChannel->id)){
                    $getRole=Role::findOrFail($getRoleChannel->role_id);
                    $role_permissions = $getRole->perms()->get();
                    foreach ($role_permissions as $permission) {
                        if ($permission->name == 'manager_channel') {
                            $this->_security=true;
                        }
                    }
                }
            }
            view()->share(
                'channel',array(
                    'domain'=>$this->_domain,
                    'info'=>$this->_channel,
                    'security'=>$this->_security,
                    'category'=>$this->_channel->getCategory,
                    'theme'=>$this->_theme,
                    'color'=>(!empty($this->_channel->channelAttributeColor->channel_attribute_value)) ? json_decode($this->_channel->channelAttributeColor->channel_attribute_value) : false
                )
            );
        }
	}
	public function selectSend()
    {
		if(Auth::check()){
			$cartMessage=Input::get('cartMessage'); 
			if(Cart::getContent()->count()>0){
				$contentMessage=[
					'cartContent'=>Cart::getContent(), 
					'cartMessage'=>$cartMessage
				];
				$message=[
					'type'=>'select', 
					'from'=>Auth::user()->id, 
					'to'=>$this->_channel->id, 
					'message_title'=>'Bạn nhận được 1 yêu cầu từ '.$this->_channel->channel_name, 
					'message_body'=>json_encode($contentMessage), 
					'message_status'=>'unread', 
					'message_send'=>'pending', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				]; 
				Messages::create($message); 
				Cart::clear();
				return response()->json(['success'=>true,
					'message'=>$message
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không có mục nào được chọn nên không thể gửi yêu cầu. '
				]);
			}
		}else{
			return response()->json(['success'=>false,
				'message'=>'Đăng nhập để gửi yêu cầu', 
				'error'=>'login'
			]);
		}
	}
	public function selectGet()
    {
		if(Cart::getContent()->count()>0){
			return response()->json(['success'=>true,
				'message'=>'Danh sách mục đã chọn', 
				'data'=>Cart::getContent()
			]);
		}else{
			return response()->json(['success'=>false,
				'message'=>'Không có mục nào được chọn'
			]);
		}
	}
	public function selectDelete()
    {
		$idSelect=Input::get('idSelect'); 
		Cart::remove($idSelect); 
		return response()->json(['success'=>true,
			'message'=>'Đã xóa mục chọn '
		]);
	}
	public function selectAdd()
    {
		if(Auth::check()){
			$itemId=Input::get('itemId'); 
			$post=Posts::find($itemId); 
			if(!empty($post->id)){
				if($post->gallery[0]->media->media_storage=='youtube'){
					$postImage='//img.youtube.com/vi/'.$post->gallery[0]->media->media_name.'/hqdefault.jpg'; 
				}else if(!empty($post->gallery[0]->media->media_url_xs)){
					$postImage=$post->gallery[0]->media->media_url_xs; 
				}else if(!empty($post->gallery[0]->media->media_url)){
					$postImage=$post->gallery[0]->media->media_url; 
				}else{
					$postImage=null; 
				}
				Cart::add($post->id, $post->posts_title, $post->price->posts_attribute_value, 1,['currency' => $this->_channel->channelJoinRegion->region->currency_code,'link'=>route('channel.slug',array($this->_domain->domain,$post->getSlug->slug_value)),'image'=>$postImage]);
				return response()->json(['success'=>true,
					'message'=>'Đã chọn '.$post->id
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không tìm thấy mục được chọn '.$itemId
				]);
			}
		}else{
			return response()->json(['success'=>false,
				'message'=>'Đăng nhập để chọn', 
				'error'=>'login'
			]);
		}
	}
}