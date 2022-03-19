<?php

namespace App\Http\Controllers;

use App\Events\UserRegisterEvent;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->customResponse($validator->getMessageBag()->all(), 400);
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->customResponse('Invalid credentials', 400);
        };

        $user = auth()->user();
        $token = $user->createToken(auth()->user()->id)->plainTextToken;
        return $this->customResponse([
            'displayname' => auth()->user()->displayname,
            'token' => $token,
            'type' => $user->role?->slug,
            'emailVerified' => auth()->user()->hasVerifiedEmail()
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | min:5 | max: 255',
            'email' => 'required | email | max:255 | unique:users,email',
            'displayname' => 'required | min:5 | max:15 | unique:users,displayname',
            'password' => 'required | min:8 | max:255 | confirmed'
        ]);

        if ($validator->fails()) {
            return $this->customResponse($validator->getMessageBag()->all(), 400);
        }

        $fields = $validator->validated();
        $fields['password'] = bcrypt($fields['password']);
        $fields['role_id'] = 2;
        $fields['verification_code'] = Str::random(10);
        $user = User::create($fields);

        UserRegisterEvent::dispatch($user);

        return new UserResource($user);
    }

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->customResponse($validator->getMessageBag()->all(), 400);
        }

        $user = auth()->user();
        if ($request->input('code') !== $user->verification_code) {
            return $this->customResponse(['Invalid code'], 400);
        }

        $user->email_verified_at = date("Y:m:d H:i:s");
        $user->save();
    }

    public function resendVerifyEmail()
    {
        $user = auth()->user();

        if (!$user->hasVerifiedEmail()) {
            UserRegisterEvent::dispatch($user);
            return $this->customResponse('The email has been successfully resent', 200);
        }

        return $this->customResponse('Your email has already been verified', 400);
    }
}
