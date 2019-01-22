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
use App\Model\Posts;
use App\Model\Posts_join;
use App\Model\Posts_join_channel;
use App\Model\Posts_join_category;
use App\Model\Posts_attribute; 
use App\Model\Fields; 
use App\Model\Regions;
use App\Model\Subregions;
use App\Model\Region_district;
use App\Model\Region_ward;
use App\Model\Channel; 
use App\Model\Channel_join; 
use App\Model\Domain; 
use App\Model\Domain_join;
use App\Model\Hosting; 
use App\Model\Hosting_join;
use App\Model\Cloud; 
use App\Model\Cloud_join;
use App\Model\Mail_server; 
use App\Model\Mail_server_join; 
use App\Model\Services; 
use App\Model\Services_attribute; 
use Carbon\Carbon;
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
use ParserXml; 
use Theme;
use WebService; 
use Route; 
use Redirect; 
use Response; 
use Input; 
use Validator; 
use Cart; 
use DateTime; 
use Lang; 
class FieldController extends ConstructController
{
	public function __construct(){
		parent::__construct(); 
	}
	public function show()
    {
		echo $this->_parame['id']; 
	}
	public function getChannelByFieldLocation()
    {
		if(!empty($this->_parame['fieldSlug'])){
			$slugField=explode('/',$this->_parame['fieldSlug']); 
			$getField=Fields::where('SolrID','=','/'.$this->_parame['fieldSlug'])->first(); 
			if(!empty($getField->id)){
				if(!empty($this->_parame['location'])){
					$getRegion=Regions::where('languages','=',Lang::locale())->first(); 
					$getSubRegion=Subregions::where('SolrID','=','/'.$getRegion->iso.'/'.$this->_parame['location'])->first(); 
					$getRegionWard=Region_ward::where('SolrID','=','/'.$getRegion->iso.'/'.$this->_parame['location'])->first(); 
					$getRegionDistrict=Region_district::where('SolrID','=','/'.$getRegion->iso.'/'.$this->_parame['location'])->first(); 
					if(!empty($getSubRegion->id)){
						return Redirect::route('channel.slug',array($this->_domain->domain,str_replace('/'.$getRegion->iso.'/','',$getSubRegion->SolrID.'/'.Str::slug($getField->SolrID))));
					}else if(!empty($getRegionDistrict->id)){
						return Redirect::route('channel.slug',array($this->_domain->domain,str_replace('/'.$getRegion->iso.'/','',$getRegionDistrict->SolrID.'/'.Str::slug($getField->SolrID))));
					}else if(!empty($getRegionWard->id)){
						return Redirect::route('channel.slug',array($this->_domain->domain,str_replace('/'.$getRegion->iso.'/','',$getRegionWard->SolrID.'/'.Str::slug($getField->SolrID))));
					}
				}
			}
		}
	}
	public function fieldSelect()
    {
		$fieldList=array(); 
		$getField=\App\Model\Fields::where('status','=',0)->orderBy('sort_order','desc')->get(); 
		foreach($getField as $field){
			//$categoryList[]=$joinCategory->category; 
		}
		$result='<option value="0">-- Danh mục chính --</option>';
		$result.=$this->tree_option($getField,$parent_id=0); 
		echo $result;
	}
	function has_children($rows, $id) {
        foreach ($rows as $row) {
            if ($row['parent_id'] == $id)
				return true;
        }
        return false;
    }
	public function tree_option($rows, $parent = 0,$spe = "--",$selected = -1){
        $result = "";
        if(count($rows) > 0){
            foreach ($rows as $key => $val){
                if($val['parent_id'] == $parent){
                    $att_selected = ($val['id'] == $selected) ? "selected" : "";
                    if($parent == 0)
                        $result .= "<option $att_selected value='$val->id'>$val->name</option>";
                    else
                        $result .= "<option $att_selected value='$val->id'>$spe$val->name</option>";
                    unset($rows[$key]);
                    if ($this->has_children($rows, $val['id'])){
                        $spe .= "--" ;
                        $result .= $this->tree_option($rows,$val['id'],$spe,$selected);
                    }
                }//end if
            }//end for
        }
        return $result;
    }
}