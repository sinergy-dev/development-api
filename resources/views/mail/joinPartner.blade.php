<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		table {
		  border-collapse: collapse;
		}

		table, th, td {
		  border: 1px solid grey;
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
		<center><img style="width: 30%" src="{{env('API_LINK_CUSTOM2')}}/image/fillProgress.png" ></center>
	</div>
	<div style="line-height: 1.5em;padding: 10px;">
		<div style="color: #141414;font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<strong>
				<br>Hi {{$partner->name}},
			</strong>
			<p>
				@if($partner->status == "On Progress")
				<strong>Congratulation, you've been successfully filled partner candidate information on EOD registration! </strong>
				@elseif($partner->status == "OK basic")
					@if($partner->latest_education == "")
					<strong>Congratulation, you've been confirmed for your first stage registration. Waiting for Next Stage confirmation!</strong>
					@endif
				@elseif($partner->status == "OK Advance")
					<!-- @if($partner->interview == null)
					<strong>Congratulation, you've been confirmed for your second stage registration. Waiting for Interview Schedule!</strong>
					@else -->
					<strong>Congratulation, you've been confirmed for the Interview Stage!</strong>
					<p>Below is information about the interview schedule:</p>
					@if($partner->interview->status == "not started")
					<table>
						<tr>
							<th>Date</th><td>{{date('D, F jS Y', strtotime($partner->interview->interview_date))}}</td>
						</tr>
						<tr>
							<th>Time</th><td>{{date('g:i a',strtotime($partner->interview->interview_date))}}</td>
						</tr>
						<tr>
							<th>Link</th><td> this link not available </td>
						</tr>
					</table>
					@else
						@if($partner->interview->interview_result == null)
						<strong>Don't be late, Join link interview on information below!</strong>
						<br><br>
						<table>
							<tr>
								<th>Date</th><td>{{date('D, F jS Y', strtotime($partner->interview->interview_date))}}</td>
							</tr>
							<tr>
								<th>Time</th><td>{{date('g:i a',strtotime($partner->interview->interview_date))}}</td>
							</tr>
							<tr>
								<th>Link</th><td><a href="{{$partner->interview->interview_link}}">{{$partner->interview->interview_link}}</a></td>
							</tr>
						</table>
						@else
						<strong style="color: #3490dc">Interview Result :</strong>
						<p>
							{{$partner->interview->interview_result}}
						</p>
						@endif
					@endif
					<!-- @endif -->
				@elseif($partner->status == "OK Interview")
				<strong style="color: #3490dc">Interview Result :</strong>
				<p>
					{{$partner->interview->interview_result}}
				</p>
				<strong>Verifying and Adding Personal Information Data on website!</strong>

				<p>Please go to website  <a href="{{env('CUSTOM_URL_WEB_SIFOMA')}}/partner/{{$randomString}}">
				@elseif($partner->status == "OK Partner")
				<p>You`re now an glhf</p>

				<table>
					<tr>
						<td><p style="color: #3490dc">Username</p></td>
						<td>{{$partner->email}}</td>
					</tr>
					<tr>
						<td><p style="color: #3490dc">Password</p></td>
						<td>sinergy</td>
					</tr>
					<tr>
						<td><p style="color: #3490dc">App Store</p></td>
						<td><i>https://www.apple.com/ios/app-store</i></td>
					</tr>
					<tr>
						<td><p style="color: #3490dc">PlayStore</p></td>
						<td><i>https://play.google.com/store?hl=en</i></td>
					</tr>
				</table>
				@elseif($partner->status == "Reject")
				<strong>Sorry you haven't had the opportunity to continue the next test.</strong><br>
				<strong>keep up the spirit and keep trying! Thank's for join us and do your best.</strong>
				@endif
			</p>
			@if($partner->status != "Reject")
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
			<p> Follow up to your registration process!, Please visit to </p>
			<a href="{{env('CUSTOM_URL_WEB_SIFOMA')}}/partner/{{$randomString}}" target="_blank">EOD Web to continue</a>
			@endif
			<p>
				Disclaimer, if you have trouble while processing the registration, please contact us at 021-58355599 (Ext: 384) or email development@sinergy.co.id.
			</p>
			<p>
				If you've trouble while registration, please contact us:
			</p>
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