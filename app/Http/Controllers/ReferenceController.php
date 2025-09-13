<?php

namespace App\Http\Controllers;

use App\Models\Resources;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    public function index()
    {
        try {
            $resources = Resources::with(['content:id,title', 'sourceCategory:id,name'])
                ->select('id', 'content_id', 'title', 'author', 'year', 'source_category_id', 'link')
                ->orderBy('content_id')
                ->orderBy('title')
                ->get();

            return response()->json($resources);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reference data'
            ], 500);
        }
    }

    public function byContent($content_slug)
    {
        try {
            $resources = Resources::with(['content:id,title,slug', 'sourceCategory:id,name'])
                ->whereHas('content', function ($query) use ($content_slug) {
                    $query->where('slug', $content_slug);
                })
                ->select('id', 'content_id', 'title', 'author', 'year', 'source_category_id', 'link')
                ->orderBy('title')
                ->get();

            return response()->json($resources);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reference data'
            ], 500);
        }
    }

    public function showReferences()
    {
        return view('pages.references', [
            'title' => 'All References - Smart City Mini Library',
            'slug' => 'references'
        ]);
    }
}
