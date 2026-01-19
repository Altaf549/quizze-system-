<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contents = Content::all();
        return view('admin.contents.index', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.contents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'page_key' => 'required|string|unique:contents,page_key',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Content::create($request->all());

        return redirect()->route('contents.index')
            ->with('success', 'Content created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $content = Content::findOrFail($id);
        return view('admin.contents.show', compact('content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $content = Content::findOrFail($id);
        return view('admin.contents.edit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $content = Content::findOrFail($id);

        $request->validate([
            'page_key' => 'required|string|unique:contents,page_key,' . $id,
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $content->update($request->all());

        return redirect()->route('contents.index')
            ->with('success', 'Content updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $content = Content::findOrFail($id);
        $content->delete();

        return redirect()->route('contents.index')
            ->with('success', 'Content deleted successfully.');
    }
}
