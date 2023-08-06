<?php

namespace App\Http\Interfaces;

use App\Models\Faq;
use Illuminate\Http\Request;

interface FaqInterface
{
    public const modelClass = Faq::class;

    public function index(Request $request);

    public function adminIndex(Request $request);

    public function show(Request $request, Faq $faq);

    public function create(Request $request);

    public function update(Request $request, Faq $faq);

    public function delete(Faq $faq);
}
