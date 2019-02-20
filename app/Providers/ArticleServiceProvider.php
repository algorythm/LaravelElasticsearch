<?php

namespace App\Providers;

// use App\Repository;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use App\Repository\ArticlesRepository;
use Illuminate\Support\ServiceProvider;
use App\Repository\EloquentArticlesRepository;
use App\Repository\ElasticsearchArticlesRepository;

class ArticleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(
        //     \App\Repository\ArticlesRepository::class, 
        //     \App\Repository\EloquentArticlesRepository::class
        // );
        $this->app->singleton(ArticlesRepository::class, function($app) {
            // this is useful in case we want to turn off our
            // search cluster, or when deploying the search
            // to a live/running application at first.
            if (!config('services.search.enabled')) {
                return new EloquentArticlesRepository();
            }

            return new ElasticsearchArticlesRepository(
                $app->make(Client::class)
            );
        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function($app) {
            return ClientBuilder::create()
                ->setHosts(config('services.search.hosts'))
                ->build();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
