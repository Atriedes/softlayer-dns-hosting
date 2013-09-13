<div class="block">
    <p class="block-heading">Account Activation</p>
    <div class="block-body">

    	@if ($success == 1)

    		Your account has been activated!

    	@else

    		Account activation failed

    	@endif
        
    </div>
</div>
<p class="pull-right" style=""><a href="http://www.portnine.com" target="blank">Theme by Portnine</a></p>
<p><a href="{{ URL::to('reset-password') }}">Forgot your password?</a></p>