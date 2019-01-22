<div id="post-message">
	<div id="m-header">
		<h5>Gửi tin nhắn
		<a href="javascript:void(0)" onclick="$('#post-message').slideToggle()">
			<i class="fa fa-close"></i>
		</a>	
		</h5>
	</div>
	<div class="m-body">
		<div class="row">
			<form id="send-message" action="" method="post" class="form-horizontal" role="form">

				<p style="margin-bottom: 10px;"></p>
				<div class="form-group">

	        		<label class="col-xs-12 col-md-3 col-lg-3 control-label">Gửi tới</label>
	        		<div class="col-xs-12 col-md-8 col-lg-8">
	        			<input id="get_user_checked" ng-keypress="getContact()" ng-model="to_member" type="text" class="form-control" name="to_member" value="">
	        			<div ng-class="{ 'show' : to_member != '' }" id="list-contact">
	        				<ul class="list-contact">
	        					<li ng-repeat="contact in contacts">

	        					</li>
	        				</ul>
	        			</div>
	        		</div>
	        	</div>
				<div class="form-group">

	        		<label class="col-xs-12 col-md-3 col-lg-3 control-label">Tiêu đề</label>
	        		<div class="col-xs-12 col-md-8 col-lg-8">
	        			<input type="text" class="form-control" name="title" value="">
	        		</div>
	        	</div>
				<div class="form-group">
					<label class="col-md-3 col-lg-3 control-label" for="message">Tin nhắn</label>
					<div class="col-sm-12 col-md-8 col-lg-8">
						<textarea class="form-control" name="message" id="message" cols="10" rows="5"></textarea>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-8 col-md-offset-3">
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-send"></i>
							Gửi
						</button>
						<button class="btn btn-default" onclick="$('#post-message').slideToggle()">Đóng</button>
					</div>
				</div>
				<div class="form-group">
					<label class="text-danger col-xs-11 col-xs-offset-1" for="" id="result"></label>
				</div>

			</form>
		</div>
	</div>
</div>