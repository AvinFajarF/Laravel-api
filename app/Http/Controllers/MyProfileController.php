<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyProfileController extends Controller
{
    public function update(Request $request, $id)
    {


        // Validasi
        $request->validate(
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

            $data = $request->all();

            if ($request->file('images')) {
                $extension = $request->file('images')->getClientOriginalExtension();
                $newImagesName = $request->tanggal_lahir . '-' . now()->timestamp . '.' . $extension;

                $request->file('images')->storeAs('images', $newImagesName);
                $request->images->move(public_path('images'), $newImagesName);
                $data['images'] = $newImagesName;
            }


            $user = User::findOrFail($id);
            $update =  $user->update($data);


            return response()->json([
                "status" => "Success",
                "massage" => "Berhasil mengupdate profile",
                "data" => $user
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat update profile"
            ], 500);
        }
    }
}
