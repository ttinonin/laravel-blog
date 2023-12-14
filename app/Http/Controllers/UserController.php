<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
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

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if($oldAvatar != "/fallback-avatar.jpg") {
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        } 

        return back()->with('success', 'Avatar saved!');
    }

    public function showAvatarForm() {
        return view('avatar-form');
    }

    private function getSharedData(User $user) {
        $isFollowing = 0;

        if(auth()->check()) {
            $isFollowing =  Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }

        View::share('sharedData', ['avatar' => $user->avatar, 'isFollowing' => $isFollowing, 'username' => $user->username, 'postCount' => $user->posts()->count(), 'followingCount' => $user->followingTheseUsers()->count(), 'followerCount' => $user->followers()->count()]);
    }

    public function profile(User $user) {
        $this->getSharedData($user);

        $thePosts = $user->posts()->latest()->paginate(10);
        
        return view('profile-posts', ['posts' => $thePosts]);
    }

    public function profileFollowers(User $user) {
        $this->getSharedData($user);
        
        $followers = $user->followers()->latest()->get();
        
        return view('profile-followers', ['followers' => $followers]);
    }

    public function profileFollowing(User $user) {
        $this->getSharedData($user);

        $following = $user->followingTheseUsers()->latest()->get();
        
        return view('profile-following', ['followings' => $following]);
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

        return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(20)]);
    }

    public function logout() {
        auth()->logout();
        return redirect('/')->with('success', 'You are now logged out');
    }
}
