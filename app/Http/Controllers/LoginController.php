<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;

class LoginController extends Controller
{
	protected function login(Request $request)
	{
	    if (!isset($_POST['email']) or !isset($_POST['password'])) 
	    {
	       return response()->json([
                'message' => 'You should fill all the fields'
            ], 401);
	    }

	    $email = $_POST['email'];
	    $password = $_POST['password'];
        $key = $this->key;

        if (self::isUserLogged($email, $password))
        {
            $userSave = User::where('email', $email)->first();
            
            if ($userSave == null)
       		{
            	return false;
        	}
            if ($userSave->role_id == 2)
            {
                $array = $arrayName = array
                (
                    'id' => $userSave->id,
                    'password' => $password
                );

                $jwt = JWT::encode($array, $key);
                return response()->json([
                    'token' => $jwt
                ], 200);
            }
            else
            {
                return response()->json([
                    'message' => 'You do not have permissions'
                ], 403);
            }
        }
    }

    protected function loginWeb(Request $request)
    {
        if (empty($_POST['email']) or empty($_POST['password'])) 
        {
             return response()->json([
                    'message' => 'You should fill all the fields'
                ], 401);
        } 
        else
        {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $key = $this->key;

            if (self::isUserLogged($email, $password))
            {
                $userSave = User::where('email', $email)->first();
            
                if ($userSave == null)
                {   
                    return false;
                }
                if ($userSave->role_id == 1)
                {
                    $array = $arrayName = array
                    (
                        'id' => $userSave->id,
                        'password' => $password
                    );

                    $jwt = JWT::encode($array, $key);
                
                    return response()->json([
                        'token' => $jwt
                    ], 200);
                }
                else
                {
                    return response()->json([
                        'message' => 'You do not have permissions'
                    ], 403);
                }
            }
            else
            {
                return response()->json([
                    'message' => 'This user does not exists'
                ], 401);
            }
        }
    }
}
