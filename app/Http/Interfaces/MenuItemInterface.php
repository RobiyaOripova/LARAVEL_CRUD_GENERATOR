<?php

namespace App\Http\Interfaces;


use App\Models\MenuItem;
use Illuminate\Http\Request;

interface MenuItemInterface
{
    public const modelClass = MenuItem::class;

    public function index(Request $request);

    public function adminIndex(Request $request);

    public function show(Request $request, MenuItem $menuItem);

    public function create(Request $request);

    public function update(Request $request, MenuItem $menuItem);

    public function delete(MenuItem $menuItem);
}
