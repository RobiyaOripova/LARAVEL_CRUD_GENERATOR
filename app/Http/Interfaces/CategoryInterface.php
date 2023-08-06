<?php

namespace App\Http\Interfaces;

use App\Models\Category;
use Illuminate\Http\Request;

interface CategoryInterface
{
    public const modelClass = Category::class;

    public function index(Request $request);

    public function adminIndex(Request $request);

    public function show(Request $request, Category $category);

    public function create(Request $request);

    public function update(Request $request, Category $category);

    public function delete(Category $category);
}
