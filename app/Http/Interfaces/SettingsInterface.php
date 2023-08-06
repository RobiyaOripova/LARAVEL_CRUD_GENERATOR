<?php

namespace App\Http\Interfaces;


use App\Models\Settings;
use Illuminate\Http\Request;

interface SettingsInterface
{
    public const modelClass = Settings::class;

    public function index(Request $request);

    public function adminIndex(Request $request);

    public function show(Request $request, Settings $settings);

    public function create(Request $request);

    public function update(Request $request, Settings $settings);

    public function delete(Settings $settings);
}
