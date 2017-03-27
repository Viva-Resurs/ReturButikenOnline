<!DOCTYPE html>
<html>
    <head>
        <title>{{ config('app.name', 'Index') }}</title>
        <base href="{{ substr($_SERVER['SCRIPT_NAME'],0,strripos($_SERVER['SCRIPT_NAME'],'/')+1) }}">
        <link rel="stylesheet" href="css/semantic.min.css">
        <link rel="stylesheet" href="css/app.css?date={{ date('ymdHis') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div id="app"></div>
        <script src="js/shop.js?date={{ date('ymdHis') }}"></script>
    </body>
</html>
