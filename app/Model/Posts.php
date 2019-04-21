<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model; 
use Elasticquent\ElasticquentTrait;
class Posts extends Model
{
	use ElasticquentTrait;
    protected $table = 'posts';
    public $timestamps = false; 
	protected $canBeRated = true; 
	protected $mustBeApproved = true; 
	function getIndexName()
	{
		return 'post_index';
	}
    protected $mappingProperties = array(
        'posts_title' => array(
            'type' => 'string',
            'analyzer' => 'standard'
        ),
        'posts_title_convert' => array(
            'type' => 'string',
            'analyzer' => 'standard'
        ),
        'posts_description'=>array(
            'type'=>'string',
            'analyzer'=>'standard'
        ),
        'posts_view'=>array(
            'type'=>'integer',
            'analyzer'=>'standard'
        )
    );

	public function postsTranslate(){
      return $this->hasMany('App\Model\Posts_translate', 'posts_id', 'id'); 
    }
	public function postsShareFacebook(){
        return $this->hasOne('App\Model\Posts_share', 'post_id', 'id')->where('social','=','facebook');
    }
	public function keywords(){
      return $this->hasMany('App\Model\Posts_join_keywords', 'post_id', 'id'); 
    }
	public function getSlug(){
        return $this->hasOne('App\Model\Slug', 'slug_table_id', 'id')->where('slug_table','=','posts');
    }
	public function postsJoinChannel(){
      return $this->hasOne('App\Model\Posts_join_channel', 'posts_id', 'id'); 
    }
	public function postsJoinField(){
      return $this->hasMany('App\Model\Posts_join_field', 'post_id', 'id'); 
    }
	public function postsJoinCategory(){
      return $this->hasMany('App\Model\Posts_join_category', 'posts_id', 'id'); 
    }
	public function joinKeywords(){
      return $this->hasMany('App\Model\Posts_join_keywords', 'post_id', 'id'); 
    }
	public function joinRegion(){
      return $this->hasOne('App\Model\Post_join_region', 'post_id', 'id'); 
    }
	public function joinSubRegion(){
      return $this->hasOne('App\Model\Post_join_subregion', 'post_id', 'id'); 
    }
	public function joinDistrict(){
      return $this->hasOne('App\Model\Post_join_district', 'post_id', 'id'); 
    }
	public function joinWard(){
      return $this->hasOne('App\Model\Post_join_ward', 'post_id', 'id'); 
    }
	public function attribute(){
      return $this->hasMany('App\Model\Posts_attribute', 'posts_parent_id', 'id'); 
    }
	public function contact(){
      return $this->hasOne('App\Model\Posts_attribute', 'posts_parent_id', 'id')->where('posts_attribute_type','=','contact'); 
    }
	public function author(){
      return $this->hasOne('App\Model\Posts_attribute', 'posts_parent_id', 'id')->where('posts_attribute_type', 'author'); 
    }
	public function options(){
      return $this->hasOne('App\Model\Posts_attribute', 'posts_parent_id', 'id')->where('posts_attribute_type', 'options'); 
    }
	public function price(){
      return $this->hasOne('App\Model\Posts_attribute', 'posts_parent_id', 'id')->where('posts_attribute_type','=','price'); 
    }
	public function quanlity(){
      return $this->hasOne('App\Model\Posts_attribute', 'posts_parent_id', 'id')->where('posts_attribute_type','=','quanlity'); 
    }
	public function gallery(){
      return $this->hasMany('App\Model\Posts_attribute', 'posts_parent_id', 'id')->where('posts_attribute_type','=','gallery'); 
    }
	public function commentJoinPost(){
      return $this->hasMany('App\Model\Comments_join', 'table_parent_id', 'id')->where('table','=','posts'); 
    }
	public function like(){
      return $this->hasMany('App\Model\Like', 'table_parent_id', 'id')->where('like_type','=','like')->where('like_table','=','posts'); 
    }
	public function unLike(){
      return $this->hasMany('App\Model\Like', 'table_parent_id', 'id')->where('like_type','=','unlike')->where('like_table','=','posts'); 
    }
}
