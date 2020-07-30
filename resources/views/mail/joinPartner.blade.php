<style type="text/css">
	table {
	  border-collapse: collapse;
	}

	table, th, td {
	  border: 1px solid grey;
	}
</style>
<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	<strong>
		<br>Hi ({{$partner->name}})
	</strong>
	<p>
		@if($partner->status == "On Progress")
		<strong>Congratulation, you've been success to filling first stage registration. Waiting for Confirmed Status! </strong>
		@elseif($partner->status == "OK basic")
			@if($partner->latest_education == "")
			<strong>Congratulation, you've been confirmed for your first stage registration. Waiting for Next Stage confirmation!</strong>
			@endif
		@elseif($partner->status == "OK Advance")
			@if($partner->interview == null)
			<strong>Congratulation, you've been confirmed for your second stage registration. Waiting for Interview Schedule!</strong>
			@else
				@if($partner->interview->status == "not started")
				<strong>Congratulation, you've been confirmed for Interview Stage!</strong>
				<br><br>
				<p>This below is more about interview schedule information : </p>
				<table>
					<tr>
						<th>Date</th><td>{{date('D, F jS Y', strtotime($partner->interview->interview_date))}}</td>
					</tr>
					<tr>
						<th>Time</th><td>{{date('g:i a',strtotime($partner->interview->interview_date))}}</td>
					</tr>
					<tr>
						<th>Link</th><td> - </td>
					</tr>
				</table>
				<p><span style="color: red">or you can access that link on our website</span> <a href="{{url('https://172.16.1.200:8080/partner')}}">https://EOD.co.id</a>.</p>
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
							<th>Link</th><td><a href="{{$partner->interview->interview_link}}" target="_blank">{{$partner->interview->interview_link}}</a></td>
						</tr>
					</table>
					@else
					<strong style="color: #3490dc">Interview Result :</strong>
					<p>
						{{$partner->interview->interview_result}}
					</p>
					@endif
				@endif
			@endif
		@elseif($partner->status == "OK Interview")
		<strong>Verifying and Adding Personal Information Data!</strong>
		<p>Please go to website  <a href="{{url('https://172.16.1.200:8080/partner')}}">https://EOD.co.id</a>. and <b style="color: red">accept</b> your policy!</p>
		@elseif($partner->status == "OK Partner")
		<strong>You're now a partner of the company.</strong>
		<p>You can pick the job based on your job category and get paid. But you can only pick the job from Sinergy Freelance App. So please download our mobile app in the play store or app store. And the following bellow is our username and password for your Sinergy Freelance App. Thank you and good luck!</p>

		<table>
			<tr>
				<td><p style="color: #3490dc">Username</p></td>
				<td>haloengineer</td>
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
		@endif
	</p>
	<table>
		<tr>
			<th colspan="2" style="text-align: center;">Lastest Activity</th>
		</tr>
		<?php
			$no = 1;
		?>
		@foreach($activity as $data)
		@if($no == 1)
			<tr style="background-color:z-index: 2;color: #fff;background-color: #3490dc;border-color: #3490dc;">
				<td>{{$no++}}.</td>
				<td>{{$data->history_detail}}</td>
			</tr>
		@else
			<tr>
				<td>{{$no++}}</td>
				<td>{{$data->history_detail}}</td>
			</tr>
		@endif
		@endforeach
		<tr>
			<th colspan="2" style="text-align: left;">
				<span style="color: red">Identifier Code : {{$randomString}}</span>
			</th>
		</tr>
	</table>
	<br>
	<p>Follow up to your registration progress!, Please visit to <a href="{{url('https://172.16.1.200:8080/partner')}}">https://EOD.co.id</a>. And enter your uniq code identifier above. And <b style="color: red">Don't Forget to Remember your Identifier Code!</b>
	</p>
	<p>
		How to get your progress information?
		<br>
		1. Open link <a href="{{url('https://172.16.1.200:8080/partner')}}">https://EOD.co.id</a> or copy then paste that link on browser url.<br>
		2. After open it, select `<i style="color: red"><b>Have been join</b></i>` beside `<i>Show me</i>` button.<br>
		3. Paste your identifier code on the column field.<br>
		4. Press button submit.<br>
		5. Then you'll know your lastest activity of registration stage.<br>
		6. Thank you.

	</p>
	<p>
		Disclaimer,
		if you`ve trouble while next regristation, please contact us at (Ext: 384) or email development@sinergy.co.id. 
	</p>
	<p>
		Thanks<br>
		Best Regard,
	</p>
	<h5 style="color: #f39c12 !important;margin-top: 0px" class="text-yellow" ><i>Tech - Dev</i></h5>
	<p>
		----------------------------------------<br>
		PT. Sinergy Informasi Pratama (SIP)<br>
		| Inlingua Building 2nd Floor |<br>
		| Jl. Puri Raya, Blok A 2/3 No. 33-35 | Puri Indah |<br>
		| Kembangan | Jakarta 11610 â€“ Indonesia |<br>
		| Phone | 021 - 58355599 |<br>
		----------------------------------------<br>
	</p>
</div>