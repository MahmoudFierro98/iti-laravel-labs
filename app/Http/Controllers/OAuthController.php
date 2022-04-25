<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class OAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $providerUser = Socialite::driver($provider)->user();
        // dd($providerUser);
        $user = User::where('provider_id', $providerUser->id)->first();
        if ($user) {
            $user->update([
                'provide_token' => $providerUser->token,
                'provide_refresh_token' => $providerUser->refreshToken,
            ]);
        } else {
            $user = User::create([
                'name' => $providerUser->name,
                'email' => $providerUser->email,
                'password' => $providerUser->token,
                'provider_id' => $providerUser->id,
                'provider_token' => $providerUser->token,
                'provider_refresh_token' => $providerUser->refreshToken,
            ]);
        }

        Auth::login($user);

        return redirect('/posts');
    }
}
