<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
//Importing laravel-permission model
use Spatie\Permission\Models\Role;
//import laravel hash
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserPanelController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $role = new Role;
        if($user->roles()->pluck('id')->implode(' ') > 0)
            $role = Role::findOrFail($user->roles()->pluck('id')->implode(' '));
        if(Auth::user()->id != $id)
            abort(403);
        else
            return view('user-panel.edit', compact('user', 'role'));
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
        $request->validate([
            'current_password' => 'required',
            'password' => [
                Rule::notIn($request['current_password']),
                'required',
            ],
            'password_confirmation' => 'required|min:5|same:password',
        ]);

        $user = User::findOrFail($id);
        $hashedPassword = Hash::check($request['current_password'], $user->password);

        if(!$hashedPassword)
            $message = "Your current password does not match the one we have in the database.";
        else
        {
            $user->password = $request['password'];
            $user->save();
        }

        if(isset($message))
            return redirect()->route('user-panel.edit', $id)->withErrors($message);
        else
            return redirect()->route('user-panel.edit', $id)->with('flash_message', 'Your password has been updated!');
    }
}
