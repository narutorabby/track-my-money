<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        try {
            $token = $request->token;
            $userInfo = Socialite::driver('google')->stateless()->userFromToken($token);
            if($userInfo == null) {
                return errorResponse("Could not find this google account");
            }

            $user = $this->findOrCreateUser($userInfo);
            if($user == null) {
                return errorResponse("Google signin failed. Try again!");
            }
            $token = $user->createToken('Personal Token')->plainTextToken;
            $user->token = $token;
            return successResponse("Signed in successfully", $user);
        } catch (Exception $e) {
            Log::info("Error", ['err' => $e]);
            return errorResponse("Could not signin with Google");
        }
    }

    private function findOrCreateUser($userInfo)
    {
        DB::beginTransaction();
        try {
            $user = null;
            $googleUser = User::where('email', $userInfo->email)->where('google_id', $userInfo->id)->first();

            if($googleUser){
                $user = $googleUser;
            }
            else{
                $user = new User();
                $user->name = $userInfo->name ?? explode("@", $userInfo->email)[0];
                $user->email = $userInfo->email;
                $user->google_id = $userInfo->id;
                $user->save();
            }
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function profile(Request $request)
    {
        return successResponse("User profile", $request->user());
    }

    public function update(Request $request)
    {
        try {
            $user = User::where('id', $request->user()->id)->first();
            $user->name = $request->name;
            $user->mobile = $request->mobile;
            $user->avatar = $request->avatar;
            $user->save();
            return successResponse("Profile updated successfully", $user);
        } catch (Exception $e) {
            return errorResponse("Could not update profile");
        }
    }
}
