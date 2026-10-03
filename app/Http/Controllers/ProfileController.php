<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as RulesPassword;

class ProfileController extends Controller
{
    public function edit(){
        return view('profile.edit', [
            'user'=> Auth::user()
        ]);
    }

    public function update(Request $request){
        $user = Auth::user();
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', RulesPassword::min(8)->letters()->numbers()->symbols()],
        ]);
       

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ?? $user->password,
        ]);
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully');
    }
}
