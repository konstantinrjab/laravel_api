<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return response()->json(['data' => 'User logged out.'], 200);
    }

    /**
     * @SWG\Post(
     *      path="/login",
     *      tags={"user"},
     *      summary="Login User",
     *     @SWG\Schema(
     *          @SWG\Parameter(
     *              name="email",
     *              in="body",
     *              description="User Email",
     *              required=true,
     *              type="string",
     *          ),
     *          @SWG\Parameter(
     *              name="password",
     *              in="body",
     *              description="User Password",
     *              required=true,
     *              type="string",
     *          ),
     *     ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *              @SWG\Schema(
     *                  @SWG\Property(
     *                      property="user",
     *                      type="object",
     *                      ref="#definitions/user"
     *                  ),
     *              ),
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="Bad request"
     *     )
     *  )
     *
     * Login User
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();
            $user->generateToken();

            return response()->json([
                'user' => $user->toArray(),
            ]);
        }

        return $this->sendFailedLoginResponse($request);
    }
}
