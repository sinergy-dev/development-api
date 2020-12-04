<!DOCTYPE html>
<html>
<head>
	<title>BAST</title>
	<style type="text/css">
	table > tbody > tr > td {
		/*font-size: 15px*/
	}
	body {
		line-height: 1.2;
		/*font-family: 'Book Antiqua';   */
		font-family:  "Times New Roman", Times, serif;
		padding:0px 50px 0px 50px;               
	}
</style>
<link href="http://fonts.cdnfonts.com/css/book-antiqua" rel="stylesheet">
</head>
<body>
	<div style="float:left;width:30%;opacity:0.85;text-align:center;">
		<img id="logo2" src="https://www.wartakriminal.co.id/wp-content/uploads/2017/05/pt-pln-logo.png.cf_.png" width="100px" height="100px" style="float: left;object-fit: cover;" alt="logo.png" title="Mystery"/>
	</div>
	<div style="float:right;width:30%;opacity:0.85;text-align:center;">
		<img src="https://cdn-kisikisi.qerja.com/assets/companies/images/logo/sinergy_informasi_pratama_pt_fb.jpg" style="float: right;object-fit: cover;" width="100px;" height="100px" id="logo">
	</div>

	<div style="clear: both;">
		<h3 style="text-align: center;"><u>BERITA ACARA SERAH TERIMA PEKERJAAN</u></h3>
		<p style="text-align: center;color: red;margin-top: -10px">{{$data['no_letter']}}</p>

		<p style="text-align: justify;">Berita Acara Serah Terima (BAST) ini dibuat pada hari <b>Jumat</b> tanggal <b>Dua puluh lima</b> bulan <b>Desember</b> tahun <b>Dua ribu lima belas</b>(25Desember 2015) antara:</p>
		<table style="width: 100%">
			<tr>
				<td style="width: 10%;vertical-align: top-left;">1</td>
				<td style="width: 90%;text-align: justify">{{$data['name_customer']}}, yang diwakili oleh {{$data['pic']}}, bertindak untuk dan atas nama {{$data['name_customer']}} ({{$data['location_name']}})</td>
			</tr>
			<tr>
				<td style="width: 10%;vertical-align: top-left;padding-top: 10px">2</td>
				<td style="width: 90%;text-align: justify;padding-top: 10px">PT.  Sinergy   Informasi   Pratama,   yang   diwakili   oleh  {{$data['name_moderator']}}, Moderator PT. Sinergy Informasi Pratama bertindak untuk dan atas nama PT. Sinergy Informasi Pratama</td>
			</tr>
		</table> 
		<br>
		<p style="text-align: justify;">Berdasarkan Berita Acara Pemeriksaan Pekerjaan No. <span style="color:red">{{$data['no_letter']}}</span>  berikut deskripsi pekerjaan:</p>
		<table>
			<tr>
				<td><b>Judul</b></td>
				<td>:</td>
				<td>{{$data['job_title']}}</td>
			</tr>
			<tr>
				<td><b>Kategori</b></td>
				<td>:</td>
				<td><i>{{$data['job_category']}}</i></td>
			</tr>
			<tr>
				<td><b>Lokasi</b></td>
				<td>:</td>
				<td>{{$data['job_location']}}</td>
			</tr>
			<tr>
				<td><b>Alamat</b></td>
				<td>:</td>
				<td>{{$data['job_address']}}</td>
			</tr>
		</table>
		<table style="width: 100%;page-break-after: always">
			<tr>
				<td><b>Deskripsi </b></td>
			</tr>
			<tr>
				<td style="vertical-align: top;">
					<ul>
						@foreach($data['job_description'] as $description)
						<li>{{$description}}</li>
						@endforeach
					</ul>
				</td>
			</tr>
			<tr>
				<td><b>Persyaratan Kerja</b></td>
			</tr>
			<tr>
				<td style="vertical-align: top;">
					<ul>
						@foreach($data['job_requirment'] as $requirment)
						<li>{{$requirment}}</li>
						@endforeach
					</ul>
				</td>
			</tr>
		</table>
		<p style="text-align: justify;">Demikian   Berita   Acara  Serah   Terima  ini   dibuat   untuk   dapat   dipergunakan sebagaimana mestinya.</p>
		<br>
		<table style="width: 100%" >
		<tr>
			<td style="text-align:center; width: 50%">
				Moderator Dispatcher
				<br>
				<br>
				<br>
				<br>
				<br>
				<b><u>{{$data['name_moderator']}}</u></b><br>
				<b>PT. Sinergy Informasi Pratama</b>
			</td>
			<td style="text-align:center; width: 50%">
				Partner In Charge(PIC) Customer
				<br>
				<br>
				<br>
				<br>
				<br>
				<b><u>{{$data['pic']}}</u></b><br>
				<b>{{$data['name_customer']}}</b>
			</td>
		</tr>
	</table>
	</div>

</body>
</html>