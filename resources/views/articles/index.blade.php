<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Articles</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div class="container" style="margin: 48px auto 48px auto;">
        <div class="row">
            <div class="card">
                <h2 class="card-header">
                    Articles <span class="badge badge-pill badge-primary">{{ $articles->count() }}</span>
                </h2>
                <form class="card-body" action="{{ url('search') }}" method="GET" >
                    <div class="form-group">
                        <label for="searchField">Search for an article</label>
                        <input type="search" name="q" id="searchField" class="form-control" aria-describedby="searchHelp" placeholder="Enter your search here" value="{{ request('q') }}">
                        <small id="searchHelp" class="form-text text-muted">Searching through all of the articles in our system.</small>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
                <hr />
                <div class="card-body">
                    @forelse ($articles as $article)
                        <div class="card" style="margin-bottom: 12px">
                            <div class="card-body">
                                <h5 class="card-title">{{ $article->title }}</h5>
                                <p class="card-text">{{ $article->body }}</p>
                                @forelse ($article->tags as $tag)
                                    <span class="badge badge-secondary">{{ $tag }}</span>
                                @empty
                                    <small class="text-muted">No tags</small>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <p class="well">No articles found</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>