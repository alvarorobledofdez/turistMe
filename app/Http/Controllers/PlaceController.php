<?php

namespace App\Http\Controllers;

use App\Place;
use App\User;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;

class PlaceController extends Controller
{
    public function index()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $tokenDecoded = JWT::decode($token, $key, array('HS256'));

        if ($this->isUserLogged($tokenDecoded->id , $tokenDecoded->password))
        {
            $places = Place::all();

            foreach ($places as $key => $place) 
            {
                if(count($places) != 0 && $place->user_id == $tokenDecoded->id)
                {
                    return response()->json([
                   "message"=>$places 
                    ]);  
                } 
                else
                {
                    return $this->error(401, 'You do not have any place created');
                }
            }
        }
        else
        {
            return $this->error(403, 'You do not have permissions');
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
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $tokenDecoded = JWT::decode($token, $key, array('HS256'));  

        

        $name = $_POST['name'];
        $description = $_POST['description'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$coordinateX = $_POST['coordinateX'];
		$coordinateY = $_POST['coordinateY'];
        $name = trim($name);

        if (empty($name))
    	{
        	return $this->error(401, 'Name can not be empty');
        }

        if (empty($start_date) || empty($end_date))
        {
            return $this->error(401, 'Dates can not be empty');
        }
        else 
        {
            try
            {
                $place = new Place();
                $place->name = $name;
                $place->description = $description;

                $startTime = strtotime($start_date);
                $startFormat = date('y-m-d', $startTime);
                $place->start_date = $startFormat;

                $endTime = strtotime($end_date);
                $endFormat = date('y-m-d', $endTime);
                $place->end_date = $endFormat;

                $place->coordinateX = $coordinateX;
                $place->coordinateY = $coordinateY;
                $place->user_id = $tokenDecoded->id;
                $place->save();
            }
            catch(Exception $e)
            {
                return $this->error(402, $e->getMessage());
            }
            return $this->error(200, 'Ubication created');
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
