@if(count($ads_tops)>0)
<div class="panel panel-default list_ads_top_group">
            <div class="panel-heading">
                    <span class="glyphicon glyphicon-list-alt"></span> <b>Bài viết mới nhất</b>
             </div>
             <div class="panel-body">
                   <div class="row">
                          <div class="col-xs-12">
                               <ul class='list_item_ads_top clear'>
                                @foreach($ads_tops as $ads_top)
                                    <li>
                                        <h4 class="top_ads"><a href="{{route('front.ads.detail',$ads_top->ads_slug)}}">{{$ads_top->ads_title}}</a></h4>
                                        <div class="date__time clear"><i class="fa fa-calendar"></i> {!!WebService::time_request($ads_top->updated_at)!!}</div>
                                        <div class="des_ads_top clear">
                                        {{ str_limit(strip_tags(htmlspecialchars_decode($ads_top->ads_description)), $limit = 180, $end = '..') }}

                                        </div>

                                    </li>
                                @endforeach
                                </ul>
                           </div>
                    </div>
             </div>
             <div class="panel-footer">

             </div>
</div>
@endif