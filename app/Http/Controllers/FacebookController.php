<?php namespace App\Http\Controllers;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use Session;
use App\Models\Ads;
use App\Models\Categories;
use App\Models\AdsType;
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use WebService;
use Auth;
use Str;
use SSH;
use Redirect;
use File;
use Input;
class FacebookController extends Controller {



	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public $fb;
	 
	public function __construct()
	{
        session_start() ; 
        $this->fb = new \Facebook\Facebook([
          'app_id' => Config::get('app.facebook_app_id'),
          'app_secret' => Config::get('app.facebook_app_secret'),
          'default_graph_version' => 'v2.8',
           ]);
        
        
	}

  /**
   * Show index
   */
   
   public function index(){
    $adstype = AdsType::take(4)->orderBy('id','desc')->get();
    
      return view('facebook.home',compact('ads','adstype'));
   }
   
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	 public function loginAccess(){
	  
        $helper = $this->fb->getRedirectLoginHelper();
        //$permissions = ['email','publish_actions','publish_pages','user_posts','manage_pages','user_groups']; // optional
		$permissions = ['email'];
        $loginUrl = $helper->getLoginUrl(url().'/facebook/get_access', $permissions);
       // echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
	      return redirect($loginUrl);
	 }
	 

    
    public function getAccessTokenFacebook(){
        $helper = $this->fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
          // The OAuth 2.0 client handler helps us manage access tokens
          $oAuth2Client = $this->fb->getOAuth2Client();
          
          if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
              $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
              echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
              exit;
            }
            
          }

        if (isset($accessToken)) {
          // Logged in
          Setcookie("facebook_access_token",(string) $accessToken, time() +5184000,'/');
          $_SESSION['facebook_access_token'] = (string) $accessToken;
          
          try {
              // Returns a `Facebook\FacebookResponse` object
             $me = $this->fb->get('/me',  $_SESSION['facebook_access_token']);
            
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              echo 'Graph returned an error: ' . $e->getMessage();
              exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              exit;
            }
            
            $user = $me->getGraphUser();
            if(Auth::check()){
              $user_id = Auth::user()->id;
            }else{
              $user_id = 0;
            }
            $data_insert = [
                        
                        'tokent' => $_SESSION['facebook_access_token'],
                        'fb_user_id' => $user['id'],
                        'user_id'=>$user_id
                        ];
          $data_update= [
                        
                        'tokent' => $_SESSION['facebook_access_token'],
                        
                        ];
          $users = DB::table('fb')->where('fb_user_id','=',$user['id'])->first();
          if(count($users) != 0 ){
              DB::table('fb')->where('fb_user_id','=',$user['id'])->update($data_update);
          }else{
            DB::table('fb')->insert($data_insert);
          }
          // Now you can redirect to another page and use the
           //return redirect()->route('facebook.me');
          // access token from $_SESSION['facebook_access_token']
          return redirect()->route('user.autopost.setting');
        }

    }
    
    public function getuser(){
      
      try {
              // Returns a `Facebook\FacebookResponse` object
             $me = $this->fb->get('/me',  $_COOKIE['facebook_access_token']);
            
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              echo 'Graph returned an error: ' . $e->getMessage();
              exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              exit;
            }
            
            $user = $me->getGraphUser();
            return $user['name'];
          
          
            
           
            
    }
    public function getgroup(){
        
          try {
              // Returns a `Facebook\FacebookResponse` object
         
             $group = $this->fb->get('/me/groups?limit=50', $_COOKIE['facebook_access_token']);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              echo 'Graph returned an error: ' . $e->getMessage();
              exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              exit;
            }
            try {
              // Returns a `Facebook\FacebookResponse` object
             $me = $this->fb->get('/me',  $_COOKIE['facebook_access_token']);
            
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              echo 'Graph returned an error: ' . $e->getMessage();
              exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              exit;
            }
            echo "groups info";
            echo "---------------";
            $data_group = $group->getDecodedBody();
            $user = $me->getGraphUser();
           // dd($data_group);
            foreach($data_group['data'] as $row){
               $post = [
                 'id' => $row['id'],
                 'name' => $row['name'],
                
                 'status' => 'active',
                 ];
                $fb_group_old = DB::table('fb_group')->where('id','=',$row['id'])->first();
                if (count($fb_group_old) == 0){
                 DB::table('fb_group')->insert($post);
                }
            }
            //dd($data_group['data']);
    }
    public function getpage(){
     
      try {
              // Returns a `Facebook\FacebookResponse` object
            
             $page = $this->fb->get('/me/accounts', $_COOKIE['facebook_access_token']);
            
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              echo 'Graph returned an error: ' . $e->getMessage();
              exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              exit;
            }
         
            echo "page info";
            echo "---------------";
            $data_page = $page->getDecodedBody();
           
            ?> 
            <select name="">
            <?php
            foreach ($data_page['data'] as $pages){
              
               ?>
               <option value="<?php echo $pages['id'] ?>"><?php echo $pages['name'] ?></option>
               <?php
              }
            ?>
            </select>
            
            <?php
           
            
    }
    public function feed($link,$price,$name,$phone,$user_id,$ads,$title,$image)
	{
	  if(WebService::getVipByID($user_id) ==0){
	   $linkData = [
                  'link' => $link, 
                  'message' =>'👉'.$title. ' ✌✌✌
                  👉 chỉ có: '.number_format($price) .' VNĐ 👏👏👏. 
                  (y) Truy cập ngay http://cbr.vn/'.$name .' để xem chi tiết. 
                  Hoặc liên hệ gian hàng 💜💜: '.$name .'👉  Điện thoại: '.$phone.' 📞 👉 để được tư vấn thêm.
                  🎉 Tin được đăng từ website CBR.VN | Tạo Website - Gian Hàng Miễn Phí 🎉.
                  ',
                  'picture' => url($image),
                  ];
	    }else{
	      $linkData = [
                  'link' => $link,
                  'message' =>'👉'.$title. ' ✌✌✌
                  👉 chỉ có: '.number_format($price) .' VNĐ 👏👏👏. 
                  (y) Truy cập ngay http://'.$name.'.cbr.vn/ để xem chi tiết. 
                  Hoặc liên hệ gian hàng 💜💜: '.$name .'👉  Điện thoại: '.$phone.' 📞 👉 để được tư vấn thêm.
                  🎉 Tin được đăng từ website '.$name .'.CBR.VN  🎉.
                  ',
                  'picture' => url($image),
                  ];
	    }
        $user_fb = DB::table('fb')->where('user_id','=',0)->get();
        foreach ($user_fb as $key){
        
          try {
                // Returns a `Facebook\FacebookResponse` object
                $response = $this->fb->post('/me/feed', $linkData, $key->tokent);
              } catch(Facebook\Exceptions\FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
              } catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
              }
              
              $graphNode = $response->getGraphNode();
              
             response('Posted with id: ' . $graphNode['id']);
             	
        }
        
	    
    }
    public function user_feed($link,$price,$name,$phone,$user_id,$ads,$title,$image)
	{
	  
	  $linkData = [
                  'link' => $link,
                  'message' =>'👉'.$title. '  ✌✌✌
                  👉 chỉ có: '.number_format($price) .' VNĐ 👏👏👏. 
                  (y) Truy cập ngay http://'.$name.'.cbr.vn/ để xem chi tiết. 
                  Hoặc liên hệ gian hàng 💜💜: '.$name .' 👉  📞  Điện thoại: '.$phone.' 👉 để được tư vấn thêm.
                  🎉 Tin được đăng từ website  '.$name .'.cbr.vn.
                  ',
                  'picture' => url($image),
                  ];
        
        $user_fb = DB::table('fb')->where('user_id','=',Auth::user()->id)->first();
        
        
          try {
                // Returns a `Facebook\FacebookResponse` object
                $response = $this->fb->post('/me/feed', $linkData, $user_fb->tokent);
              } catch(Facebook\Exceptions\FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
              } catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
              }
              
              $graphNode = $response->getGraphNode();
              
              //response('Posted with id: ' . $graphNode['id']);
       return response()->json(array('msg' => 'success'));
	    
    }
    public function postgroup($link,$price,$name,$phone,$user_id,$ads,$title,$image){
      
     
     $linkData = [
                  'link' => $link,
                  'message' =>'👉'.$title. ' ✌✌✌
                  👉 chỉ có: '.number_format($price) .' VNĐ 👏👏👏. 
                  (y) Truy cập ngay http://'.$name.'.cbr.vn/ để xem chi tiết. 
                  Hoặc liên hệ gian hàng 💜💜: '.$name .'👉  Điện thoại: '.$phone.' 📞 👉 để được tư vấn thêm.
                  🎉 Tin được đăng từ website '.$name .'.CBR.VN  🎉.
                  ',
                  'picture' => url($image),
                  ];
          $this->feed($link,$price,$name,$phone,$user_id,$ads,$title,$image);
           $this->postpage($link,$price,$name,$phone,$user_id,$ads,$title,$image);
           
      if(WebService::getVipByID($user_id) !=0){
        $user_fb = DB::table('fb')->where('user_id','=',0)->get();
        $cat = DB::table('ads')->where('ads.id','=',$ads)->first();
        if($cat->ads_cate_id != 18){
          
                  foreach ($user_fb as $key){
                      try {
                              // Returns a `Facebook\FacebookResponse` object
                          $group = $this->fb->get('/me/groups?limit=20', $key->tokent);
                            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                              echo 'Graph returned an error: ' . $e->getMessage();
                              exit;
                            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                              echo 'Facebook SDK returned an error: ' . $e->getMessage();
                              exit;
                            }
                          
                            
                            $data_group = $group->getDecodedBody();
                            
                            foreach($data_group['data'] as $listg){
                                  try {
                                        // Returns a `Facebook\FacebookResponse` object
                                        $response = $this->fb->post($listg['id'].'/feed', $linkData, $key->tokent);
                                      } catch(Facebook\Exceptions\FacebookResponseException $e) {
                                        echo 'Graph returned an error: ' . $e->getMessage();
                                        exit;
                                      } catch(Facebook\Exceptions\FacebookSDKException $e) {
                                        echo 'Facebook SDK returned an error: ' . $e->getMessage();
                                        exit;
                                      }
                                      
                                      $graphNode = $response->getGraphNode();
                                    
                                     
                                      
                                      //response('Posted with id: ' . $graphNode['id']);
                                      
                                      
                                      $data_insert = [
                                        'user_id' => $user_id,
                                        'ads_id' => $ads,
                                        'data' => $graphNode['id'],
                                        ];
                                      DB::table('facebook_post')->insert($data_insert);
                                     
                        }
                        
                  }   
          
        }
         
      }
    }
     public function postpage($users,$data){
		foreach($users as $user){
			try {
				$response = $this->fb->post($user->page_id.'/feed', $data, $user->tokent);
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}
			  
			$graphNode = $response->getGraphNode();
			  
			response('Posted with id: ' . $graphNode['id']);  
		}
             
    }
    public function test(){
    
      foreach(DB::table('users')->orderBy('created_at','ASC')->get() as $item){
        if(count(DB::table('template_setting')->where('user_id','=',$item->id)->first()) ==0){
        
          DB::table('template_setting')->insert(array('user_id'=>$item->id));
          
        }
      }
     /* 
      if(Input::hasFile('file')){
                
                $file =  Input::file('file');

                
                $filename = $file->getClientOriginalName();
        		    $destinationPath = 'media/test/';
                $upload_success = $file->move($destinationPath, $filename);
                $thumb= $destinationPath.$filename;
				
                dd(Input::hasFile('file'));
            }else{
               return view('test');
            }
           */ 
        
    }
    public function cbrgroup(){
      $linkData = [
          'link' => 'https://www.facebook.com/cbr.vn/',
          'message' => 'Bạn có biết tạo gian hàng trên CBR.VN bạn sẽ được 
          - Một website riêng giúp bạn dễ hơn trong việc quản bá, tăng uy tín
          - Sản phẩm được đăng trên 40 Group rao vặt, mua bán tại Facebook
          - Áp dụng cho cả cửa hàng kinh doanh và các cá nhân thanh lý đồ cũ
          - Mọi thứ đều hoàn toàn miễn phí
          Like page và Share để tăng thêm động lực cho chúng tôi bạn nhé!',
          
          
          ];
           $user_fb = DB::table('fb')->get();
        foreach ($user_fb as $key){ 
                   try {
                      // Returns a `Facebook\FacebookResponse` object
              $group = $this->fb->get('/me/groups?limit=50', $key->tokent);
                    } catch(Facebook\Exceptions\FacebookResponseException $e) {
                      echo 'Graph returned an error: ' . $e->getMessage();
                      exit;
                    } catch(Facebook\Exceptions\FacebookSDKException $e) {
                      echo 'Facebook SDK returned an error: ' . $e->getMessage();
                      exit;
                    }
                  
                    
                    $data_group = $group->getDecodedBody();
                    
                    foreach($data_group['data'] as $listg){
                          try {
                                // Returns a `Facebook\FacebookResponse` object
                                $response = $this->fb->post($listg['id'].'/feed', $linkData, $key->tokent);
                              } catch(Facebook\Exceptions\FacebookResponseException $e) {
                                echo 'Graph returned an error: ' . $e->getMessage();
                                exit;
                              } catch(Facebook\Exceptions\FacebookSDKException $e) {
                                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                                exit;
                              }
                              
                              $graphNode = $response->getGraphNode();
                         
              }
    }
    }
    public function postnew(){
       $linkData = [
          'link' => 'http://cbr.vn/',
          'message' => 'Bạn có biết tạo gian hàng trên CBR.VN bạn sẽ được 
          - Một website riêng giúp bạn dễ hơn trong việc quản bá, tăng uy tín
          - Sản phẩm được đăng trên 40 Group rao vặt, mua bán tại Facebook
          - Áp dụng cho cả cửa hàng kinh doanh và các cá nhân thanh lý đồ cũ
          - Mọi thứ đều hoàn toàn miễn phí
          Vậy tại sao lại không tham gia CBR.VN ngay hôm nay!',
          'picture' => 'http://cbr.vn/img/image-qc.png',
          'name' => 'CBR.VN Cùng Bạn Đồng Hành Chinh Phục Kinh Doanh Online',
         'caption'=>'http://cbr.vn',
          ];
      $user_fb = DB::table('fb')->get();
        foreach ($user_fb as $key){ 
          
            try {
                    // Returns a `Facebook\FacebookResponse` object
            $group = $this->fb->get('/me/groups?limit=50', $key->tokent);
                  } catch(Facebook\Exceptions\FacebookResponseException $e) {
                    echo 'Graph returned an error: ' . $e->getMessage();
                    exit;
                  } catch(Facebook\Exceptions\FacebookSDKException $e) {
                    echo 'Facebook SDK returned an error: ' . $e->getMessage();
                    exit;
                  }
                
                  
                  $data_group = $group->getDecodedBody();
                  
                  foreach($data_group['data'] as $listg){
                        try {
                              // Returns a `Facebook\FacebookResponse` object
                              $response = $this->fb->post($listg['id'].'/feed', $linkData, $key->tokent);
                            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                              echo 'Graph returned an error: ' . $e->getMessage();
                              exit;
                            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                              echo 'Facebook SDK returned an error: ' . $e->getMessage();
                              exit;
                            }
                            
                            $graphNode = $response->getGraphNode();
                       
            }
        }
    }
    public function listgroup(){
      $group = DB::table('fb_group')->get();
      return view('list-group-fb')->with('group',$group);
    }
    
  // database have field Ads ID - member id - time post
  
  //
  public function autopost(){
    $post = DB::table('autopost')->where('user_id','=',Auth::user()->id)->orderBy('created_at','DESC')->paginate(10);
    return view('users.autopost')->with('post',$post);
  }
    public function setting(){
      $user_fb = DB::table('fb')->where('user_id','=',Auth::user()->id)->first();
      try {
              // Returns a `Facebook\FacebookResponse` object
             $page = $this->fb->get('/me/accounts',$user_fb->tokent);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              echo 'Graph returned an error: ' . $e->getMessage();
              exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              exit;
            }
            $data_page = $page->getDecodedBody();
           
      return view('users.facesetting')->with('page',$data_page['data']);
    }
 public function settingstore(Request $request){
  $cate ="";
   foreach ($request->input('cate') as $item){
     $cate .= $item.',';
   }
    
 
   $user_fb = DB::table('fb')->where('user_id','=',Auth::user()->id)->first();
    try {
              // Returns a `Facebook\FacebookResponse` object
            
             $page = $this->fb->get('/me/accounts', $user_fb->tokent);
            
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              echo 'Graph returned an error: ' . $e->getMessage();
              exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              exit;
            }
         
            $data_page = $page->getDecodedBody();
            foreach($data_page['data'] as $pages){
              if($pages['id'] == $request->input('fanpage')){
                $name = $pages['name'];
              }
            }
   
    $data =[
      'page_id' => $request->input('fanpage'),
      'user_id'=>Auth::user()->id,
      'cate_id'=>$cate,
      'name'=>$name,
      ];
      
      DB::table('autopost')->insert($data);
      return redirect()->route('user.autopost') -> with('success_msg','Thiết lập Thành công!');
  }
  // Ham Post Page
  //xóa connect facebook
    public function unconnect(){
       DB::table('fb')->where('user_id','=', Auth::user()->id)->delete();
       DB::table('autopost')->where('user_id','=', Auth::user()->id)->delete();
       return redirect()->back();
    }
  //$ads is ads_id
  public function userpage($link,$price,$name,$phone,$user_id,$ads,$title,$image){
   
    $this->user_feed($link,$price,$name,$phone,$user_id,$ads,$title,$image);
    //post profile
    $this->postpage($link,$price,$name,$phone,$user_id,$ads,$title,$image);
    // post CBR.VN
    //get token user
        $user_fb = DB::table('fb')->where('user_id','=',Auth::user()->id)->first();
    //get page_id of user
    $cate_ads = DB::table('ads')->where('ads.id','=',$ads)->first();
    //get cate_id by ads_id
      $pages = DB::table('autopost')->where('user_id','=',Auth::user()->id)->get();
         
             $page = $this->fb->get('/me/accounts', $user_fb->tokent);
             // get data page admin for user
             $linkData = [
                  'link' => $link,
                  'message' =>'👉'.$title. '  ✌✌✌
                  👉 chỉ có: '.number_format($price) .' VNĐ 👏👏👏. 
                  (y) Truy cập ngay http://'.$name.'.cungcap.net/ để xem chi tiết.
                  Hoặc liên hệ gian hàng 💜💜: '.$name .' 👉  📞  Điện thoại: '.$phone.' 👉 để được tư vấn thêm.
                  🎉 Tin được đăng từ website  '.$name .'.cungcap.net.
                  ',
                  'picture' => url($image),
                  ];
              // set data content share
             $data_page = $page->getDecodedBody();
             // get page list for array
                    foreach ($data_page['data'] as $key){
                      // foreach page_id of facebook
                      foreach($pages as $userpage){
                        // foreach page_id of user
                        if ($key['id'] == $userpage->page_id){
                            // if page_id facebook = page_id of user
                            $token = $key['access_token'];
                            // set tokent for page post below
                            foreach (explode(',',$userpage->cate_id) as $cate_id){
                              if($cate_ads->ads_cate_id == $cate_id){
                               try {
                                      // Returns a `Facebook\FacebookResponse` object
                                      $response = $this->fb->post($userpage->page_id.'/feed', $linkData, $token);
                                    } catch(Facebook\Exceptions\FacebookResponseException $e) {
                                      echo 'Graph returned an error: ' . $e->getMessage();
                                      exit;
                                    } catch(Facebook\Exceptions\FacebookSDKException $e) {
                                      echo 'Facebook SDK returned an error: ' . $e->getMessage();
                                      exit;
                                    }
                              $graphNode = $response->getGraphNode();
                  
                               response('Posted with id: ' . $graphNode['id']);
                              }// end if ads cate vs auto post cate setting
                            } //End foreach cate auto post setting 
                        }
                      }  
                    }
    }
    public function destroy($id){
      $autopost = DB::table('autopost')->where('id','=',$id)->delete();
      return redirect()->route('user.autopost')->with('success_msg','Xóa thành công!');
    }
}
