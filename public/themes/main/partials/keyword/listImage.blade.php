<div class="panel panel-primary">
    <div class="panel-heading">Image relate for {!! $keyword['keyword'] !!}</div>
    <div class="panel-body">
        <div class="form-group">
            <?php $a=0; ?>
            @foreach($keyword['image_relate'] as $imageRelate)
                <?php
                $a++;
                ?>
                @if($a==1)
                    <?php
                    $image=DB::connection('mongodb')->collection('mongo_image')
                        ->where('_id', (string)$imageRelate)->first();
                    ?>
                    <img class="img-responsive" id="showImageLarge" src="https:{{$image['attribute']['image']}}" alt="{{$image['title']}}" title="{{$image['title']}}">
                    <h4 class="text-center"><span class="text-light" id="showImageLargeLink"><span class="text-light">{{$image['title']}}</span></span></h4>
                    <?php break; ?>
                @endif
            @endforeach
        </div>
        <div class="form-group" id="thumbImage">
            <div class="row row-pad-5">
                <?php $b=0;?>
                @foreach($keyword['image_relate'] as $imageRelate)
                    <?php $b++; ?>
                    @if($b>1 && $b<=7)
                        <?php
                        $image=DB::connection('mongodb')->collection('mongo_image')
                            ->where('_id', (string)$imageRelate)->first();
                        ?>
                        <div class="col col-md-2 col-xs-2 mb-2">
                            <a class="showImageLink" href="https:{{$image['attribute']['image']}}" data-image="https:{{$image['attribute']['image']}}" data-title="{{$image['title']}}" data-url=""><img class="img-responsive" src="https:{{$image['attribute']['thumb']}}" alt="{{$image['title']}}" title="{{$image['title']}}"></a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>