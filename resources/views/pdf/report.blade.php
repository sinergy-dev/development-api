<!DOCTYPE html>
<html>
<head>
	<title>Report Finish Job</title>
	<style type="text/css">
	table > tbody > tr > td {
		/*font-size: 15px*/
	}
	body {
		line-height: 1.2;
	}
</style>
</head>
<body>
	<h1 style="text-align: center;">Report Finish Job</h1>
	<h3 style="text-align: center;">By Rama Agastya</h3>

	<p>After the work has been carried out, the engineer must prepare a report document as proof of the work done. The following report documents have been compiled</p>
	<hr>
	<p>With the work we can describe as follows : </p>
	<table>
		<tr>
			<td><b>Job Title</b></td>
			<td>:</td>
			<td>{{$data['job_title']}}</td>
		</tr>
		<tr>
			<td><b>Job Category</b></td>
			<td>:</td>
			<td>{{$data['job_category']}}</td>
		</tr>
		<tr>
			<td><b>Job Location</b></td>
			<td>:</td>
			<td>{{$data['job_location']}}</td>
		</tr>
		<tr>
			<td><b>Job Address</b></td>
			<td>:</td>
			<td>{{$data['job_address']}}</td>
		</tr>
	</table>
	<table style="width: 100%">
		<tr>
			<td style="width: 50%"><b>Job Desciption</b></td>
			<td style="width: 50%"><b>Job Requirment</b></td>
		</tr>
		<tr>
			<td style="vertical-align: top;">
				<ul>
					@foreach($data['job_description'] as $description)
					<li>{{$description}}</li>
					@endforeach
				</ul>
			</td>
			<td style="vertical-align: top;">
				<ul>
					@foreach($data['job_requirment'] as $requirment)
					<li>{{$requirment}}</li>
					@endforeach
				</ul>
			</td>
		</tr>
		<tr>
			<td style="width: 50%"><b>Job Start : </b></td>
			<td style="width: 50%"><b>Job End : </b></td>
		<tr>
			<td>{{Carbon\Carbon::parse($data["job_progress"][0]->date_time)->format("l, d F - H:i")}}</td>
			<td>{{Carbon\Carbon::parse($data["job_progress"][sizeof($data["job_progress"]) - 1]->date_time)->format("l, d F - H:i")}}</td>
		</tr>
	</table>
	<hr>
	<b>Job Progress</b>
	<ul>
		@foreach($data["job_progress"] as $progress)
		<li>
			{{Carbon\Carbon::parse($progress->date_time)->format("d F - H:i")}} - [{{$progress->user->name}}] - {{$progress->detail_activity}}
		</li>
		@endforeach
	</ul>
	<hr style="page-break-after: always;">
	<b>Job Summary</b>
	<br>
	{{$data['job_summary']}}
	<table style="width: 100%">
		<tr>
			<td style="width: 50%"><b>Root Cause</b></td>
			<td style="width: 50%"><b>Counter Measure</b></td>
		</tr>
		<tr>
			<td style="vertical-align: top;">
				{{$data['job_rootcause']}}
			</td>
			<td style="vertical-align: top;">
				{{$data['job_countermeasure']}}
			</td>
		</tr>
	</table>
	<b>Documentation</b>
	<br>
	@if($data['job_documentation'] != "file")
		<img style="width: 100%;" src="{{$data['job_documentation']}}">
	@endif

</body>
</html>