<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComentarResource;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comment = Comments::with(['user'])->get();

        try {
            return response()->json([
                "status" => "Success",
                "massage" => "Berhasil mendapatkan semua data commentar",
                "data" =>  $comment->loadMissing(['user:id,name'])
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat mengambil semua data commentar"
            ], 500);
        }
    }


    public function store(Request $request)
    {

        $request->validate(
            [
                'content' => "string|required"
            ],
            [
                'content.required' => "Komentar harus bernilai string",
                'content.required' => "Komentar wajib di isi",
            ]
        );


        try {
            $data = $request->all();
            $data['user_id'] = Auth::user()->id;
            $comment = Comments::create($data);

            return response()->json([
                "status" => "Success",
                "massage" => "Berhasil membuat data postingan",
                "data" => $comment->loadMissing(['user:id,name'])
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat membuat commentar"
            ], 500);
        }
    }




    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => 'string',
                'content' => 'string',
                'image' => 'image|mimes:jpg,png,jpeg,gif,svg'
            ]
        );


        try {

            $comment = Comments::findOrFail($id);

            $data = $request->all();
            $data['created_by'] = Auth::user()->id;
            $comment->update($data);

            return response()->json([
                "status" => "Success",
                "massage" => "Berhasil mengedit data user",
                "data" => $comment->loadMissing(['user:id,name'])
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat update data commentar"
            ], 500);
        }
    }


    public function destroy($id)
    {
        $delete = Comments::findOrFail($id);
        $delete->delete();

        try {
            return response()->json([
                "status" => "Success",
                "massage" => "Data berhasil di hapus"
            ], 202);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat menghapus data commentar"
            ], 500);
        };
    }
}
