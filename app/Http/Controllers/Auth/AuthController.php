<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\RegisterMail;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(LoginRequest $request)
    {
        try {
            $uemail = strtolower($request->email);
            $upass = $request->password;

            $user = User::whereEmail($uemail)->first();

            if (!$user) {
                return $this->response404("No user found with the provided email, Please make sure you have entered a valid email address");
            }
            if (Hash::check($upass, $user->password)) {
                $credentials = $request->only('email', 'password');
                $token = Auth::attempt($credentials);


                $response = [
                    'id' => $user->id,
                    'email' => $user->email,
                    'authorization' => [
                        'type' => 'bearer',
                        'token' => $token,
                    ],
                ];

                return $this->response200('Successfully logged in', $response);

            } else {
                return $this->response401('Failed to login. Password does not match.');
            }
        }
        catch (\Exception $exception)
        {
            return $this->response500($exception->getMessage());
        }
    }

    function randomPassword() {
        $digits    = array_flip(range('0', '9'));
        $lowercase = array_flip(range('a', 'z'));
        $uppercase = array_flip(range('A', 'Z'));
//        $special   = array_flip(str_split('!@#$%^&*()_+=-}{[}]\|;:<>?/'));
        $combined  = array_merge($digits, $lowercase, $uppercase);

        return str_shuffle(array_rand($digits) .
            array_rand($lowercase) .
            array_rand($uppercase) .
//            array_rand($special) .
            implode(array_rand($combined, rand(4, 8))));
    }

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $password = $this->randomPassword();

            $request->merge([
                'password' => Hash::make($password),
            ]);

            $user = User::query()->create($request->only((new User)->getFillable()));

            if (!$user) {
                DB::rollBack();
                return $this->response409("Failed to register your account, please try again.");
            }

            try {
                Mail::to($user->email)->send(new RegisterMail($password));
            }
            catch (\Exception $exception) {
                DB::rollBack();
                return $this->response409("Account creation failed due to mail failure.", $exception->getMessage());
            }

            DB::commit();
            return response()->json(
                [
                    'message' => 'User created successfully.',
                    'data' => $user,
                ]
            );
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            return $this->response500($exception->getMessage());
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out',
            ]);
        }
        catch (\Exception $exception)
        {
            return $this->response500($exception->getMessage());
        }
    }
}
