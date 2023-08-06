<?php

namespace App\Http\Interfaces;


use App\Models\Page;
use Illuminate\Http\Request;

interface PageInterface
{
    public const modelClass = Page::class;

    public function index(Request $request);

    public function adminIndex(Request $request);

    public function show(Request $request, Page $page);

    public function create(Request $request);

    public function update(Request $request, Page $page);

    public function delete(Page $page);
}
