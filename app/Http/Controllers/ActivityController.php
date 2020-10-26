<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Contracts\ActivityInterface;

class ActivityController extends Controller
{
    public function __construct( ActivityInterface $activity ) {
        $this->middleware( 'auth' );
        $this->activity = $activity;
    }
    /*User Activity List*/
    public function index( ) {
        $userActivities = $this->activity->getUsers();
        return view( 'home', compact( 'userActivities' ) );
    }
    /* Show Activity Details */
    public function edit( $id ) {
        $userActivity = $this->activity->findUser($id);
        return response()->json( array(
            'data' => $userActivity
        ) );
    }
    /* Update Activity Details */
    public function update( Request $request, $id ) {
        $request->validate( array(
            'activity_name' => 'required'
        ) );
        $this->activity->updateUser($id,$request);
        return response()->json( array(
            'success' => true
        ) );
    }
    /* Load More Activity */
    public function moreActivity( Request $request ) {
        $userActivityCount = $this->activity->moreActivity();
        if ( date( 'Y-m-d', strtotime( Auth::user()->created_at ) ) == date( "Y-m-d" ) ) {
            $newActivityLimit = 12;
        } else {
            $newActivityLimit = 2;
        }
        if ( count( $userActivityCount ) < $newActivityLimit ) {
            $this->activity->saveActivity( $request );
            return response()->json( array(
                'success' => true,
                'warning' => false
            ) );
        } else {
            return response()->json( array(
                'success' => false,
                'warning' => true
            ) );
        }
    }
    /* Response Handler */
    public function response_handler( $response ) {
        if ( $response ) {
            return json_decode( $response );
        }
        return array( );
    }
}