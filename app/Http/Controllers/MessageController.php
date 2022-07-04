<?php

namespace App\Http\Controllers;

use App\Http\Helpers\CustomHelper;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $users = User::where('id','!=',$id)->get();

        return view('messages')->with('users',$users);
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
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'to_id' => 'required|exists:users,id',
                'message' => 'required',
            ]);

            if ($validator->fails()) {
                $response = CustomHelper::prepareValidationResponse($validator->messages());
            }
            else{
                $input = $request->all();
                $input['from_id'] = Auth::id();

                $message = Message::create($input);

                $response = [
                    "status" => true,
                    "message" => 'Message added successfully',
                    "data" => $message,
                    "errors" => []
                ];
            }
        } catch (\Exception $e) {
            report($e);

            $response = [
                "status" => false,
                "message" => 'Error : '.$e->getMessage(),
                "data" => [],
                "errors" => []
            ];
        }

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $messages = Message::where(function ($query) use ($id) {
                $query->where('from_id',$id)->where('to_id',Auth::id());
            })->orWhere(function ($query) use ($id) {
                $query->where('from_id',Auth::id())->where('to_id',$id);
            })->get();

            $response = [
                "status" => true,
                "message" => 'Messages fetched successfully',
                "data" => $messages,
                "errors" => []
            ];

        } catch (\Exception $e) {
            $response = [
                "status" => false,
                "message" => 'Error : '.$e->getMessage(),
                "data" => [],
                "errors" => []
            ];
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Message::find($id)->delete();
                
            $response = [
                "status" => true,
                "message" => 'Order deleted successfully',
                "data" => [],
                "errors" => []
            ];
        } catch (\Exception $e) {
            report($e);

            $response = [
                "status" => false,
                "message" => __("general.error") . ': '.$e->getMessage(),
                "data" => [],
                "errors" => []
            ];
        }

        return response()->json($response);
    }
}
