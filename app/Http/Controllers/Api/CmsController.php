<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $slug): JsonResponse
    {
        $page = CmsPage::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (! $page) {
            return response()->json([
                'message' => 'Page not found.',
            ], 404);
        }

        return response()->json([
            'slug' => $page->slug,
            'title' => $page->title,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'html_content' => $page->html_content,
            'custom_css' => $page->custom_css,
            'custom_js' => $page->custom_js,
        ]);
    }
}
