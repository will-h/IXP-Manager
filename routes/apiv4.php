<?php
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api/v4" middleware group. Enjoy building your API!
|
*/

// API key can be passed in the header (preferred) or on the URL.
//
//     curl -X GET -H "X-IXP-Manager-API-Key: mySuperSecretApiKey" http://ixpv.dev/api/v4/test
//     wget http://ixpv.dev/api/v4/test?apikey=mySuperSecretApiKey


// all routes in this group require auth (handled by IXP\Http\Kernel.php) and AUTH_SUPERUSER privs:
Route::group( ['middleware' => 'assert.privilege:' . Entities\User::AUTH_SUPERUSER ], function() {

    Route::get('dns/arpa/{vlanid}/{protocol}',             'DnsController@arpa');   // ?format=json also works

    Route::get('nagios/birdseye_daemons',                  'NagiosController@birdseyeDaemons');
    Route::get('nagios/birdseye_daemons/{vlanid}',         'NagiosController@birdseyeDaemons');
    Route::get('nagios/birdseye_bgp_sessions/rs',          'NagiosController@birdseyeRsBgpSessions');
    Route::get('nagios/birdseye_bgp_sessions/rs/{vlanid}', 'NagiosController@birdseyeRsBgpSessions');

    Route::get('router/gen_config/{handle}',               'RouterController@genConfig' );

    Route::get('sflow-receivers/pretag.map',    'SflowReceiverController@pretagMap');
    Route::get('sflow-receivers/receivers.lst', 'SflowReceiverController@receiversLst');
});
