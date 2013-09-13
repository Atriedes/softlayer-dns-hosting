<div class="block">
    <p class="block-heading">Login</p>
    <div class="block-body">

    	<?php foreach($errors->all() as $error)
        {

            echo 
            "<div class='alert alert-error'>
                <button type='button' class='close' data-dismiss='alert'>×</button>
                $error
            </div>";

        }
        ?>

        <form method="POST" action="">
        	{{ Form::token() }}
		    <label>Email</label>
		    {{ Form::text('email', Input::old('email'), array('class' => 'span12')) }}
		    <label>Password</label>
		    <input type="password" name="password" class="span12">
		    <input type="submit" class="btn btn-primary pull-right" value="Sign In">
		    <label class="remember-me"><input type="checkbox" name="remember" value="check"> Remember me</label>
		    <div class="clearfix"></div>
		</form>
    </div>
</div>
<p class="pull-right" style=""><a href="http://www.portnine.com" target="blank">Theme by Portnine</a></p>
<p><a href="{{ URL::to('reset-password') }}">Forgot your password?</a></p>