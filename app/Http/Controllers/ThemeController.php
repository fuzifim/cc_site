<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\User;
use App\Model\Domain;
use Input;
use Auth;
use Validator;
use Image;
use DB;
use Illuminate\Support\Str;
use App\Model\Themes;
use App\Model\Media;
use App\Model\Media_join;
use File;
use Session;
use Redirect;
use Response;
use Carbon\Carbon;
use URL;
use Cart; 
class ThemeController extends Controller
{
    //==========================================================================
    //==== Media
    //==========================================================================
	public function show(){
		$themes=Themes::where('themes.status','=','active')
		->join('media_join','media_join.table_parent_id','=','themes.theme_id')
		->where('media_join.table','=','themes')
		->join('media','media.media_id','=','media_join.media_parent_id')
		->groupBy('themes.theme_id')
		->get(); 
		if(count($themes)>0){
			return view('front.themes.show')->with('themes',$themes);
		}else{
			return view('404'); 
		}
	}
	public function joinAction(){
		$themeId=Input::get('themeId'); 
		$getCart=Cart::get('channel'); 
		if(isset($getCart)){
			$addCart=Cart::add(array(
				'id' => 'channel',
				'name' => $getCart->name,
				'price' => round(config('app.vpc_Amount')),
				'quantity' => 1,
				'attributes' => array(
					'channelDomain' => $getCart['attributes']->channelDomain,
					'channelName' => $getCart['attributes']->channelName,
					'channelAbout' => $getCart['attributes']->channelAbout,
					'channelRegion' => $getCart['attributes']->channelRegion,
					'channelSubRegion'=>$getCart['attributes']->channelSubRegion,
					'channelCategory' => $getCart['attributes']->channelCategory,  
					'theme'=>$themeId
				)
			));
			return response()->json(['success'=>true,
				'message'=>'Đã thêm mẫu vào kênh của bạn!',
				'data'=>$getCart
			]);
		}else{
			$addCart=Cart::add(array(
				'id' => 'channel',
				'name' => 'channel',
				'price' => round(config('app.vpc_Amount')),
				'quantity' => 1,
				'attributes' => array(
					'theme'=>$themeId
				)
			));
			return response()->json(['success'=>true,
				'message'=>'Đã thêm mẫu vào kênh của bạn!', 
			]);
		}
	}
}
