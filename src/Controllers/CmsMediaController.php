<?php

namespace Hellotreedigital\Cms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class CmsMediaController extends Controller
{
    public function showMedia(Request $request)
    {
        $search = $request->input('custom_search');
        $files = Storage::disk('public')->allFiles();
    
        $mediaItems = [];
    
        foreach ($files as $file) {
            // Exclude hidden files
            if (strpos(basename($file), '.') !== 0) {
                $mediaItems[] = [
                    'file_name'   => basename($file),
                    'file_path'   => $file,
                    'folder_name' => dirname($file),
                    'size'        => Storage::disk('public')->size($file),
                    'updated_at'  => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
                ];
            }
        }
    
        // Filter by search
        if ($search) {
            $mediaItems = array_filter($mediaItems, function ($item) use ($search) {
                $search = strtolower($search);
                return str_contains(strtolower($item['file_name']), $search) ||
                       str_contains(strtolower($item['folder_name']), $search) ||
                       str_contains(strtolower($item['file_path']), $search);
            });
        }
    
        // Sort by latest updated
        $sortBy = $request->input('sort_by', 'updated_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        usort($mediaItems, function ($a, $b) use ($sortBy, $sortOrder) {
            $valA = $a[$sortBy] ?? null;
            $valB = $b[$sortBy] ?? null;
        
            // Handle date sorting
            if ($sortBy === 'updated_at') {
                $valA = strtotime($valA);
                $valB = strtotime($valB);
            }
        
            if ($valA == $valB) return 0;
        
            return ($sortOrder === 'asc' ? 1 : -1) * (($valA < $valB) ? -1 : 1);
        });
            
        // Paginate
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($mediaItems);
        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();
    
        $paginatedItems = new LengthAwarePaginator(
            $currentItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        return view('cms::pages/cms-media/index', [
            'mediaItems' => $paginatedItems,
            'search' => $search,
        ]);
    }

    public function destroy(Request $request)
    {
        $path = $request->delete_path;
        
        if (Storage::disk('public')->exists($path)) {
            // File exists
            Storage::disk('public')->delete($path);
        }
        
        return redirect()->back()->with('success', 'Image Delted successfully.');
    }
    
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf,xlsx,xls,doc,docx,mov,mp4|max:10240', // max 10MB
        ]);
    
        $file = $request->file('file');
        $path = $file->store('/', 'public'); // Store in storage/app/public/
    
        return 1;
    }
}