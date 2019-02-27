<?php

namespace App\Jobs;

use App\Article;
use Goutte\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ScrapeDevrant implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    // private $client;
    private $rant_url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($rant_url)
    {
        // $this->client = new Client();

        // $guzzleClient = new \GuzzleHttp\Client([
        //     'timeout' => 60,
        //     'verify' => false,
        // ]);

        // $this->client->setClient($guzzleClient);
        $this->rant_url = $rant_url;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();

        $guzzleClient = new \GuzzleHttp\Client([
            'timeout' => 60,
            'verify' => false,
        ]);

        $client->setClient($guzzleClient);
        $crawler = $client->request("GET", $this->rant_url);

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

        sleep(30);

        Article::create([
            'title' => $articleTitle,
            'body' => $articleBody,
            'tags' => $tags,
        ]);
    }
}
