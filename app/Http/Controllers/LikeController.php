<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Like;

class LikeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }


    public function index() {
        $user = \Auth::user();
        $likes = Like::where('user_id', $user->id)
                        ->orderBy('id', 'desc')
                        ->paginate(4);

        return view('like.index', [
            'likes' => $likes
        ]);
    }


    public function like($image_id) {
        $user = \Auth::user();
        $like = new Like();

        $isset_like = Like::where('user_id', $user->id)
                            ->where('image_id', $image_id)
                            ->count();

        if ($isset_like == 0) {
            $like->user_id = $user->id;
            $like->image_id = (int) $image_id;
            $like->save();

            return response() -> json(['like' => $like]);
        }
        else {
            return response() -> json(['like' => 'Like already exits']);
        }
    }


    public function dislike($image_id) {
        $user = \Auth::user();
        $like = new Like();

        $like = Like::where('user_id', $user->id)
                            ->where('image_id', $image_id)
                            ->first();

        if ($like) {
            $like->delete();

            return response() -> json(['like' => $like]);
        }
        else {
            return response() -> json(['like' => 'Like doesn']);
        }
    }
}
