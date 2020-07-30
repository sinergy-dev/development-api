<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
// use Request;
use App\Http\Controllers\Controller; 
use App\User; 
use App\Users; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth; 
use Validator;

class UserController extends Controller
{
    //
    public $successStatus = 200;

    public function login(Request $req){ 
        if(Auth::attempt(['email' => $req->email, 'password' => $req->password])){ 
            $token = hash('sha256',Str::random(60));
            $user = Auth::user()->where('email',request('email'))->first();
            $user->api_token = $token;
            $user->save();
            return response()->json([
                'response' => [
                    'success' => 200,
                    'id_user' => $user->id,
                    'token' => $token
                ]
            ], $this->successStatus); 
        } else{ 
            return response()->json(['response' => ['success' => 401, 'message' => 'Unauthorised']],401 ); 
        } 
    }

    public function token(Request $req){ 
        // return $req->user()->id;
        $user = Users::find($req->user()->id);
        $user->fcm_token = $req->token;
        $user->save();

        return collect([
            "message" => "Success FCM Save",
            // "token" => $req->token,
        ]);
    }

    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

		$input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        $success['name'] =  $user->name;

		return response()->json(['success'=>$success], $this-> successStatus); 
    }

    public function details(){ 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    }
}
