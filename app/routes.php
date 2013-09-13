<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('before' => 'check', function()
{
	return Redirect::to('dashboard/list-domain');
}));

Route::get('/dashboard', array('before' => 'check', function()
{
	return Redirect::to('dashboard/list-domain');
}));

/*
|--------------------------------------------------------------------------
| Filter Registration
|--------------------------------------------------------------------------
*/
Route::filter('check', function()
{

	if ( ! Sentry::check()) {

		return Redirect::to('sign-in');

	}

});

Route::filter('csrf', function()
{

	if (Request::forged()) return Response::error('500');

});


/*
|--------------------------------------------------------------------------
| Public Controller Router
|--------------------------------------------------------------------------
*/
Route::get('sign-up', 'PublicController@getSignUp');
Route::post('sign-up', 'PublicController@postSignUp');

Route::get('sign-in', 'PublicController@getSignIn');
Route::post('sign-in', 'PublicController@postSignIn');

Route::get('activate/{activationCode?}', 'PublicController@getActivateUser');

Route::get('reset-password', 'PublicController@getResetPassword');
Route::post('reset-password', 'PublicController@postResetPassword');

Route::get('change-password/{code?}', 'PublicController@getChangePassword');
Route::post('change-password', 'PublicController@postChangePassword');

Route::get('sign-out', function (){

	Sentry::logout();
	return Redirect::to('sign-in');

});
/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('dashboard/list-domain', 'DNSController@getListDomain');

Route::get('dashboard/add-domain', 'DNSController@getAddDomain');
Route::post('dashboard/add-domain', 'DNSController@postAddDomain');

Route::get('dashboard/delete-domain/{domainId?}', 'DNSController@getRemoveDomain');

Route::get('dashboard/domain-details/{domainId?}', 'DNSController@getViewDomainRecord');

Route::post('dashboard/add-record', 'DNSController@postAddRecord');

Route::get('dashboard/edit-record/{recordType?}/{recordId?}', 'DNSController@getEditRecord');
Route::post('dashboard/edit-record', 'DNSController@postEditRecord');

Route::get('dashboard/delete-record/{domainId?}/{recordId?}', 'DNSController@getDeleteRecord');