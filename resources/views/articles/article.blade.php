<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $article->title }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div class="container" style="margin: 48px auto 48px auto;">
        <div class="row">
            <div class="card" style="margin-bottom: 12px; width: 100%;">
                <h1 class="card-header">
                    {{ $article->title }}
                    {{-- <div class="float-right"> --}}
                        <a role="button" href="/create?url={{ Request::get('url') }}" class="btn btn-secondary float-right">Back</a>
                        {{-- </div> --}}
                    </h1>
                    <div class="card-body">
                        @forelse ($article->tags as $tag)
                        <span class="badge badge-secondary">{{ $tag }}</span>
                        @empty
                        <small class="text-muted">No tags</small>
                        @endforelse
                        <hr>
                        <p class="card-text">{{ $article->body }}</p>
                    </div>
                    @if(Request::has('url'))
                    <hr>
                    <div class="card-body">
                        <small class="text-muted">{{ Request::get('url') }}</small>
                    <form action="/create/url" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-primary float-right">Create</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>