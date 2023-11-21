<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Get authenticated user.
     */
    public function current(Request $request)
    {
        return response()->json($request->user());
    }

    public function show(Request $request) {
        $user = User::findOrFail($request->id);

        return new UserResource($user);
    }

    public function index()
    {
        $users = User::all();

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request) 
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['success' => 'info_created'], 200);
    }

    public function update(UpdateUserRequest $request)
    {   
        // return $request->all();
        $user = User::findOrFail($request->id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json(['success' => 'info_updated'], 200);
    }

    public function destroy(Request $request) {

        $user = User::find($request->id);

        $user->delete();

        return response()->json(['success' => 'User deleted successfully.'], 200);
    }

    public function getUser($id) {
        $user = User::find($id);

        return new UserResource($user);
    }

}
