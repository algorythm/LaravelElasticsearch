<?php

use App\Repository\ArticlesRepository;

Route::get('/', function() {
    return view('articles.index', [
        'articles' => App\Article::all(),
    ]);
});

Route::get('/search', function(ArticlesRepository $repository) {
    $query = (string) request('q');

    if ($query == "")
    {
        return redirect("/");
    }

    $articles = $repository->search((string) request('q'));

    return view('articles.index', [
        'articles' => $articles,
    ]);
});

Route::get('/create', function() {
    return view('articles.create');
});

Route::post("/create", 'ArticleController@create');
