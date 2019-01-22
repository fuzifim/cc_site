<?php  

namespace App\Libraries;
use Illuminate\Support\Str;
use Response;
use Request;
class Helpers
{
	public function test(){
		return "hello";
	}
	
	public function url_admin($admin='adminwp',$string='update',$param='',$id=0){
		$url=Request::root()."/".$admin;
		if(!empty($string)) $url .="/".$string;
		if(!empty($param)) $url .="/".$param;
		if($id>0) $url.="/".$id;
		
		return $url;
	}
	public function create_crbox($box_id, $array, $type="checkbox", $checked="", $style="")
	{
		if(!$box_id || !$array)		error_msg("box_id or array empty", 2);
		$return_value = "";
		$i = 0;
		foreach($array as $key => $value){
			if($type == "checkbox"){

				if( is_array($checked) ){
					if( in_array($key, $checked) )	$equals = "checked='checked'";
					else							$equals = "";
				}else{
					if( ($checked != "") && ($checked == $key) )	$equals = "checked='checked'";
					else											$equals = "";
				}
				$return_value .= "<input type='checkbox' name='{$box_id}{$i}' id='{$box_id}{$i}' value='{$key}' {$equals} {$style}/> {$value}&nbsp;";
			}else{
				$equals = ($checked == $key) ? "checked='checked'" : "";

				$return_value .= "<input type='radio' name='{$box_id}' id='{$box_id}{$i}' value='{$key}' {$equals} {$style}/> {$value}&nbsp;";
			}
			$i++;
		}
		return $return_value;
	}
	public function Create_Selectbox($box_name, $array, $selected="", $opt="", $opt_first="Y")
	{
		$option = "";
		if($opt_first == "Y")	$option = "<option value=''>--Option--</option>";
		foreach($array as $key => $value){
			$SELECTED = ($selected == $key) ? "selected" : "";
			$option .= "<option value='{$key}' {$SELECTED}>{$value}</option>";
		}
		$selectbox = "<select name='{$box_name}' id='{$box_name}' {$opt}>{$option}</select>";
		return $selectbox;
	}
	function msg_move_page($msg,$url="back",$isExit=1){
		if($msg) echo "<script language='javascript'>alert('".$msg."');</script>";
		if($url){
			switch($url){
				case "home"	:
					echo "<script>location.href='/'</script>";
					break;
				case "back" :
					echo "<script language='javascript'>history.go(-1);</Script>";
					break;
				case "close" :
					echo "<script language='javascript'>self.close();</Script>";
					break;
				case "reload" :
					echo "<script language='javascript'>document.location.reload();</Script>";
					break;
				case "top_opener_reload" :
					echo "<script language='javascript'>top.opener.document.location.reload();</Script>";
					break;
				case "top_url" :
					echo "<Script language='javascript'>top.document.location.href = '".$url."'</Script>";
					break;
				case "parent_reload" :
					echo "<script language='javascript'>parent.document.location.reload();</Script>";
					break;
				default :
					echo "<script language='javascript'>document.location.replace('".$url."');</Script>";
					break;
			}
		}
		if($isExit) exit();
	}

	//Refresh url
	function move_page($url){
	    echo"<meta http-equiv=\"refresh\" content=\"0; url=$url\">";
	    exit;
	}
}
?>