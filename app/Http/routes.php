/*
|--------------------------------------------------------------------------
| Animal Routes
|--------------------------------------------------------------------------
|
| Here are all routes relating to the Animal model. A restful routing naming
| convention has been used, to allow index, show, edit, update, create and
| store actions on the Animal model.
|
*/

Route::group(['prefix' => 'animal'], function () {

    Route::get('/',         ['as' => 'animal.index',    'uses' => 'AnimalController@index']);
    Route::get('/{id}',     ['as' => 'animal.show',     'uses' => 'AnimalController@show']);
    Route::get('/{id}/edit',['as' => 'animal.edit',     'uses' => 'AnimalController@edit']);
    Route::post('/update',  ['as' => 'animal.update',   'uses' => 'AnimalController@update']);
    Route::get('/create',   ['as' => 'animal.create',   'uses' => 'AnimalController@create']);
    Route::get('/store',    ['as' => 'animal.store',    'uses' => 'AnimalController@store']);

});
/*
|--------------------------------------------------------------------------
| TempEstados Routes
|--------------------------------------------------------------------------
|
| Here are all routes relating to the TempEstados model. A restful routing naming
| convention has been used, to allow index, show, edit, update, create and
| store actions on the TempEstados model.
|
*/

Route::group(['prefix' => 'tempestados'], function () {

    Route::get('/',         ['as' => 'tempestados.index',    'uses' => 'TempEstadosController@index']);
    Route::get('/{id}',     ['as' => 'tempestados.show',     'uses' => 'TempEstadosController@show']);
    Route::get('/{id}/edit',['as' => 'tempestados.edit',     'uses' => 'TempEstadosController@edit']);
    Route::post('/update',  ['as' => 'tempestados.update',   'uses' => 'TempEstadosController@update']);
    Route::get('/create',   ['as' => 'tempestados.create',   'uses' => 'TempEstadosController@create']);
    Route::get('/store',    ['as' => 'tempestados.store',    'uses' => 'TempEstadosController@store']);

});
/*
|--------------------------------------------------------------------------
| TempEstados Routes
|--------------------------------------------------------------------------
|
| Here are all routes relating to the TempEstados model. A restful routing naming
| convention has been used, to allow index, show, edit, update, create and
| store actions on the TempEstados model.
|
*/

Route::group(['prefix' => 'tempestados'], function () {

    Route::get('/',         ['as' => 'tempestados.index',    'uses' => 'TempEstadosController@index']);
    Route::get('/{id}',     ['as' => 'tempestados.show',     'uses' => 'TempEstadosController@show']);
    Route::get('/{id}/edit',['as' => 'tempestados.edit',     'uses' => 'TempEstadosController@edit']);
    Route::post('/update',  ['as' => 'tempestados.update',   'uses' => 'TempEstadosController@update']);
    Route::get('/create',   ['as' => 'tempestados.create',   'uses' => 'TempEstadosController@create']);
    Route::get('/store',    ['as' => 'tempestados.store',    'uses' => 'TempEstadosController@store']);

});
