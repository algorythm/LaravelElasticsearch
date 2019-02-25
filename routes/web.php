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
Route::post("/create/url", "ArticleController@create_from_url");

Route::get("/test", function() {
    $crawler = Goutte::request("GET", "https://www.dr.dk/nyheder/politik/klaus-riskaer-fyrede-kandidat-efter-dage-en-soed-pige-der-ikke-kan-klare");

    $articleTitle = ($crawler->filter(".dre-standard-article article header h1 span"))->each(function($node) {
        echo($node->text());
    });
    echo("test");
    dd($articleTitle);
});
