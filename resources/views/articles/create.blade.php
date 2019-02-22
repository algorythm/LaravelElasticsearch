<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Article</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        h6.line {
            position: relative;
            margin-top: 20px;
        }
        h6.line:before {
            content: "";
            display: block;
            border-top: solid 1px rgba(0,0,0,.125);
            width: 100%;
            height: 1px;
            position: absolute;
            top: 50%;
            z-index: 1;
        }
        h6.line span {
            background: #fff;
            position: relative;
            left: 50%;
            z-index: 5;
            padding: 0 20px;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="card" style="margin: 48px auto 48px auto; width:100%;">
                <h2 class="card-header">
                    Create a new Article
                    <a href="/" role="button" class="btn btn-primary float-right">Go Back</a>
                </h2>
                <div class="card-body">
                    <form action="/create/url" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="url">Url to article</label>
                            <input type="text" class="form-control" name="url" id="url" placeholder="https://example.com/article/2018-01-01-you-wont-believe-what-happened-next" value="{{ Request::get('url') }}">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create from URL</button>
                            <button type="submit" class="btn btn-secondary" name="preview">Preview Article</button>
                        </div>
                    </form>
                </div>
                <h6 class="line">OR</h6>
                <br>
                <div class="card-body">
                    <form action="/create" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="title">Article Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="My bloody amazing article!">
                        </div>
                        <div class="form-group">
                            <label for="body">Article Body</label>
                            <textarea class="form-control" name="body" id="body" rows="10" placeholder="Lorem ipsum dolor sit amet..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="tags">Tags</label>
                            <input type="text" name="tags" id="tags" class="form-control" aria-describedby="tagsHelp" placeholder="php,laravel,elasticsearch">
                            <small id="tagsHelp" class="form-text text-muted">Comma separated list of tags</small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Article</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        $(function() {
            var myHeading = $('h6.line'),
            myHeadingText = $(myHeading).text(),
            myFullText = '<span>' + myHeadingText + '</span>';
            $(myHeading).html(myFullText);
        });
    </script>
</body>
</html>