<div class="block">
    <p class="block-heading">Reset your password</p>
    <div class="block-body">
        @if (Session::get('success') === 1)

            {{ Session::get('msg') }}
            
        @else

            <?php

                foreach($errors->all() as $error)
                {
                    echo 
                    "<div class='alert alert-error'>
                        <button type='button' class='close' data-dismiss='alert'>×</button>
                        $error
                    </div>";
                }

            ?>

            <form url="" method="POST">
                {{ Form::token() }}
                <label>Email Address</label>
                {{ Form::text('email', Input::old('email'), array('class' => 'span12')) }}
                <input type="submit" class="btn btn-primary pull-right" value="Reset Password">
                <div class="clearfix"></div>
            </form>

        @endif
    </div>
</div>
<p class="pull-right" style=""><a href="http://www.portnine.com" target="blank">Theme by Portnine</a></p>
<p><a href="{{ URL::to('sign-up') }}">Create your account</a></p>