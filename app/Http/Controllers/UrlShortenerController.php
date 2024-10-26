<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UrlShortenerController extends Controller
{
    public function shorten(Request $request)
    {
        // Validate URL input
        $validator = Validator::make($request->all(), [
            'original_url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid URL provided'], 422);
        }

        $originalUrl = $request->input('original_url');

        // Check if URL already exists
        $url = Url::where('original_url', $originalUrl)->first();
        if ($url) {
            return response()->json(['short_url' => url($url->short_code)]);
        }

        // Generate unique short code
        do {
            $shortCode = Str::random(6);
        } while (Url::where('short_code', $shortCode)->exists());

        // Store the URL in the database
        $url = Url::create([
            'original_url' => $originalUrl,
            'short_code' => $shortCode,
        ]);

        return response()->json(['short_url' => url($shortCode)], 201);
    }

    public function redirectToOriginal($code)
    {
        $url = Url::where('short_code', $code)->first();

        if (!$url) {
            return response()->json(['error' => 'Short URL does not exist'], 404);
        }

        return response()->json(['main_url' => $url->original_url], 201);
//        return redirect()->to($url->original_url);
    }
}
