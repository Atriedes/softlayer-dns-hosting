<h1>{{{ $subject }}}</h1>
<br /><br />
Please click link below to activate your account
<br /><br />
<a href="{{ URL::to('activate') }}/{{ $detail }}">Activate Your Account!</a>
<br /><br />
If dont work please copy and paste link below to your browser <br />
{{ URL::to('change-password') }}/{{ $detail }}