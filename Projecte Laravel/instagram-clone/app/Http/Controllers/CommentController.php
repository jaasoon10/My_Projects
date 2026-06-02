<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Image $image)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:500'],
        ]);

        Comment::create([
            'user_id'  => Auth::id(),
            'image_id' => $image->id,
            'content'  => $request->content,
        ]);

        return redirect()->route('images.show', $image)->with('success', 'Comentari afegit!');
    }

    public function edit(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'content' => ['required', 'string', 'max:500'],
        ]);

        $comment->update(['content' => $request->content]);

        return redirect()->route('images.show', $comment->image_id)->with('success', 'Comentari actualitzat!');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $imageId = $comment->image_id;
        $comment->delete();

        return redirect()->route('images.show', $imageId)->with('success', 'Comentari eliminat!');
    }
}
