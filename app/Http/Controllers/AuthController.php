<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as RulesPassword;

class AuthController extends Controller
{

    // untuk register
    public function register(User $user, Request $request)
    {
        // Validasi
        $request->validate(
            [
                'name' => 'string|required',
                'email' => 'email|required',
                'password' => 'required',
                'tanggal_lahir' => 'required',
                'jenis_kelamin' => 'required',
                'alamat' => 'required',
                'images' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ],
            [
                'name.string' => "Nama harus bernilai string",
                'name.required' => "Nama wajib di isi",
                'email.required' => 'Email wajib di isi',
                'email.email' => 'Format email yang anda isikan salah, example@yahoo.com',
                'password.required' => 'Password wajib di isi',
                'tanggal_lahir.required' => "Tanggal Lahir wajib di isi",
                'jenis_kelamin.required' => "Jenis Kelamin wajib di isi",
                'alamat.required' => "Alamat wajib di isi",
                'images.mimes' => "Masukan extension gambar yang benar (jpeg,png,jpg,gif,svg)"
            ]
        );


        try {
            // create user
            $data = $request->all();
            $data["password"] = Hash::make($data["password"]);
            if ($request->file('images')) {
                $extension = $request->file('images')->getClientOriginalExtension();
                $newImagesName = $request->tanggal_lahir . '-' . now()->timestamp . '.' . $extension;

                $request->file('images')->storeAs('images', $newImagesName);
                $request->images->move(public_path('images'), $newImagesName);
                $data['images'] = $newImagesName;
            }
            $user =  $user::create($data);

            // response json
            return response()->json([
                'status' => "success",
                "massage" => "Berhasil membuat account",
                "data" => $data
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                'status' => "error",
                "massage" => "Terjadi kesalahan pada saat register, silahkan cek ulang atau email sudah di gunakan"
            ], 500);
        }
    }



    // untuk login
    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            [
                'email.required' => 'Email wajib di isi',
                'email.email' => 'Format email yang anda isikan salah, example@yahoo.com',
                'password.required' => "Password wajib di isi   "
            ]
        );


        try {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Kredensial yang diberikan salah.'],
                ]);
            }
            $token = $user->createToken($request->email)->plainTextToken;
            return response()->json([
                'status' => "success",
                "massage" => "Anda berhasil login",
                "token" => $token
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                'status' => "error",
                "massage" => "Email atau password yang anda berikan salah"
            ], 401);
        }
    }


    public function logout(Request $request)
    {

        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => "success",
                "massage" => "Berhasil Logout"
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                'status' => "error",
                "massage" => "Gagal logout"
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {

        $request->validate([
            'email' => "required|email"
        ], [
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Format email yang anda isikan salah, example@yahoo.com',
        ]);

        try {
            Password::sendResetLink(
                $request->only('email')
            );

            return response()->json([
                'status' => 'Success',
                'message' => 'Link reset password berhasil di kirim',
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'Error',
                'message' => 'Error pada saat melakukan reset password'
            ]);
        }
    }



    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message'=> 'Password reset successfully'
            ]);
        }

        return response([
            'message'=> __($status)
        ], 500);

    }





}
