<?php

namespace App\Providers;

use Elasticsearch\Client;
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
    }
}
