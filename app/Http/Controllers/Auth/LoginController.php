<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Structures\User;
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
//    protected $redirectTo = '/home';

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
     *      @SWG\Parameter(
     *          in="formData",
     *          name="email",
     *          required=true,
     *          type="string",
     *          format="email",
     *          description="User Email",
     *          @SWG\Schema(
     *              example="admin@test.com"
     *          ),
     *     ),
     *     @SWG\Parameter(
     *          in="formData",
     *          name="password",
     *          required=true,
     *          type="string",
     *          description="User Password",
     *          @SWG\Schema(
     *              example="secret_pass"
     *          ),
     *      ),
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
     *          response="default",
     *          description="Error",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="error",
     *                  type="object",
     *                  ref="#definitions/error"
     *              ),
     *          )
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

            return User::getOne($user);
        }

        return $this->sendFailedLoginResponse($request);
    }
}
