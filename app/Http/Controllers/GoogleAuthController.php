<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LoginUser;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use PhpOpenSourceSaver\JWTAuth\Facades\JWTAuth; // Ensure JWTAuth facade is imported
// use Tymon\JWTAuth\Facades\JWTAuth;


// use Illuminate\Routing\Controller;
class GoogleAuthController extends Controller
{
use ResponseTrait;

    public function redirectToGoogle(Request $request)
    {
        // redirect user to "login with Google account" page
        return Socialite::driver('google')->redirect();
    }
    public function handleCallback(Request $request)
    {
        $code = $request->input('code');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code',
        ]);

        // Parse the response from Google
        $tokenData = $response->json();

        if (isset($tokenData['access_token'])) {
            $accessToken = $tokenData['access_token'];
            $userResponse = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v1/userinfo?alt=json');

            $userData = $userResponse->json();

            if (isset($userData['id'])) {
                // $user_with_google = User::query()->where('google_id', $userData['id'])->first();
                $user = LoginUser::query()->where('email', $userData['email'])->first();
                if ($user) {
                    $user->update([
                        'first_name' => $userData['given_name'],
                        'last_name' => $userData['family_name'],
                        'google_id' => $userData['id'],
                        'email_verified_at' => now(),
                    ]);
                } else {
                    $user = LoginUser::create([

                        'first_name' => $userData['given_name'],
                        'last_name' => $userData['family_name'],
                        'email' => $userData['email'],
                        'email_verified_at' => now(),
                        'password' => Hash::make('password'),
                        'google_id' => $userData['id'],

                    ]);
                }


                $sanctumToken = $user->createToken('google-auth-token')->plainTextToken;

                return $this->success([
                    'user' => $user,
                    'token' => $sanctumToken,
                ]);
            } else {
                return $this->ErrorResponse( 'Failed to retrieve user data from Google', 400);
            }
        } else {
            return $this->ErrorResponse( 'Failed to obtain access token' ,  400);
        }
    }

}
