<?php
    $array_session=Session::get('history_cc');
?>
            @if(Session::has('history_cc'))
                        <!--history-->
                        <div class="panel panel-default load_history_index">
                               <div class="panel-heading company-chanel">
                                    <div class="pull-right company-like-galerry">
                                    <div class="company-chanel-delete">
                                        <a href="{{route('delete.history')}}" class="" title="Không quan tâm"><i class="glyphicon glyphicon-remove"></i></a>
                                    </div>
                                </div>
                                    <h4 class="company-name"><span class="text-none-ahref">Đã xem</span></h4>
                               </div>
                               <div class="panel-body company-chanel-body">
                                    <div id="watched_history" class="carousel slide four_shows_one_move portfolio_utube_carousel_wrapper" data-ride="carousel" data-interval="false" data-pause="hover">
                                      <!--========= Wrapper for slides =========-->
                                        <div class="carousel-inner" role="listbox">
                                        <!--========= 1st slide =========-->
                                        <?php
                                            $ads_group_content="";
                                            for($i=0;$i<count($array_session);$i++){
                                                $list_session=$array_session[$i];
                                                $ads_group_content=WebService::getAdsbyID($list_session['ads_id'],$list_session['template_id'])
												//if(!empty($ads_group_content)){
                                         ?>
										 <?
											$getDomain=App\Model\Domain::where('site_id','=',$ads_group_content->template_setting_id)->where('status','=','active')->where('domain_primary','=','default')->first(); 
											if(isset($getDomain->domain)){
												$domainPrimary=$getDomain->domain; 
											}else{
												$domainPrimary=$ads_group_content->domain; 
											}
										?>
										 @if(strlen($ads_group_content)>0)
                                           <div class="item @if($i==0) active @endif row">

                                               <div class="col-xs-12 col-sm-6 col-md-3 portfolio_utube_item">
                                                     <div class="portfolio_utube_item_image">
                                                        @if(strlen($ads_group_content->ads_thumbnail))
                                                               <a class="thumnail_tag coverImagePostThumbnail" href="{{route('front.ads.detail',array(App\Model\Regions::find($ads_group_content->regionsID)->iso,App\Model\Subregions::find($ads_group_content->subRegionsID)->subregions_name_slug,$ads_group_content->ads_slug))}}"><img src="{{$ads_group_content->ads_thumbnail}}" class="img-fluid lazy-load" alt="{{$ads_group_content->ads_title}}"/></a>
                                                        @endif

                                                        <a class="icon_cat" href="{{route('front.ads.detail',array(App\Model\Regions::find($ads_group_content->regionsID)->iso,App\Model\Subregions::find($ads_group_content->subRegionsID)->subregions_name_slug,$ads_group_content->ads_slug))}}" data-toggle="tooltip" data-placement="left" title="Hình ảnh">
                                                            @if(strlen(WebService::getCategoryOptionbyTemplates($ads_group_content->category_option_ads_id))>0)
                                                               <?php echo htmlspecialchars_decode(WebService::getCategoryOptionbyTemplates($ads_group_content->category_option_ads_id)->category_icon_span);?>
                                                            @else
                                                               <i class="glyphicon glyphicon-list-alt"></i>
                                                            @endif
                                                        </a>
                                                     </div>
                                                    <div class="portfolio_utube_item_caption clear">
                                                        <h4><a class="link_title_show" href="{{route('front.ads.detail',array(App\Model\Regions::find($ads_group_content->regionsID)->iso,App\Model\Subregions::find($ads_group_content->subRegionsID)->subregions_name_slug,$ads_group_content->ads_slug))}}">{{$ads_group_content->ads_title}}</a></h4>
                                                    @if(!empty($ads_group_content->ads_price))
                                                        <div class="product__price clear">
                                                            <span class="price">
                                                                <span itemprop="price" class="price__value">{!!Site::price($ads_group_content->ads_price)!!}</span>
                                                                <span class="price__symbol">đ</span>
                                                                @if(!empty($ads_group_content->discount))
                                                                     <span class="price__discount">-{!!Site::percentPrice($ads_group_content->ads_price,$ads_group_content->discount)!!}</span>
                                                                @endif
                                                            </span>
                                                       </div>
                                                        @if(!empty($ads_group_content->discount))
                                                        <div class="product__price product__price--list-price clear">
                                                               <span class="price price--list-price">
                                                                    <span class="price__value">{!!Site::price($ads_group_content->discount)!!}</span><span class="price__symbol">đ</span>
                                                               </span>
                                                           </div>
                                                        @endif
                                                    @endif

                                                   <div class="portfolio_utube_item_caption_author">
                                                       <span>bởi</span>
                                                       <a href="{{route('member.home',$domainPrimary)}}">{{$ads_group_content->title}}</a>
                                                       <i class="fa fa-check-square" data-toggle="tooltip" data-placement="top" title="Verified"></i>
                                                   </div>
                                                   <ul>
                                                      <li>{{$ads_group_content->ads_view}} lượt xem</li>
                                                      <li><span>.</span></li>
                                                      <li>{!!WebService::time_request($ads_group_content->updated_at)!!}</li>
                                                   </ul>
                                               </div>
                                               </div>
                                           </div><!--item-->
									     @else
										   <script type="text/javascript">
												jQuery(document).ready(function ($) {
													$('.load_history_index').hide();
												});
										   </script
										 @endif 		
                                           <?php  } ?>
                                        </div>
                                        <!--======= Left Button =========-->
                                        <a class="left carousel-control portfolio_utube_carousel_control_left" href="#watched_history" role="button" data-slide="prev">
                                            <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <!--======= Left Button =========-->
                                        <!--======= Right Button =========-->
                                        <a class="right carousel-control portfolio_utube_carousel_control_right" href="#watched_history" role="button" data-slide="next">
                                            <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                        <!--======= Right Button =========-->
                                    </div>
                              </div><!--panel-body-->
                        </div><!--panel-default panel-chanel-->
                        <!--End history-->
            @endif