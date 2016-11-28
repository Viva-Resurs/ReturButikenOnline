<!DOCTYPE html>
<html>
    <head>
        <title>{{ config('app.name', 'Index') }}</title>
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>
        <div id="app"></div>
        <script src="/js/app.js?date={{ date('ymdHis') }}"></script>
    </body>
</html>
