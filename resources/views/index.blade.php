<!DOCTYPE html>
<html>
    <head>
        <title>{{ config('app.name', 'Index') }}</title>
        <link rel="stylesheet" href="/css/app.css?date={{ date('ymdHis') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div id="app"></div>
        <script src="/js/app.js?date={{ date('ymdHis') }}"></script>
    </body>
</html>
