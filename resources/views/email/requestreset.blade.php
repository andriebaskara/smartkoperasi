@extends('email.template')

@section('content')

	<p style="color:#777777;font-family:'Helvetica','Arial',sans-serif;font-size:15px;font-weight:normal;line-height:19px;">Hai <b>{{$reset->nama}}</b></p>
	<p style="color:#777777;font-family:'Helvetica','Arial',sans-serif;font-size:15px;font-weight:normal;line-height:19px;">Kami menerima permintaan reset password melalui aplikasi membership. Berikut token reset Anda. Copy token dan paste di aplikasi membership atau scan QR menggunakan aplikasi membership pada menu verifikasi token</p>

	<p style="color:#777777;font-family:'Helvetica','Arial',sans-serif;font-size:15px;font-weight:normal;line-height:19px;"><b>{{$reset->token}}</b></p>


	<div>
		<center>
			<img src="{!!$message->embedData(QrCode::format('png')->size(300)->backgroundColor(255,255,255)->generate($reset->token), 'QrCode.png', 'image/png')!!}">
		</center>
	</div>


@stop