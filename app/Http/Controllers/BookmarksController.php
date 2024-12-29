<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\Book;

class BookmarksController extends Controller{

    public function toggleBookmark(Request $request)
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

        $existingBookmark = Bookmark::where('user_id', $user->id)
            ->where('bookmarkable_id', $request->bookmarkable_id)
            ->where('bookmarkable_type', $request->bookmarkable_type)
            ->first();
            
        if ($bookmark) {
            // If it exists, remove it
            $bookmark->delete();
            return response()->json(['message' => 'Bookmark removed', 'status' => false], 200);
        } else {
            // If it doesn't exist, add it
            Bookmark::create

    }
}
