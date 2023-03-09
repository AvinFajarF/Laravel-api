<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComentarResource;
use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Comments;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::with(['created_by:name', 'comments'])->get();
        $post = Post::paginate(10);

        try {
            return response()->json([
                "status" => "Success",
                "massage" => "Berhasil mendapatkan semua data",
                "data" => PostDetailResource::collection($post)
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat mengambil semua data postingan"
            ], 500);
        }
    }


    public function store(Request $request, Post $post)
    {

        // Validasi
        $validasi =  $request->validate(
            [
                'title' => 'string|required',
                'content' => 'string|required',
                'images' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ],
            [
                'title.string' => "Title harus bernilai string",
                'title.required' => "Title wajib di isi",
                'content.string' => "Content harus bernilai string",
                'content.required' => 'Content wajib di isi',
                'images.mimes' => "Masukan extension gambar yang benar (jpeg,png,jpg,gif,svg)"
            ]
        );


        try {
            // create user
            $data = $validasi;
            $data['created_by'] = Auth::user()->id;

            if ($request->file('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $newImagesName = Auth::user()->name . '--' . now()->timestamp . '.' . $extension;

                $request->file('image')->storeAs('images', $newImagesName);
                $request->image->move(public_path('images'), $newImagesName);
                $data['image'] = $newImagesName;
            }

            $post =  $post::create($data);
            // response json
            return response()->json([
                "status" => "Success",
                "massage" => "Berhasil membuat data postingan",
                "data" => new PostResource($post->loadMissing('created_by:id,name'))
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat membuat data postingan"
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $validasi =  $request->validate(
            [
                'title' => 'string',
                'content' => 'string',
                'image' => 'image|mimes:jpg,png,jpeg,gif,svg'
            ]
        );


        try {
            $post = Post::findOrFail($id);

            $data = $validasi;
            $data['created_by'] = Auth::user()->id;

            if ($request->file('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $newImagesName = Auth::user()->name . '-' . now()->timestamp . '.' . $extension;

                $request->file('image')->storeAs('images', $newImagesName);
                $request->image->move(public_path('images'), $newImagesName);
                $data['image'] = $newImagesName;
            } else {
                $data['image'] = $post->image;
            }



            $post->update($data);

            return response()->json([
                "status" => "Success",
                "massage" => "Berhasil membuat data postingan",
                "data" => new PostResource($post->loadMissing('created_by'))
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat update data postingan"
            ], 500);
        }
    }


    public function destroy($id)
    {
        $delete = Post::findOrFail($id);
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
                "massage" => "Error pada saat menghapus data postingan"
            ], 500);
        };
    }

    public function show(Post $post, $id)
    {
        try {
            $view = Post::with("comments:user_id,post_id,content")->findOrFail($id);
            $view->views++;
            $view->save();
            return response()->json([
                "status" => "Success",
                "massage" => "Berhasil melihat detail data postingan",
                "data" => new PostDetailResource($view->loadMissing("comments")),
            ], 200);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                "status" => "error",
                "massage" => "Error pada saat melihat detail data postingan"
            ], 500);
        }
    }
}
