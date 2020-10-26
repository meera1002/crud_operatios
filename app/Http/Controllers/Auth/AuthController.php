<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Hash;
use GuzzleHttp\Client;
use App\UserActivity;
use Mail;

class AuthController extends Controller
{
    public function __construct( ) {
        $this->middleware( array(
            'guest'
        ) )->except( 'logout' );
    }
    /* Register Form */
    public function register( ) {
        return view( 'auth.register' );
    }
    /*Save user details and user activities */
    public function storeUser( Request $request ) {
        $request->validate( array(
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'captcha' => 'required|captcha'
        ) );
        $password = $request->password;
        $user     = User::create( array(
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make( $request->password ),
            'role_id' => 2,
            'type' => $request->type
        ) );
        $client   = new Client();
        for ( $i = 0; $i < 10; $i++ ) {
            if ( $request->type == 'diy' ) {
                if ( $i == 6 )
                    break;
            }
            if ( $request->type == 'music' ) {
                if ( $i == 9 )
                    break;
            }
            $response       = $client->request( 'GET', config( 'app.api_url' ) . 'activity?type=' . $request->type );
            $body           = $this->response_handler( $response->getBody()->getContents() );
            $userActivities = UserActivity::where( 'user_id', $user->id )->where( 'activity_key', $body->key )->first();
            if ( !$userActivities ) {
                $userActivity                = new UserActivity();
                $userActivity->user_id       = $user->id;
                $userActivity->activity_key  = $body->key;
                $userActivity->activity_name = $body->activity;
		$userActivity->price = $body->price;
                $userActivity->save();
            } else {
                $i--;
            }
        }
        Mail::send( 'mail.email_notification', array(
            'email' => $user->email,
            'password' => $password
        ), function( $message ) use ($user) {
            $message->to( $user->email, $user->name )->subject( 'Email Notification' );
        } );
        return redirect( '/' );
    }
    /* Login Form */
    public function login( ) {
        return view( 'auth.login' );
    }
    /*Post Login*/
    public function authenticate( Request $request ) {
        $request->validate( array(
            'email' => 'required|string|email',
            'password' => 'required|string'
        ) );
        $credentials = $request->only( 'email', 'password' );
        if ( Auth::attempt( $credentials ) ) {
            if ( Auth::user()->role_id == 1 ) {
                return redirect()->intended( 'admin-dashboard' );
            } else {
                return redirect()->intended( 'activity-list' );
            }
        }
        return redirect( '/' )->with( 'error', 'Oops! You have entered invalid credentials' );
    }
    /* Logout */
    public function logout( ) {
        Auth::logout();
        return redirect( '/' );
    }
    /* Response Handler */
    public function response_handler( $response ) {
        if ( $response ) {
            return json_decode( $response );
        }
        return array( );
    }
    /* Captcha Reloading */
    public function reloadCaptcha( ) {
        return response()->json( array(
            'captcha' => captcha_img()
        ) );
    }
}
