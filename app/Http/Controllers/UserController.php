<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function storeAvatar(Request $request) {
        $incomingFields = $request->validate([
            "avatar" => 'required|image|max:6000',
        ]);

        $user = auth()->user();

        $filename = $user->id . '-' . uniqid() . '.jpg';

        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');

        Storage::put("public/avatars/{$filename}", $imgData);
    }

    public function showAvatarForm() {
        return view('avatar-form');
    }

    public function profile(User $user) {
        $thePosts = $user->posts()->latest()->get();
        
        return view('profile-posts', ['username' => $user->username, 'posts' => $thePosts, 'postCount' => $user->posts()->count()]);
    }

    public function register(Request $request) {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);

        $user = User::create($incomingFields);

        auth()->login($user);

        return redirect('/')->with('success', 'Account successfully created');
    }

    public function login(Request $request) {
        $incomingFields = $request->validate([
            "loginusername" => 'required',
            "loginpassword" => 'required'
        ]);

        if(!auth()->attempt([
            "username" => $incomingFields["loginusername"],
            "password" => $incomingFields["loginpassword"],
        ])) {
            return redirect('/')->with('error', 'Invalid login');
        } 
        
        $request->session()->regenerate();
        return redirect('/')->with('success', 'You are now logged in');
    }

    public function showCorrectHomepage() {
        if(!auth()->check()) {
            return view('homepage');
        }

        return view('homepage-feed');
    }

    public function logout() {
        auth()->logout();
        return redirect('/')->with('success', 'You are now logged out');
    }
}
