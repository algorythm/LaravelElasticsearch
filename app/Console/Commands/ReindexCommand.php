<?php

namespace App\Console\Commands;

use App\Article;
use Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all models to elasticsearch';

    private $search;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $search)
    {
        parent::__construct();
        $this->search = $search;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Indexing all articles. Might take a while...');

        foreach (Article::cursor() as $model)
        {
            $this->search->index([
                'index' => $model->getSearchIndex(),
                'type' => $model->getSearchType(),
                'id' => $model->id,
                'body' => $model->toSearchArray(),
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("nDone!");
    }
}
