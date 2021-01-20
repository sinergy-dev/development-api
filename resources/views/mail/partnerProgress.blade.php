<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		table {
		  border-collapse: collapse;
		}

		table {
			border: none;
		}

		/*.centered{
			position: absolute;
		  	top: 50%;
		  	left: 50%;
		  	transform: translate(-50%, -40%);
		}*/
	</style>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
</head>
<body style="display:block;width:600px;margin-left:auto;margin-right:auto;">
	<div style="line-height: 1.5em">
		<center><img style="width: 30%" src="{{env('API_LINK_CUSTOM2')}}/image/partnerProgress.png" ></center>
	</div>
	<div style="line-height: 1.5em;padding: 10px;">
		<div style="color: #141414;font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<strong>
				<br>Hi {{$partner->name}},
			</strong>
			<p>
				<p>You can pick the job based on your job category only from the EOD Mobile App and get paid. So, please download our mobile app in the App Store or Google Play Store. Below is your username and password for your EOD Mobile App. Thank you and good luck!</p>

				<table style="width: 100%">
					<tr>
						<th style="width: 20%;text-align: left;"><p>Username :</p></th>
						<td style="width: 80%"><b>{{$partner->email}}</b></td>
					</tr>
					<tr>
						<th style="width: 20%;text-align: left;"><p>Password :</p></th>
						<td style="width: 80%"><b>{{$partner->password_plain}}</b></td>
					</tr>
				</table>

				<center>
				<img style="width: 30%" src="{{env('API_LINK_CUSTOM2')}}/image/appstore.png">
				<img style="width: 30%" src="{{env('API_LINK_CUSTOM2')}}/image/gplay.png" >
				</center>

			</p>
			<div style="background-color: #E6E7E8;padding: 10px">
				<strong style="padding-left: 25px;color: #FFC548">Latest Activity</strong>
				<ul>
					<?php
						$no = 1;
					?>
					@foreach($activity as $data)
					@if($no == 1)
						<li>
							{{$data->history_detail}}
						</li>
					@else
						<li>
							{{$data->history_detail}}
						</li>
					@endif
					@endforeach
				</ul>
				<span style="padding-left: 25px;">Identifier code</span> <strong><span style="color: #FFC548">{{$randomString}}</span></strong>
			</div>
			<br>
			<p>Keep updated on your registration process and please visit to <a href="{{env('CUSTOM_URL_WEB_SIFOMA')}}/partner/{{$randomString}}" target="_blank">EOD Web</a> to continue</p>
			<p>
				If you have trouble with the registration process, please contact us:
			</p>
			<center>
				<img style="width: 30%" src="{{env('API_LINK_CUSTOM2')}}/image/phone.png">
				<img style="width: 30%" src="{{env('API_LINK_CUSTOM2')}}/image/mail.png" >
			</center>
			<p>
				Best Regard,<br><br>
				EOD Team
			</p>
		</div>
	</div>
	<div style="height:200px; width: 600px; background-size:100% 100%; background-repeat: no-repeat;color: #FFFFFF; font-family: 'Montserrat',sans-serif; vertical-align: middle; position: relative; background-image: url('{{env('API_LINK_CUSTOM2')}}/image/footer.png')">
		<!-- <img style="width: 100%" src="{{env('API_LINK_CUSTOM_PUBLIC')}}/image/footer.png"> -->
		<div class="centered">
			<b><p style="text-align: center;font-size: 12px; padding-top: 80px">
			PT. Sinergy Informasi Pratama (SIP)<br>
			Inlingua Building 2nd Floor<br>
			Jl. Puri Raya, Blok A 2/3 No. 33-35 Puri Indah<br>
			Kembangan Jakarta 11610 Indonesia<br>
			Phone 021 - 583 555 99<br>
			</p></b>
		</div>
	</div>
</body>
</html>