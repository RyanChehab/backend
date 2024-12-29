<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\Book;

class BookmarksController extends Controller{
    public function addBookmark(Request $request)
    {
        $request->validate([
            'bookmarkable_id' => 'required|integer',
            'bookmarkable_type' => 'required|string',
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated',
            ], 401);
        }
    }
}
