<?php

namespace App\Http\Interfaces;


use App\Models\Post;
use Illuminate\Http\Request;

interface PostInterface
{
    public const modelClass = Post::class;

    public function index(Request $request);

    public function adminIndex(Request $request);

    public function show(Request $request, Post $post);

    public function create(Request $request);

    public function update(Request $request, Post $post);

    public function delete(Post $post);
}
