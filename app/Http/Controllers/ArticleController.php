<?php

namespace App\Http\Controllers;

use App\Article;
use Goutte\Client;
use Illuminate\Http\Request;
use App\Repository\ArticlesRepository;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

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
        if ($req->has("preview") && !$req->has("article"))
        {
            return view("articles.article", [
                'article' => $this->scrapeArticle($req->url),
            ]);
        }
        else
        {
            $article = $this->scrapeArticle($req->url)->save();
            return redirect("/");
        }
    }

    private function scrapeArticle($url)
    {
        $client = new Client();

        $guzzleClient = new \GuzzleHttp\Client([
            'timeout' => 60,
            'verify' => false,
        ]);

        $client->setClient($guzzleClient);
        $crawler = $client->request("GET", $url);

        $crawler->filter(".dre-stasndard-article article header h1 span")->each(function($node) {
            $stuff += $node;
        });

        $tags = [];

        $rantUser = $crawler->filter(".rant-top-info .rant-username")->extract("_text")[0];
        $rantUpvotes = $crawler->filter(".post-details-text .votecount")->extract("_text")[0];
        $articleTitle = "$rantUser ($rantUpvotes upvotes)";

        $articleBody = $crawler->filter(".post-details-text .rantlist-content")->each(function($node) {
            return $node->html();
        })[0];

        $articleImage = $crawler->filter(".post-details-text .rant-image.details-image")->each(function($node) {
            return $node->html();
        });

        
        if (sizeof($articleImage) > 0)
        {
            $articleBody = "$articleBody<br/>$articleImage[0]";
        }

        foreach ($crawler->filter(".post-details-text .rantlist-tags h2.rantlist-content-tag")->extract("_text") as $tag)
        {
            array_push($tags, $tag);
        }

        return new Article([
            'title' => $articleTitle,
            'body' => $articleBody,
            'tags' => $tags,
        ]);
    }
}
