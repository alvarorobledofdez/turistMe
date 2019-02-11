<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $tokenDecoded = JWT::decode($token, $key, array('HS256'));
        
        if($tokenDecoded->id == 1)
        {
            $users = User::where('role_id', 2)->get();
            
            return response()->json([
                'message' => $users
            ], 200);
                
        }
        else
        {
            return response()->json([
                'message' => 'You do not have enough permissions'
            ], 403);
        }  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        $name = $_POST['name'];
        $trimName = trim($name);
        $email = $_POST['email'];
        $password = $_POST['password']; 

        
        if(empty($trimName) || empty($email) || empty($password))
        {
            return response()->json([
                'message' => 'You should fill all the fields'
            ], 401);
        }

        if(strpos($trimName, " "))
        {
            return response()->json([
                'message' => 'The username can not have space'
            ], 401);
        }
        else
        {
            $user = new User();
            $user->name = $trimName;
            $users = User::where('email', $email)->get();

            if (!strpos($email, "@") || !strpos($email, ".")) 
            {
                return response()->json([
                    'message' => "The email has not been writen correctly"
                ], 400);
            }
            else
            {   
                foreach($users as $key => $value)
                {
                    if($email == $value->email)
                    {
                        return response()->json([
                            'message' => 'This email is already in use'
                        ], 401);
                    }
                }
                $user->email = $email;
            }

            
        }
        if(strlen($password) < 8)
        {
            return response()->json([
                'message' => 'The passwrod has to be longer than 7 characteres'
            ], 401);
        } 
        else 
        {
            $user->password = encrypt($password);
        }
        $user->role_id = '2';
        $user->save();                    
                       
        return response()->json([
            'message' => 'Register completed'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $tokenDecoded = JWT::decode($token, $key, array('HS256'));
        if($tokenDecoded->id == 1)
        {
            try{
                $userSave = User::find($user->id);
                if($userSave == null)
                {
                    return response()->json([
                        'message' => 'This user does not exists'
                    ], 401);
                }
                if(!empty($request->name))
                {
                    $trimName = trim($request->name);
                    if(strpos($trimName, " "))
                    {
                        return response()->json([
                            'message' => 'The username can not have space'
                        ], 401);
                    }
                    $userSave->name = $trimName;
                }
                
                if(!empty($request->email))
                {
                    if (!strpos($request->email, "@") || !strpos($request->email, ".")) 
                    {
                        return response()->json([
                            'message' => "The email has not been writen correctly"
                        ], 400);
                    }
                    $userSave->email = $request->email;
                }
                if(!empty($request->password))
                {
                    if(strlen($request->password) < 8)
                    {
                        return response()->json([
                            'message' => 'The passwrod has to be longer than 7 characteres'
                        ], 401);
                    } 
                    $userSave->password = encrypt($request->password);
                }

                $userSave->save();
                return response()->json([
                    'message' => 'The user has been updated',
                    'id' => $user->id
                ], 200);
            }
            catch(Exception $e)
            {
                return response()->json([
                    'message' => 'Error'
                ], 401);
            }
        }
        else
        {
            return response()->json([
                'message' => 'You do not have enough permissions'
            ], 403);
        } 
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $tokenDecoded = JWT::decode($token, $key, array('HS256'));

        
        if ($tokenDecoded->id == 1) 
        {
            $user->delete();
            return response()->json([
                'message' => 'The user has been deleted'
            ], 200);
        } 
        else
        {
            return response()->json([
                'message' => 'You do not have enough permissions'
            ], 403);
        }
    }  
}
