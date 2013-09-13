<?php

class PublicController extends BaseController {

	private $setting;
    private $theme;

	public function __construct()
	{

		$this->setting = Setting::find(1);

        $this->theme = Theme::uses('blacktie')->layout('public');

        $this->theme->setCompany($this->setting->company_name);

	}

	public function getSignIn()
    {

        return $this->theme->scope('public.user.sign-in')->render();

    }

    public function postSignIn()
    {

        $input = Input::get();
        $rules = array(
            'email' => 'required|email',
            'password' => 'required');

        $validation = Validator::make($input, $rules);

        if ($validation->fails()) {

            return Redirect::to('sign-in')->withErrors($validation)->withInput();

        } else {

            try {

                $credentials = array(
                    'email' => $input['email'],
                    'password' => $input['password']);

                if (isset($input['remember'])) {

                    $user = Sentry::authenticateAndRemember($credentials);

                } else {
                    
                    $user = Sentry::authenticate($credentials, false);

                }
                

                Session::put('uid', $user->id);

                return Redirect::to('dashboard');

            } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {

                return Redirect::to('sign-in')->withErrors(array(
                    'login' => 'Email is required'))->withInput();

            } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {

                return Redirect::to('sign-in')->withErrors(array(
                    'login' => 'Password is required'))->withInput();

            } catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {

                return Redirect::to('sign-in')->withErrors(array(
                    'login' => 'Password not match with provided email'))->withInput();

            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {

                return Redirect::to('sign-in')->withErrors(array(
                    'login' => 'Email is not found in database'))->withInput();

            } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {

                return Redirect::to('sign-in')->withErrors(array(
                    'login' => 'This account havent activated yet'))->withInput();

            } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {

                return Redirect::to('sign-in')->withErrors(array(
                    'login' => 'This account has been suspended'))->withInput();

            } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {

                return Redirect::to('sign-in')->withErrors(array(
                    'login' => 'This account has been banned'))->withInput();

            }
        }

    }

    public function getLogout()
    {

    }

    public function getSignUp()
    {

    	if ($this->setting->allow_registration == 1) {

    		return $this->theme->scope('public.user.sign-up')->render();

    	} else {

    		App::abort(404, 'Page not found');

    	}
        

    }

    public function postSignUp()
    {

    	if ($this->setting->allow_registration == 1)
    	{
    		
    		$input = Input::get();

	        $messages = array(
	            'toc.required' => 'You have to accept term of condition!',
	        );

	        $rules = array('first_name' => 'required|min:3|alpha',
	                    'last_name' => 'required|min:3|alpha',
	                    'password' => 'required|min:6',
	                    'email' => 'required|email',
	                    'toc' => 'required');

	        $validation = Validator::make($input, $rules, $messages);

	        if ($validation->fails())
	        {

	            return Redirect::to('sign-up')->withErrors($validation)->withInput();

	        } else {
	            try {

	                if($input['password'] != $input['password2']) {

	                    return Redirect::to('sign-up')->withErrors(array(
	                        'password' => 'Password not match!'))->withInput();

	                }

	                $user = Sentry::register(array(
	                    'first_name' => $input['first_name'],
	                    'last_name' => $input['last_name'],
	                    'email' => $input['email'],
	                    'password' => $input['password']));

	                $key = $user->getActivationCode();

                    $email = array(
                        'to' => $input['email'],
                        'subject' => 'New account activation',
                        'name' => $input['first_name'].' '.$input['last_name'],
                        'detail' => $key);

	                Mail::send('emails.user.activation', $email, function($message) use ($email){

                        $message->from('no-reply@fvck.ml', 'Server Watcher Robot');
                        $message->to($email['to'])->subject($email['subject']);

	                });

	                return Redirect::to('sign-up')->with('success',1);

	            } catch (Cartalyst\Sentry\Users\UserExistsException $e) {

	                return Redirect::to('sign-up')->withErrors(array(
	                    'user' => 'User already exist!'))->withInput();

	            }
	        }

    	} else {

    		App:abort(404, 'Page not found');

    	}

    }

    public function getActivateUser($activationCode = '')
    {

        if ($activationCode) {

            try {

                $user = Sentry::getUserProvider()->findByActivationCode($activationCode);

                if ($user->attemptActivation($activationCode)) {

                    $group = Sentry::getGroupProvider()->findByName('Standard User');
                    $user->addGroup($group);

                    $throttle = Sentry::getThrottleProvider()->findByUserId($user->id);

                    $throttle->setAttemptLimit(5);

                    $email = array(
                        'to' => $user['email'],
                        'subject' => 'New account activated',
                        'name' => $user['first_name'].' '.$user['last_name'],
                        'detail' => '');

                    Mail::send('emails.user.active', $email, function($message) use ($email){

                        $message->from('no-reply@fvck.ml', 'Server Watcher Robot');
                        $message->to($email['to'])->subject($email['subject']);

                    });

                    $data = array('success' => 1,
                        'msg' => 'User activated successfully');

                    return $this->theme->scope('public.user.activation', $data)->render();

                } else {

                    $data = array('success' => 0,
                        'msg' => 'User activation failed');

                    return $this->theme->scope('public.user.activation', $data)->render();

                }

            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {

                $data = array('success' => 0,
                    'msg' => 'User activation failed');

                return $this->theme->scope('public.user.activation', $data)->render();

            } catch (Cartalyst\SEntry\Users\UserAlreadyActivatedException $e) {

                $data = array('success' => 0,
                    'msg' => 'User already activated!');

                return $this->theme->scope('public.user.activation', $data)->render();

            }
            
        } else {

            $data = array('success' => 0,
                'msg' => 'User activation failed!');

            return $this->theme->scope('public.user.activation', $data)->render();

        }

    }

    public function getResetPassword()
    {

        return $this->theme->scope('public.user.reset-password')->render();

    }

    public function postResetPassword()
    {

        $input = Input::get();

        $rules = array('email' => 'required|email');

        $validation = Validator::make($input, $rules);

        if ($validation->fails()) {

            return Redirect::to('reset-password')->withErrors($validation)->withInput();

        } else {

            try {

                $user = Sentry::getUserProvider()->findByLogin($input['email']);

                $resetCode = $user->getResetPasswordCode();

                $email = array(
                    'to' => $user['email'],
                    'subject' => 'Reset password confirmation',
                    'name' => $user['first_name'].' '.$user['last_name'],
                    'detail' => $resetCode);

                Mail::send('emails.user.reset-password', $email, function($message) use ($email){

                    $message->from('please@fvck.me', 'Server Watcher Robot');
                    $message->to($email['to'])->subject($email['subject']);

                });

                return Redirect::to('reset-password')->with(array(
                    'success' => 1,
                    'msg' => 'We have sent confirmation code to your email, please read you email
                            for further instruction!'));

            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {

                return Redirect::to('reset-password')->withErrors(array(
                    'user' => 'Email not found in database!'))->withInput();

            }

        }
        
    }

    public function getChangePassword($code = '')
    {

        $data = array(
            'code' => $code);

        return $this->theme->scope('public.user.change-password', $data)->render();

    }

    public function postChangePassword()
    {

        $input = Input::get();

        $rules = array(
            'password' => 'required|min:6',
            'code' => 'required');

        $validation = Validator::make($input, $rules);

        if ($validation->fails()) {

            return Redirect::to('change-password')->withErrors($validation)->withInput();

        } else {

            try {

                if ($input['password'] != $input['password2']) {

                    return Redirect::to('change-password')->withErrors(array(
                        'password' => 'Password not match!'));
                }

                $user = Sentry::getUserProvider()->findByResetPasswordCode($input['code']);

                if ($user->checkResetPasswordCode($input['code'])) {

                    if ($user->attemptResetPassword($input['code'], $input['password'])) {

                        return Redirect::to('change-password')->with(array(
                            'success' => 1,
                            'msg' => 'Password successfully changed, please login using your new password'));

                    } else {

                        return Redirect::to('change-password')->withErrors(array(
                            'password' => 'Change password failed, please contact our support team!'));

                    } 
                    
                } else {

                    return Redirect::to('change-password')->withErrors(array(
                        'code' => 'Invalid code'));
                    
                }

            } catch(Cartalyst\Sentry\Users\UserNotFoundException $e) {

                return Redirect::to('change-password')->withErrors(array(
                    'code' => 'Invalid code'));

            }
        }

    }
}