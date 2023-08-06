<?php

namespace App\Http\Interfaces;

use App\Models\Banner;
use Illuminate\Http\Request;

interface BannerInterface
{
    public const modelClass = Banner::class;

    public function index(Request $request);

    public function adminIndex(Request $request);

    public function show(Request $request, Banner $banner);

    public function create(Request $request);

    public function update(Request $request, Banner $banner);

    public function delete(Banner $banner);
}
