<?php

use Illuminate\Http\Request;

Route::match(['get','post'],'/login','API\PassportController@login')->name('login');
Route::post('register','API\PassportController@register');
Route::post('forgetpassword','API\PassportController@forgetpassword');
Route::post('add-instrument','API\PassportController@addInstrument');
Route::get('get-instrument','API\PassportController@getInstrument');
Route::post('get-instrument-filter','API\PassportController@getInstrumentFilter');
Route::post('get-file','API\PassportController@getFile');
Route::get('download','API\PassportController@download');



Route::group(['middleware'=>'auth:api'],function(){
    Route::post('get-details','API\PassportController@getDetails');
    Route::post('logout','API\PassportController@logoutApi');
    Route::get('get-company','API\PassportController@getCompany');
    Route::post('delete-company','API\PassportController@deleteCompany');
    Route::post('update-company','API\PassportController@updateCompany');
    Route::post('get-company-by-id','API\PassportController@getCompanyById');
    Route::post('add-project','API\PassportController@addproject');
    Route::get('get-project','API\PassportController@getproject');
    Route::post('add-sensor-type','API\PassportController@addsensortype');
    Route::get('get-sensor-type','API\PassportController@getsensortype');
    Route::post('add-sensor','API\PassportController@addsensor');
    Route::get('get-sensor','API\PassportController@getsensor');
    Route::post('assign-project','API\PassportController@assignproject');
    Route::get('get-assign-project','API\PassportController@getassignproject');
    Route::post('delete-sensor-type','API\PassportController@deletesensortype');
    Route::post('delete-sensor','API\PassportController@deletesensor');
    Route::post('delete-project','API\PassportController@deleteproject');
    Route::post('csv-sensor-by-name','API\PassportController@csvSensorByName');
    Route::get('csv-sensor','API\PassportController@csvSensor');
    Route::post('upload-sesnors','API\PassportController@uploadsensors');
    Route::post('get-report-csv-data','API\PassportController@getreport');
    Route::post('get-multiple-sensor-data','API\PassportController@getmultiplesensordata');
    Route::post('get-sensors-on-type','API\PassportController@getsensorsontype');
    Route::post('convert-value','API\PassportController@convertvalue');
    
});


Route::match(['get','post'],'/register-company','API\CompanyController@registercompany');
Route::match(['get','post'],'/login-company','API\CompanyController@loginCompany')->name('login-company');

Route::group(['middleware'=>'auth:company'],function(){
    Route::post('get-details-company','API\CompanyController@getDetails');
    Route::post('logout','API\PassportController@logoutApi'); 
     
});




