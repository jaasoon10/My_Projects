<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function show(Image $image)
    {
        $image->load([
            'user',
            'likes',
            'comments' => fn($q) => $q->with('user')->orderBy('created_at', 'asc')
        ]);

        return view('images.show', compact('image'));
    }

    public function create()
    {
        return view('images.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_path'  => ['required', 'image', 'max:4096'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $path = $request->file('image_path')->store('images', 'public');

        Image::create([
            'user_id'     => Auth::id(),
            'image_path'  => $path,
            'description' => $request->description,
        ]);

        return redirect()->route('home')->with('success', 'Imatge publicada!');
    }

    public function edit(Image $image)
    {
        // Solo el propietario puede editar
        if ($image->user_id !== Auth::id()) {
            abort(403);
        }

        return view('images.edit', compact('image'));
    }

    public function update(Request $request, Image $image)
    {
        if ($image->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'image_path'  => ['nullable', 'image', 'max:4096'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        if ($request->hasFile('image_path')) {
            // Eliminar imagen anterior
            Storage::disk('public')->delete($image->image_path);
            $image->image_path = $request->file('image_path')->store('images', 'public');
        }

        $image->description = $request->description;
        $image->save();

        return redirect()->route('images.show', $image)->with('success', 'Imatge actualitzada!');
    }

    public function destroy(Image $image)
    {
        if ($image->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return redirect()->route('home')->with('success', 'Imatge eliminada!');
    }
}
