<?php
use Nekrida\Core\Route;

Route::namespace('App\Controllers')
    ->where(['id' => '[0-9]+'])
    ->group([
        Route::middleware('Auth@unauthorizedOnly')->group([
            Route::get('/register','Auth@showRegister'),
            Route::post('/register','Auth@register'),
            Route::get('/balance/result','Balance@result'),
            Route::post('/balance/result','Balance@result'),
            Route::get('/.*','Auth@showLogin'),
            Route::post('/login','Auth@login'),
        ]),
        Route::get('/logout','Auth@logout')->middleware('Auth@authorizedOnly'),
        Route::middleware('Auth@correspondentOnly')->group([
            //Telegrams
            Route::get('/','Telegrams@showAllCorrespondent'),
            Route::get('/telegram/add','Telegrams@showAdd'),
            Route::post('/telegrams/delete','Telegrams@deleteMany'),
            Route::get('/telegrams','Telegrams@showAllCorrespondent'),
            Route::get('/telegrams/status/{status}','Telegrams@showAllCorrespondent'),

            Route::middleware('Telegrams@exists')->group([
                Route::get('/telegram/{id}','Telegrams@show'),
                Route::get('/telegram/{id}/edit','Telegrams@showEdit'),
            ]),
                Route::get('/telegrams/search','Telegrams@search'),
                    //Actions with telegrams
            Route::middleware('Telegrams@possible')->group([
                Route::post('/telegram/{id}/save','Telegrams@save'),
                Route::get('/telegram/{id}/send','Telegrams@send'),
                Route::post('/telegram/{id}/send/{save}','Telegrams@send'),
                Route::post('/telegram/{id}/sign','Telegrams@sign'),
                Route::match(['get','post'],'/telegram/{id}/recall','Telegrams@recall'),
                        //Return from head to employee
                Route::post('/telegram/{id}/return','Telegrams@return'),
                Route::post('/telegram/{id}/delete','Telegrams@delete'),
            ]),

            //Balance
            Route::get('/balance/add','Balance@add'),
            Route::post('/balance/add','Balance@new'),
            Route::get('/balance/confirm/{id}','Balance@confirm'),
            //Departments
            Route::get('/departments','Departments@showAll'),
            Route::ade('/departments','{id}','Departments'),
            Route::patch('/departments/{id}/edit','Departments@edit'),
                // Move balance?
            Route::get('/departments/balance/move','Departments@showMoveBalance'),
            Route::post('/departments/balance/move','Departments@moveBalance'),
                //Users
            Route::get('/users','CorrUsers@showAll'),
            Route::ade('/user','{id}','CorrUsers'),
            Route::get('/ajax/department/{id}','Departments@showAjax'),
            Route::get('/ajax/user/{id}','CorrUsers@showAjax'),

            //Profile
            Route::get('/profile','Profile@show'),
            Route::get('/profile/invoices','Profile@invoices'),
            Route::get('/profile/invoice/{id}','Profile@invoice'),
            Route::get('/profile/balance/{id}','Profile@balance'),
            Route::post('/profile/balance/{id}','Profile@setNewBalance'),
            Route::get('/user/change-password','Profile@changePassword'),
            Route::post('/user/change-password','Profile@setNewPassword'),

        ]),
        Route::middleware('Auth@telegraphistOnly')->group([
            Route::get('/','Telegrams@showAllTelegraphist'),
            Route::get('/telegrams','Telegrams@showAllTelegraphist'),
            Route::get('/telegrams/status/{status}','Telegrams@showAllTelegraphist'),
            Route::get('/telegrams/all','Telegrams@showArchiveTelegraphist'),
            //One telegram
            //Route::middleware('Telegrams@exists')->group([
                Route::get('/telegram/{id}','Telegrams@show'),
                Route::get('/telegram/{id}/edit','Telegrams@showEdit'),
                Route::post('/telegram/{id}/save','Telegrams@save'),
                Route::get('/telegram/{id}/accept','Telegrams@accept'),
                Route::post('/telegram/{id}/reject','Telegrams@reject'),
                Route::post('/telegram/{id}/send','Telegrams@sendToVector'),

				Route::get('/telegram/{id}/confirm','Telegrams@confirm'),
            //]),
            //Destinations
            Route::get('/destinations','Destinations@showAll'),
            Route::ade('/destination','{id}','Destinations'),
        ]),
        Route::middleware('Auth@adminOnly')->group([
            //Users
            Route::get('/users','Users@showAll'),
            Route::ade('/user','{id}','Users'),
            Route::get('/user/{id}/activate','Users@activate'),
            Route::get('/user/{id}/block','Users@block'),
            //Telegram cost
            Route::get('/telegrams/cost','Telegrams@showCost'),
            Route::post('/telegrams/cost','Telegrams@setCost'),
            /*
            Route::get('/departments','Departments@showAll'),
            Route::ade('/department','{dep}','Department'),
            Route::ade('/department/{dep}/user','{id}','Auth'),
            Route::post('/department/{dep}/user/{id}/block','Auth@block'),
            Route::post('/department/{dep}/user/{id}/unblock','Auth@unblock'),
            */
            //Companies
            Route::get('/','Companies@showAll'),
            Route::get('/companies','Companies@showAll'),
            Route::delete('/companies','Companies@delete'),
            Route::ade('/company','{id}','Companies'),
            Route::get('/company/{id}','Companies@show'),
            Route::post('/company/{id}/bill','Companies@bill'),
            Route::post('/company/{id}/block','Companies@block'),
            Route::get('/company/{id}/unblock','Companies@unblock'),
            Route::post('/company/{id}/balance/add','Companies@addBalance'),
            Route::post('/company/{id}/balance/remove','Companies@removeBalance'),

            Route::ade('/companies/{com}/user','{id}','Auth'),

            //Destinations
            Route::ade('/destinations/group','{id}','DestinationGroups'),
            Route::get('/destinations','Destinations@showAll'),
            Route::ade('/destination','{id}','Destinations'),

            //Requests for sign
            Route::get('/requests/sign','SignRequests@showAll'),
            Route::post('/request/{id}/sign/accept','SignRequests@accept'),
            Route::post('/request/{id}/sign/reject','SignRequests@reject'),

            //Requests for register
            Route::get('/requests','RegisterRequests@showAll'),
            Route::get('/requests/register','RegisterRequests@showAll'),
            Route::get('/request/{id}/register','RegisterRequests@show'),
            Route::post('/request/{id}/register/accept','RegisterRequests@accept'),
            Route::post('/request/{id}/register/reject','RegisterRequests@reject'),
            //Database backups
            Route::get('/database','Database@showAll'),
            Route::get('/database/create','Database@create'),
            Route::get('/database/{name}/download','Database@download'),
            Route::get('/database/{name}/restore','Database@restore'),
            Route::get('/database/{name}/delete','Database@delete'),
            Route::post('/database/upload','Database@upload'),

            //Certificates
                //CA
            Route::get('/certificates','Certificates@showAll'),
            Route::get('/certificate/add','Certificates@showAdd'),
            Route::post('/certificate/add','Certificates@generate'),
            Route::post('/certificate/{id}/recall','Certificates@recall'),
                //Client
            Route::get('/certificates/clients','ClientCertificates@showAll'),
            Route::post('/certificates/client/add','ClientCertificates@generate'),
            Route::post('/certificates/client/{id}/recall','ClientCertificates@recall'),

        ])
        //Route::get('/','Telegrams@showAdd'),
    ]);
