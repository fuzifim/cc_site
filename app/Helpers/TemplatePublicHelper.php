<?php 
	namespace App\Helpers;

    use App\Model\Template_setting;
    use App\Model\Slishow;
    use App\Model\Template;
    use App\Model\Ads;
	use App\Model\Domain;
    use WebService;
    use Media;
    use DB;
    use Route;
    use URL;
    use File;
    use Intervention\Image\Facades\Image as Image;

	class TemplatePublicHelper
	{

		public static function loginForm($sid)
		{
            return view('front.login')->with('sid',$sid)->render();
        }
        public static function Date($date,$format='d-m-Y'){
            $new_date = date($format, strtotime($date));
            return $new_date;
        }
        public static function price($price)
        {
            return number_format($price, 0);
        }
        public static function percentPrice($price,$discount)
        {
            $percent=($price/$discount)*100;
            return (100-(int)$percent)."%";
        }
        public static function urlCurrent(){
            return URL::current();
        }
        public static function CarouselSlishow($id){
            $template_setting=Template_setting::where('id','=',$id)->first();
            $src_imgs=DB::table('media')->whereIn('id',explode(',',$template_setting->media_banner))->get();
            //dd($src_imgs);
            //$count_src_img=count($src_img);
            //if(!empty($src_imgs)){
                return view('template.'.WebService::gettempfolderbyname($id).'.top_slider')->with('src_imgs',$src_imgs)->render();
            //}
        }
        public static function getMenu($name){
            $template_setting=Template_setting::where('id','=',$name)->first();
			$getDomain=Domain::where('site_id','=',$template_setting->id)->where('status','=','active')->where('domain_primary','=','default')->first();
				if(isset($getDomain->domain)){
					$domainPrimary=$getDomain->domain;
				}else{
					$domainPrimary=$template_setting->domain;
				}
			
            return view('template.'.WebService::gettempfolderbyname($name).'.inc.menu')
				->with('template_setting',$template_setting)
				->with('domainPrimary',$domainPrimary)
				->render();
        }
        public static function getTopAds(){
            $ads_tops=DB::table('ads as A')
                ->join('template_setting as B','A.template_setting_id','=','B.id')
                ->join('users as C','A.member_id','=','C.id')
                ->where(DB::raw('LENGTH(A.ads_title)'),'>',5)
                ->orderBy('A.updated_at','DESC')
                ->select('A.id','A.ads_title','A.ads_slug','A.ads_description','A.updated_at')
                ->take(config('app.ads_top_home'))
                ->get();
          //dd($ads_tops);
            return view('include.topAdsIndex')
                ->with('ads_tops',$ads_tops)
                ->render();
        }
        public static function loadHomeSlider(){
            $slishows=Slishow::where('status','=',0)->orderBy('order','DESC')->get();
            return view('front.slideshow.home_slider')->with('slishows',$slishows)->render();
        }
        public static function url_exists($url){
            $headers = @get_headers($url);
            $httpStatus = intval(substr($headers[0], 9, 3));
            if ($httpStatus<400)
            {
                return true;
            }
            return false;
        }
        public static function parseVideo($url){
            $re = "/(http:|https:|)\\/\\/(player.|www.)?(vimeo\\.com|youtu(be\\.com|\\.be|be\\.googleapis\\.com))\\/(video\\/|embed\\/|watch\\?v=|v\\/)?([A-Za-z0-9._%-]*)(\\&\\S+)?/";
            preg_match($re, $url, $matches);
            if(isset($matches[3])) {
                if (strrpos($matches[3], 'youtu') > -1) {
                    $type = 'youtube';
                    $src = 'https://www.youtube.com/embed/' . $matches[6];
                } else if (strrpos($matches[3], 'vimeo') > -1) {
                    $type = "vimeo";
                    $src = 'https://player.vimeo.com/video/' . $matches[6];
                } else {
                    return false;
                }


                return array(
                    'type' => $type // return youtube or vimeo
                , 'id' => $matches[6] // return the video id
                , 'src' => $src // return the src for iframe embed
                );
            }else{
                return false;
            }
        }
        public static function getHost($Address) {
            $parseUrl = parse_url(trim($Address));
            return trim($parseUrl['host'] ? $parseUrl['host'] : array_shift(explode('/', $parseUrl['path'], 2)));
        }
		public static function resize($image,$with=282,$height=160){
			$img = Image::cache(function($image) {
				$image->make('public/default.jpg')->resize($with,$height);
			}); 
			return $img;
		}
		public static function formatMoney($number, $fractional=false) { 
			if ($fractional) { 
				$number = sprintf('%.2f', $number); 
			} 
			while (true) { 
				$replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number); 
				if ($replaced != $number) { 
					$number = $replaced; 
				} else { 
					break; 
				} 
			} 
			return $number; 
		} 
		public static function date_convert($date,$format='m-d-Y H:i:s'){
			if(!empty($date)):
				$new_date = date($format, strtotime($date));
				return $new_date;
			else:
				return "";
			endif;
		}
    }


?>