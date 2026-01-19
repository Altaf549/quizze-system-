<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class PageController extends Controller
{
    public function show($pageKey)
    {
        $content = Content::where('page_key', $pageKey)->firstOrFail();
        return view('pages.content', compact('content'));
    }

    public function aboutUs()
    {
        return $this->show('about-us');
    }

    public function privacyPolicy()
    {
        return $this->show('privacy-policy');
    }

    public function termsConditions()
    {
        return $this->show('terms-conditions');
    }
}
