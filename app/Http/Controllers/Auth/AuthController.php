<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Socialite;



class AuthController extends Controller
{
    protected $redirectPath = '/main';

    public function redirectToProvider()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function handleProviderCallback()
    {
        // try {
        //     $user = Socialite::driver('twitter')->user();
        // } catch (Exception $e) {
        //     return redirect('auth/twitter');
        // }
        // $authUser = $this->findOrCreateUser($user);
        // Auth::login($authUser, true);
        return redirect()->route('main');
    }

    private function findOrCreateUser($twitterUser)
    {
        $authUser = User::where('twitter_id', $twitterUser->id)->first();
        if ($authUser) {
            return $authUser;
        }
        return User::create([
            'name' => $twitterUser->name,
            'nickname' => $twitterUser->nickname,
            'twitter_id' => $twitterUser->id,
            'avatar' => $twitterUser->avatar_original
        ]);
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

}
