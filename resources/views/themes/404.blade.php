<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>Lỗi không tìm thấy trang</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-body text-center">
				<h1>Không tìm thấy trang <small><font face="Tahoma" color="red">Lỗi 404</font></small></h1>
				<p>Trang này đã thay đổi hoặc bạn không có quyền truy cập vào trang này. </p>
				<a href="{{route('channel.home',$domain->domain)}}" class="btn btn-large btn-info"><i class="icon-home icon-white"></i> Quay về trang chủ</a>
			</div>
		</div>
	</div>
	</body>
</html>