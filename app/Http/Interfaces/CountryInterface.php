<?php

namespace App\Http\Interfaces;

use App\Models\Country;
use Illuminate\Http\Request;

interface CountryInterface
{
    public const modelClass = Country::class;

    public function index(Request $request);

    public function adminIndex(Request $request);

    public function show(Request $request, Country $country);

    public function create(Request $request);

    public function update(Request $request, Country $country);

    public function delete(Country $country);
}
