<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use Socialite;
use App\User;

class SocialController extends Controller
        {
        public function redirect($provider)
        {
            return Socialite::driver($provider)
            ->fields(['first_name', 'last_name', 'email', 'gender', 'birthday'])
            ->scopes(['email','user_birthday','user_posts','gender'])->redirect();
        }

        public function callback($provider)
        {
        $getInfo = Socialite::driver($provider)->user(); 
      //  $user = $this->createUser($getInfo,$provider); 
        dd($getInfo);
        //auth()->login($user); 
        return redirect()->to('/test');
        }

        function createUser($getInfo,$provider){
        $user = User::where('provider_id', $getInfo->id)->first();
        if (!$user) {
            $user = User::create([
                'name'     => $getInfo->name,
                'email'    => $getInfo->email,
                'provider' => $provider,
                'provider_id' => $getInfo->id,
                'remember_token' => $getInfo->token,

            ]);
        }
        return $user;
        }
 }
