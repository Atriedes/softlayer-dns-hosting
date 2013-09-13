<h1>{{{ $subject }}}</h1>
<br /><br />
Please click link below to change your password
<br /><br />
<a href="{{ URL::to('change-password') }}/{{ $detail }}">Reset your password!</a>
<br /><br />
If dont work please go this page below <br />
{{ URL::to('change-password') }}
<br />
and enter this following code
<br/><br />
{{{ $detail }}}