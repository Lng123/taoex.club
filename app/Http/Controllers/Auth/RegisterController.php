<?php



namespace App\Http\Controllers\Auth;



use App\User;
use App\Rules\NoCurse;

use App\Rules\NoBadChar;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use Illuminate\Foundation\Auth\RegistersUsers;



class RegisterController extends Controller

{

    /*

    |--------------------------------------------------------------------------

    | Register Controller

    |--------------------------------------------------------------------------

    |

    | This controller handles the registration of new users as well as their

    | validation and creation. By default this controller uses a trait to

    | provide this functionality without requiring any additional code.

    |

    */



    use RegistersUsers;



    /**

     * Where to redirect users after registration.

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

        $this->middleware('guest');

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

            'firstName' => ['required',
                            'string',
                            'max:255',
                            new NoCurse,
                            new NoBadChar
                        ],

            'lastName' => ['required',
                            'string',
                            'max:255',
                            new NoCurse,
                            new NoBadChar
                        ],
//email doesn't have NoCurse and NoBadChar because it causes problems with ordinary non-malicious email addresses.
            'email' => ['required',
                        'string',
                        'email',
                        'max:255',
                        'unique:users',
                    ],

            'phone' => ['required',
                        'string',
                        'max:11',
                        new NoCurse,
                        new NoBadChar
                    ],

            'address' => ['required',
                        'string',
                        'max:255',
                        new NoCurse,
                        new NoBadChar
                    ],

            'country' => ['required',
                        'string',
                        'max:255',
                        new NoCurse,
                        new NoBadChar
                    ],
            
            'province' => ['required',
                            'string',
                            'max:255',
                            new NoCurse,
                            new NoBadChar
                        ],

            'city' => ['required',
                        'string',
                        'max:255',
                        new NoCurse,
                        new NoBadChar
                    ],

            'password' => 'required|string|min:6|confirmed',
            
            /**
             * Simplest way to validate ReCaptcha
             */
            'g-recaptcha-response' => 'required|string|min:1',

            /**
             * Ensuring radio buttons are required fields
             */
            'optradio' => 'required',

        ]);

    }



    /**

     * Create a new user instance after a valid registration.

     *

     * @param  array  $data

     * @return \App\User

     */

    protected function create(array $data)

    {

        return User::create([

            'firstName' => $data['firstName'],

            'lastName' => $data['lastName'],

            'email' => $data['email'],

            'phone' => $data['phone'],

            'address' => $data['address'],

            'country' => $data['country'],

            'province' => $data['province'],

            'city' => $data['city'],

            'type' => 0,

            'approved_status' => 0,

            'password' => bcrypt($data['password'])

        ]);

    }

}