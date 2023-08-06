<?php

namespace App\Http\Interfaces;


use App\Models\Menu;
use Illuminate\Http\Request;

interface MenuInterface
{
    public const modelClass = Menu::class;

    public function index(Request $request);

    public function adminIndex(Request $request);

    public function show(Request $request, Menu $menu);

    public function create(Request $request);

    public function update(Request $request, Menu $menu);

    public function delete(Menu $menu);
}
