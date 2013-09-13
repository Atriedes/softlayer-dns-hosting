<div class="block">
    <p class="block-heading">Sign Up!</p>

    <div class="block-body">
        @if (Session::get('success') === 1)

            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Add Domain Success</strong>
            </div>

        @else

            <?php foreach($errors->all() as $error)
            {

                echo 
                "<div class='alert alert-error'>
                    <button type='button' class='close' data-dismiss='alert'>×</button>
                    $error
                </div>";

            }
            ?>

            <form url="{{ URL::current() }}" method="POST">
                {{ Form::token() }}
                <label>First Name</label>
                {{ Form::text('first_name', Input::old('first_name'), array('class' => 'span12')) }}
                <label>Last Name</label>
                {{ Form::text('last_name', Input::old('last_name'), array('class' => 'span12')) }}
                <label>Email Address</label>
                {{ Form::text('email', Input::old('email'), array('class' => 'span12')) }}
                <label>Password</label>
                <input type="password" name="password" class="span12">
                <label>Confirm Password</label>
                <input type="password" name="password2" class="span12">
                <input type="submit" class="btn btn-primary pull-right" value="Sign Up!">
                <label class="remember-me"><input type="checkbox" name="toc"> I agree with the <a href="terms-and-conditions.html">Terms and Conditions</a></label>
                <div class="clearfix"></div>
            </form>

        @endif
    </div>
</div>
<p class="pull-right" style=""><a href="http://www.portnine.com" target="blank">Theme by Portnine</a></p>
<p><a href="reset-password.html">Forgot your password?</a></p>