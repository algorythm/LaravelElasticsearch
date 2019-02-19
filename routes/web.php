<?php

use App\Repository\ArticlesRepository;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function() {
    return view('articles.index', [
        'articles' => App\Article::all(),
    ]);
});

Route::get('/search', function(ArticlesRepository $repository) {
    $articles = $repository->search((string) request('q'));

    return view('articles.index', [
        'articles' => $articles,
    ]);
});
