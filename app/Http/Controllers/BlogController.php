<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use App\Models\Like;

class BlogController extends Controller
{
    // BLOG-CREATE-API
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        $blog = Blog::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        // Generate full image URL if image exists
        $imageUrl = $imagePath ? asset('storage/' . $imagePath) : null;

        return response()->json([
            'message' => 'Blog created successfully',
            'blog' => [
                'id' => $blog->id,
                'user_id' => $blog->user_id,
                'title' => $blog->title,
                'description' => $blog->description,
                'image' => $imageUrl,
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at,
            ],
        ], 201);
    }

    // BLOG-LIST-API
    public function index(Request $request)
    {
        $query = Blog::withCount('likes')->with('user');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        // Filter: most liked
        if ($request->input('sort') === 'most_liked') {
            $query->orderByDesc('likes_count');
        }
        // Filter: latest
        else {
            $query->orderByDesc('created_at');
        }

        $blogs = $query->paginate($request->input('per_page') ?? 10);

        // Add info: is_liked_by_user
        $user = Auth::user();
        $blogs->getCollection()->transform(function($blog) use ($user) {
            $blog->is_liked_by_user = $blog->likes()->where('user_id', $user->id)->exists();
            return $blog;
        });

        return response()->json($blogs);
    }

    // BLOG-EDIT-API
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $blog->title = $request->title;
        $blog->description = $request->description;

        if ($request->hasFile('image')) {
            $blog->image = $request->file('image')->store('blogs', 'public');
        }

        $blog->save();

        $imageUrl = $blog->image ? asset('storage/' . $blog->image) : null;

        $blog->image = $imageUrl;
        return response()->json([
            'message' => 'Blog updated successfully',
            'blog' => $blog,
        ]);
    }

    // BLOG-DELETE-API
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $blog->likes()->delete(); 
        $blog->delete();
        
        return response()->json(['message' => 'Blog deleted successfully']);
    }

    // BLOG-LIKE-TOGGLE
    public function like($id)
    {
        $blog = Blog::findOrFail($id);
        $user = Auth::user();

        $like = $blog->likes()->where('user_id', $user->id)->first();
        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $blog->likes()->create([
                'user_id' => $user->id,
            ]);
            $liked = true;
        }

        return response()->json([
            'message' => 'Blog liked successfully',
            'likes_count' => $blog->likes()->count(),
        ]);
    }

    // BLOG-UNLIKE-TOGGLE
    public function unlike($id)
    {
        $blog = Blog::findOrFail($id);
        $user = Auth::user();

        $like = $blog->likes()->where('user_id', $user->id)->first();
        if ($like) {
            $like->delete();
            $liked = false;
        }

        return response()->json([
            'message' => 'Blog unliked successfully',
            'likes_count' => $blog->likes()->count(),   
        ]);
    }       

    public function latest()
    {
        $blog = Blog::latest()->first();
        if ($blog) {
            return response()->json($blog);
        } else {
            return response()->json(['message' => 'No blog found'], 404);
        }
    }
}
