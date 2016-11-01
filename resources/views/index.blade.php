<!DOCTYPE html>
<html>
    <head>
        <title>{{ config('app.name', 'Index') }}</title>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Styles -->
        <link rel="stylesheet" href="dist/semantic.min.css">
        <!-- <link rel="stylesheet" href="/css/app.css"> -->

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>

    </head>
    <body>
        <div id="app"></div>
        <script src="/js/app.js?date={{ date('ymdHis') }}"></script>
        <script src="dist/semantic.min.js"></script>
    </body>
</html>
