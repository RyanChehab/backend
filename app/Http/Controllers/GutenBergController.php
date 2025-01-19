<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GutenBergController extends Controller{
    
    public function fetchBookContent(Request $request){
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->input('url');

        try {
            
            $response = Http::get($url);

            if ($response->ok()) {
                // Filter content between START and END markers
                $content = $this->filterBookContent($response->body());

                return response()->json([
                    'success' => true,
                    'content' => $content,
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch book content from the provided URL.',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function filterBookContent($content)
    {
        // Extract the content between the START and END markers
        $startMarker = "*** START OF THE PROJECT GUTENBERG EBOOK";
        $endMarker = "*** END OF THE PROJECT GUTENBERG EBOOK";

        $startPos = strpos($content, $startMarker);
        $endPos = strpos($content, $endMarker);

        if ($startPos !== false && $endPos !== false) {
            return substr($content, $startPos + strlen($startMarker), $endPos - $startPos - strlen($startMarker));
        }

        return $content; // Return full content if markers are not found
    }
}
