<?php

namespace App\Repository;

use App\Article;
use Illuminate\Database\Eloquent\Collection;

interface ArticlesRepository
{
    public function search(string $query = ""): Collection;
}
