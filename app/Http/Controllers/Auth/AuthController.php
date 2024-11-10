<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use App\ActivationService;
use DB;
use Gate;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $activationService;
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(ActivationService $activationService)
    {
        //$this->middleware('guest', ['except' => 'logout']);
        // $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
        $this->middleware($this->guestMiddleware(), ['except' => ['logout', 'showRegistrationForm', 'register']]);
        $this->activationService = $activationService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'dni' => 'required|numeric|unique:users',
            'telefono' => 'required|digits:9',
            'direccion' => 'required|max:255',
            'f_nac' => 'required|date',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'sexo' => 'required|max:1',
            'f_entrada' => 'required|date',
            'puesto' => 'required|integer|between:1,9999',
        ]);
    }

     /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
       return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'lastname' => $data['lastname'],
            'dni' => $data['dni'],
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion'],
            'f_nac' => $data['f_nac'],
            'sexo' => $data['sexo'],
            'f_entrada' => $data['f_entrada'],
            'puesto' => $data['puesto'],
        ]);
    }

    public function showRegistrationForm()
    {                 
        if (Gate::denies('register')) {
            return back();
        }

        $puestos = DB::table('puestos')->get();

        return view('auth.register')->with('puestos',$puestos);
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $user = $this->create($request->all());

        // $this->activationService->sendActivationMail($user);
        // return redirect('/login')->with('status', 'We sent you an activation code. Check your email.');
        return redirect('listado_usuarios');
    }
            /**
         * Return the authenticated response.
         *
         * @param  $request
         * @param  $user 
         * @return \Illuminate\Contracts\Routing\ResponseFactory
         */
        protected function authenticated(Request $request, $user)
        {
            if (!(bool)$user->activated) {
                //$this->activationService->sendActivationMail($user);
                auth()->logout();
                return back()->with('warning', 'You need to confirm your account. We have sent you an activation code, please check your email.');
            }
            if ($user->tienda_user==1) {
                auth()->logout();
                return back()->with('warning', 'No tienes permisos!');
            }
            
            return redirect()->intended($this->redirectPath());
        }


    public function activateUser($token)
    {
        if ($user = $this->activationService->activateUser($token)) {
            auth()->login($user);
            return redirect($this->redirectPath());
        }
        abort(404);
    }

}
