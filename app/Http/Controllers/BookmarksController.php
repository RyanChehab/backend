<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\Book;

class BookmarksController extends Controller{

    public function bookmark(Request $request){
        $request->validate([
            'bookmarkable_type'=>'required',
            'bookmarkable_id' => 'required|integer'
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        $exists = Bookmark::where('userable_id', $user->id)
            ->where('userable_type', get_class($user))
            ->where('bookmarkable_id', $request->bookmarkable_id)
            ->where('bookmarkable_type', $request->bookmarkable_type)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Bookmark already exists!'], 400);
        }

        $bookmark = Bookmark::create([
            'userable_id' => $user->id,
            'userable_type' => get_class($user),
            'bookmarkable_id' => $request->bookmarkable_id,
            'bookmarkable_type' => $request->bookmarkable_type,
        ]);

        return response()->json(['message' => 'Bookmark added successfully!', 'bookmark' => $bookmark], 201);
    }

    public function getUserBookmarks(){
        $user = JWTAuth::parseToken()->authenticate();

        // Fetch all bookmarks for the user
        $bookmarks = Bookmark::where('user_id', $user->id)->get();

        return response()->json($bookmarks, 200);
    }
}