<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        try {
            $contents = Content::select('id', 'title', 'slug', 'image', 'description')
                ->withCount('resources')
                ->orderBy('order', 'asc')
                ->get();

            $contents->each(function ($content) {
                $content->image_url = $content->image ? asset('storage/' . $content->image) : asset('images/default-pillar.jpg');
            });

            return response()->json($contents);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve content list'
            ], 500);
        }
    }

    public function show($slug)
    {
        try {
            $content = Content::with(['resources.sourceCategory'])
                ->where('slug', $slug)
                ->first();

            if (!$content) {
                return response()->json([
                    'success' => false,
                    'message' => 'Content not found'
                ], 404);
            }

            $content->image_url = $content->image ? asset('storage/' . $content->image) : asset('images/default-pillar.jpg');

            return response()->json($content);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve content details'
            ], 500);
        }
    }

    public function showPillar($slug)
    {
        $content = Content::where('slug', $slug)->first();

        if (!$content) {
            abort(404);
        }

        return view('pages.pillar', [
            'title' => $content->title . ' - Smart City Mini Library',
            'slug' => $slug,
            'content' => $content
        ]);
    }
}
