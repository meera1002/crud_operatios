<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserActivity;

class AdminController extends Controller
{
    public function __construct( ) {
        $this->middleware( 'auth' );
    }
    /* Admin User and Activity List */
    public function index( ) {
        $userActivities = UserActivity::with( 'user' )->paginate( 10 );
        return view( 'admin-dashboard', compact( 'userActivities' ) );
    }
    /*Show Activity Details */
    public function show( $id ) {
        $userActivity = UserActivity::with( 'user' )->find( $id );
        return response()->json( array(
            'data' => $userActivity
        ) );
    }
    /* Delete Activity */
    public function destroy( $id ) {
        $userActivity = UserActivity::find( $id );
        $userActivity->delete();
        return redirect( '/admin-dashboard' )->with( 'message', 'Activity deleted!' );
    }
}