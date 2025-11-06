<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ActiveStatus;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends BaseController
{
    public function index()
    {
        $articles = Article::where('publish', ActiveStatus::ACTIVE)->paginate(10);

        return view('fe.article.index', compact('articles'));
    }

    public function show($uuid)
    {
        $getListAnotherArticles = Article::where('uuid', '<>', $uuid)->where('publish', ActiveStatus::ACTIVE)->orderBy('updated_at', 'desc')->limit(5)->get();
        $articleDetail = Article::where([
            'uuid' => $uuid,
            'publish' => ActiveStatus::ACTIVE
        ])->first();

        return view('fe.article.detail', compact('articleDetail', 'getListAnotherArticles'));
    }
}
