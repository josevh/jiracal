@section('meta')
    <!-- env: {{App::environment()}} -->
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta charset="UTF-8">
    <title>{{ $meta['title'] or env('META_TITLE') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="{{ $meta['description'] or env('META_DESCRIPTION') }}">
    <meta name="author" content="{{ $meta['author'] or env('META_AUTHOR') }}">
@show
