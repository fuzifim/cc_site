<?php
    Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
    Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
    Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
?>
<section>
    <div class="mainpanel">
        {!!Theme::partial('headerbar', array('title' => 'Header'))!!}
        <div class="pageheader">
            <h1><strong>List Cate News</strong></h1>
        </div>
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">List Cate News</div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name keyword" required>
                        </div>
                        <label class="error" for="name"></label>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Url news" required>
                        </div>
                        <label class="error" for="email"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>