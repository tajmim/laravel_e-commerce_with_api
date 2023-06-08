<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class googleController extends Controller
{
  public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle(){
        try
        {

            $google_user = Socialite::driver('google')->stateless()->user();
            $user = User::where('google_id', $google_user->getId())->first();
            if (!$user)
            {
                $userData = [
                    'name' =>       $google_user->getName(),
                    'email' =>      $google_user->getEmail(),
                    'google_id' =>  $google_user->getId(),
                    'profile_photo_path' => $google_user->getAvatar()
                ];

                $user = User::create($userData);
            }
            

            $autUser = User::find($user->id);
            Auth::login($autUser);
            return redirect()->route('dashboard');
        }
        catch (\Exception $e)
        {
            Log::error($e);
            return redirect()->route('login');
        }
    }
}
