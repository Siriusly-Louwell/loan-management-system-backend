<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ApplicationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(User::all());
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
        $user = [];

        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email',
                'gender' => 'required|string',
                'password' => 'required|string',
                'role' => 'required|string',
                'status' => 'required|string',
            ]);

            $arr = [
                'first_name' => $validatedData['first_name'],
                'middle_name' => $request->middle_name,
                'last_name' => $validatedData['last_name'],
                'gender' => $validatedData['gender'],
                'contact' => $request->contact,
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'],
                'status' => $validatedData['status']
            ];
    
            if($validatedData['role'] == 'customer') {
                $application = ApplicationForm::where('record_id', $request->record_id)->firstOrFail();

                if($application->apply_status == "approved") {
                    $arr['pfp'] = $application->id_pic;
                    // $arr['first_name'] = $application->first_name;
                    // $arr['last_name'] = $application->last_name;
                    $arr['gender'] = $application->gender;

                    $user = User::create($arr);
                    $application->user_id = $user->id;
                    $application->save();
                    
                } else return response()->json(['message' => 'Your account is not approved yet', 'type' => 'invalid']);
            } else {
                if ($request->hasFile('pfp')) {
                    $pfp = $request->file('pfp')->store('uploads', 'public');
                }

                $arr['pfp'] = $pfp;
                $user = User::create($arr);
            }
    
            return response()->json([
                'message' => 'Account was created successfully!',
                'type' => 'valid',
                'user' => $user,
            ], 201);
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors ' => $e->errors()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|string',
                'brand' => 'sometimes|string',
                'color' => 'sometimes|string',
                'description' => 'sometimes|string',
                'price' => 'sometimes|numeric',
                'quantity' => 'sometimes|integer',
                'file_path' => 'sometimes|string'
            ]);
    
            $motorcycle->update($validatedData);
    
            return response()->json(['message' => 'Product was created successfully!'], 201);
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors ' => $e->errors()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $motorcycle
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
