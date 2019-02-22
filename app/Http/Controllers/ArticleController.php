<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Repository\ArticlesRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class ArticleController extends Controller
{
    private $repository;

    public function __construct(ArticlesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'title' => 'required',
            'body' => 'required|min:50'
        ]);

        $tags = explode(",", $req->tags);

        if (!$validator->fails())
        {
            Article::create([
                'title' => $req->title,
                'body' => $req->body,
                'tags' => $tags,
            ]);
        }

        return redirect('/');
    }

    public function create_from_url(Request $req)
    {
        if ($req->has("preview"))
        {
            return view("articles.article", [
                'article' => $this->scrapeArticle($req->url),
            ]);
        }
        else
        {
            Article::create($this->scrapeArticle($req->url));
        }
    }

    private function scrapeArticle($url)
    {
        return new Article([
            'title' => "This is a test",
            'body' => "A small body for a small test.",
            'tags' => ["tag1", "tag2"]
        ]);
    }
}
