<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <title>{{$message_title}}</title>
      
      <style type="text/css">
         /* Client-specific Styles */
         #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
         /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
         .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
         .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.  */
         #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
         img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
         a img {border:none;}
         .image_fix {display:block;}
         p {margin: 0px 0px !important;}
         table td {border-collapse: collapse;}
         table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
         a {color: #33b9ff;text-decoration: none;text-decoration:none!important;}
         /*STYLES*/
         table[class=full] { width: 100%; clear: both; }
         /*IPAD STYLES*/
         @media only screen and (max-width: 640px) {
         a[href^="tel"], a[href^="sms"] {
         text-decoration: none;
         color: #33b9ff; /* or whatever your want */
         pointer-events: none;
         cursor: default;
         }
         .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
         text-decoration: default;
         color: #33b9ff !important;
         pointer-events: auto;
         cursor: default;
         }
         table[class=devicewidth] {width: 100%!important;text-align:center!important;}
         table[class=devicewidthinner] {width: 420px!important;text-align:center!important;}
         img[class=banner] {width: 440px!important;height:220px!important;}
         img[class=colimg2] {width: 440px!important;height:220px!important;}
         
         
         }
         /*IPHONE STYLES*/
         @media only screen and (max-width: 480px) {
         a[href^="tel"], a[href^="sms"] {
         text-decoration: none;
         color: #ffffff; /* or whatever your want */
         pointer-events: none;
         cursor: default;
         }
         .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
         text-decoration: default;
         color: #ffffff !important; 
         pointer-events: auto;
         cursor: default;
         }
         table[class=devicewidth] {width: 100%!important;text-align:center!important;}
         table[class=devicewidthinner] {width: 260px!important;text-align:center!important;}
         img[class=banner] {width: 280px!important;height:140px!important;}
         img[class=colimg2] {width: 280px!important;height:140px!important;}
         td[class="padding-top15"]{padding-top:15px!important;}
         }
      </style>
   </head>
   <body style="width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; font-family: Helvetica Neue, Helvetica, Arial, Lucida Grande, sans-serif; line-height:25px;">
<!-- Start of preheader -->
<div style="">   
	<!-- Start of header -->
	<table style="background-color: #3b5998;  width: 100%;">
		<tr>
			<td></td>
			<td style="display: block !important; max-width: 800px !important; margin: 0 auto !important; clear: both !important;" width="100%">
				<div style="font-size:12px;color:#999;padding: 5px 0px;">
					<table style="" width="100%">
					  <tbody><tr>
						<td style="text-align:center; background:url(http://cungcap.net/img/logo-small-default.png) no-repeat center center; height:55px; "></td>
					  </tr>
				  </tbody></table>
				</div>
				<div style="width:98%;  margin: 0 auto; display: block;">
					<table style="background: #fff;" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td style="padding:20px;">
								<table width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td style="padding:0px; ">
											<h2 style="border-bottom: solid 1px #dadada; padding-bottom: 5px; text-align:center;">{{$message_title}}</h2>
										</td>
									</tr>
									<tr>
										<td style="padding: 0px;">
											<p><strong>Chào {{$user_name}}, </strong></p>
											{{$message_body}}
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr style="border-top: solid 1px #dadada; background: #3b5998;">
							<td align="center" valign="" style="color: #fff;padding-top: 10px;">
								<a href="http://{{config('app.url')}}" style="color: #fff;">{{config('app.appname')}}</a> là kênh cung cấp tất cả thông tin, sản phẩm, dịch vụ, ngành nghề từ các doanh nghiệp ở tất cả các khu vực. 
								<p style="font-size:11px;color: #d0d0d0;padding-top: 5px;">Email tự động gửi từ hệ thống, vui lòng không reply email này.</p>
							</td>
						</tr>
					</table>
				</div>
			</td>
			<td></td>
		</tr>
	</table>
</div>
<!-- End of postfooter -->   
   </body>
   </html>
