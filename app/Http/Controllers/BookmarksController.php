<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
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

    public function removeBookmark(Request $request){
    
        $user = JWTAuth::parseToken()->authenticate();

        $request->validate([
            'bookmarkable_type' => 'required|string',
            'bookmarkable_id' => 'required|integer',
        ]);

        $deleted = Bookmark::where('userable_id', $user->id)
            ->where('userable_type', get_class($user))
            ->where('bookmarkable_id', $request->bookmarkable_id)
            ->where('bookmarkable_type', $request->bookmarkable_type)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Bookmark removed successfully!'], 200);
        }

        return response()->json(['message' => 'Bookmark not found.'], 404);
    }

    public function getBookmarks(){
        $user = JWTAuth::parseToken()->authenticate();

        $bookmarkedBooks = Bookmark::where('userable_id', $user->id)->where('userable_type', get_class($user))->where('bookmarkable_type', 'App\\Models\\Book')->pluck('bookmarkable_id');

        $bookmarkedRepositories = Bookmark::where('userable_id', $user->id)
        ->where('userable_type', get_class($user))
        ->where('bookmarkable_type', 'App\\Models\\Repository')
        ->pluck('bookmarkable_id');

        return response()->json([
            'bookmarked_books' => $bookmarkedBooks,
            'bookmarked_repositories' => $bookmarkedRepositories,
        ]);
    
    }

    public function mostBookmarked(){
        
        $mostBookmarkedRepo = DB::table('bookmarks')
        ->select('bookmarkable_id', DB::raw('COUNT(*) as bookmark_count'))
        ->where('bookmarkable_type', 'App\\Models\\Repository')
        ->groupBy('bookmarkable_id')
        ->orderByDesc('bookmark_count')
        ->first();

        return response()->json([
            'repository' => $mostBookmarkedRepo
        ]);
    }
}
