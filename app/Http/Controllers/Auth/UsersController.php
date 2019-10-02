<?php
/*
 * UsersController
 * This controller serves mainly to update user accounts.
 */
namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Rules\NoCurse;
use App\Rules\NoBadChar;
use App\Http\Controllers\Controller;
use App\User;
use App\Utility;
use App\users_pic;
use DB;
//using Intervention\Image\ImageServiceProvider to resize images
//install via composer:
//composer require intervention/image 
//refer to: http://image.intervention.io/use/basics
//After you have installed Intervention Image, open your Laravel config file config/app.php and add the following lines.
//In the $providers array add the service providers for this package.
//    Intervention\Image\ImageServiceProvider::class
//Add the facade of this package to the $aliases array.
//    'Image' => Intervention\Image\Facades\Image::class
use Intervention\Image\ImageManagerStatic as Image;

class UsersController extends Controller
{
    /* show edit user information view */
    public function showEditForm($id)
    {
        $userinfo = User::findOrFail($id);
        return view('/auth/edituser', compact('userinfo'));
    }
    
    /* update user information */
    /* update user picture */
    public function updateUserInfo(Request $request)
    {

        $this->validate($request, [
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
                        'unique:users,email,'.Auth::user()->id
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
            'image' => 'sometimes|mimes:jpeg,jpg,png,gif',
            'optcheck' => 'required'
        ]);
        
        /* get image type and image data before update to database*/
        if( $request->hasFile('image')) {
           $image = Utility::get_image_fromFile($request->file('image'));
        } else {
           $image = Utility::get_image_fromTable($request->id,'users');
        }  
        
        
        /* update user to database */
        try {   
            $user = Auth::user();
            /* The hiden optcheck will be over write by the follow checkbox,
             * if the checkbox been checked, will get value "true".
             * or the checkbox not checked, will get the default value "false"
             * in get('optcheck', "false")
             */
            $opt = $request->get('optcheck', "false");

            
            $user->update([
                'firstName'=> $request->firstName,
                'lastName'=> $request->lastName,
                'email'=> $request->email,
                'phone'=> $request->phone,
                'address'=> $request->address,
                'country'=> $request->country,
                'province'=> $request->province,
                'city'=> $request->city,  
                'opt'=> $opt,
                'image' => $image['data'],
                'image_type' => $image['type'],
            ]);
        } catch(\Illuminate\Database\QueryException $ex){ 
            return ['error' => 'error: modify users fail (002)']; 
            //return \Response::json(['status' => 'error', 'error_msg' => var_dump($ex)]);
        }
        
        return redirect()->route('home');
    }
    
    /* show unsuscribe user view, warning information */
    public function deleteUserInfo($id)
    {
        $userinfo = User::findOrFail($id);
        return view('/auth/deleteuser', compact('userinfo'));
    }
    
    /* delele user from database table users */
    /* delete image from users_pic table in database */
    public function deleteUserAction($id)
    {
         //search table Club, owner_id, 
        $club = DB::table('Club')
                    ->where('owner_id', $id)->get();
        //if this user is owner of a Club
        if ($club->isEmpty()) {
            try {
                $userinfo = User::findOrFail($id);
                DB::table('users_pic')
                    ->where('users_id', $id)
                    ->delete();
      
                $userinfo->delete();
                return redirect()->route('login');
            } catch(\Illuminate\Database\QueryException $ex){ 
                return redirect()->route('home');
            }
            
        }else{
            
            // do nothing, exit
            return redirect()->route('home');
        }
    }

}