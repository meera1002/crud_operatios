<?php
namespace App\Repositories;

use App\Contracts\ActivityInterface;
use App\UserActivity;
use Auth;
use GuzzleHttp\Client;

class ActivityRepository implements ActivityInterface
{
    public function getUsers( ) {
        return UserActivity::where( 'user_id', Auth::user()->id )->paginate( 3 );
    }
    public function findUser( $id ) {
        return UserActivity::find( $id );
    }
    public function updateUser( $id, $request ) {
        UserActivity::where( 'id', $id )->update( array(
            'activity_name' => $request->activity_name
        ) );
    }
    public function moreActivity( ) {
        return UserActivity::select( 'id' )->whereDate( 'created_at', '=', date( 'Y-m-d' ) )->where( 'user_id', Auth::user()->id )->get();
    }
    public function saveActivity( $request ) {
        $i      = 0;
        $client = new Client();
        while ( $i == 0 ) {
            if ( config( 'app.api_url' ) ) {
                $response       = $client->request( 'GET', config( 'app.api_url' ) . 'activity?type=' . $request->type );
                $body           = $this->response_handler( $response->getBody()->getContents() );
                $userActivities = UserActivity::where( 'user_id', Auth::user()->id )->where( 'activity_key', $body->key )->first();
                if ( !$userActivities ) {
                    $userActivity                = new UserActivity();
                    $userActivity->user_id       = Auth::user()->id;
                    $userActivity->activity_key  = $body->key;
                    $userActivity->activity_name = $body->activity;
                    $userActivity->save();
                    $i++;
                }
            }
        }
    }
    private function response_handler( $response ) {
        if ( $response ) {
            return json_decode( $response );
        }
        return array( );
    }
}