<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $user = User::all();
        $user = User::paginate(10);


        try {
            return response()->json([
                "status" => "Success",
                "massage" => "Berhasil mendapatkan semua data User",
                "data" =>  UserResource::collection($user)
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat mengambil semua data User"
            ], 500);
        }
    }




    public function store(Request $request, User $user)
    {

        // Validasi
        $validasi =   $request->validate(
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
                'name.string' => "Name harus bernilai string",
                'name.required' => "Name wajib di isi",
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
            $data = $validasi;
            $data["password"] = Hash::make($data["password"]);
            $user =  $user::create($data);

            // response json

            return response()->json([
                "status" => "Success",
                "massage" => "Berhasil membuat data postingan",
                "data" => new UserResource($user)
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat membuat data user"
            ], 500);
        }
    }





    public function edit(Request $request,  $id)
    {
        // Validasi
        $validasi =  $request->validate(
            [
                'name' => 'string',
                'email' => 'email',
                'images' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ],
            [
                'name.string' => "Nama harus bernilai string",
                'email.email' => 'Format email yang anda isikan salah, example@yahoo.com',
                'alamat' => "Alamat wajib di isi",
                'images.mimes' => "Masukan extension gambar yang benar (jpeg,png,jpg,gif,svg)"
            ]
        );



        try {

            $data = $validasi;

            if ($request->file('images')) {
                $extension = $request->file('images')->getClientOriginalExtension();
                $newImagesName = $request->tanggal_lahir . '-' . now()->timestamp . '.' . $extension;

                $request->file('images')->storeAs('images', $newImagesName);
                $request->images->move(public_path('images'), $newImagesName);
                $data['images'] = $newImagesName;
            }


            $user = User::findOrFail($id);
            $user->update($data);


            return response()->json([
                "status" => "Success",
                "massage" => "Berhasil mengedit data user",
                "data" => new UserResource($user)
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat update data user"
            ], 500);
        }
    }


    public function destroy($id)
    {
        $delete = User::findOrFail($id);

        try {
            $delete->delete();

            return response()->json([
                "status" => "Success",
                "massage" => "Data berhasil di hapus"
            ], 202);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat menghapus data user"
            ], 500);
        };
    }
}
