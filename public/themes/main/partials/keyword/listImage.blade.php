<div class="panel panel-primary">
    <div class="panel-heading">
        <h2 class="panel-title">Image relate for {!! $keyword['keyword'] !!}</h2>
    </div>
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

                    @if(empty($keyword['image']))
                        <?php
                        Theme::setImage('https:'.$image['attribute']['image']);
                        ?>
                    @endif
                    <img class="img-responsive" id="showImageLarge" src="https:{{$image['attribute']['image']}}" alt="{{$image['title']}}" title="{{$image['title']}}">
                    <h3 class="subtitle text-center"><span class="text-light" id="showImageLargeLink"><span class="text-light">{{$image['title']}}</span></span></h3>
                    <?php break; ?>
                @endif
            @endforeach
        </div>
    </div>
</div>