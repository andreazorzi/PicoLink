<head>
        <title>{{(!empty($title) ? $title." - " : "").config("app.name")}}</title>
        
        <!-- Icons -->
        <link rel="icon" type="image/png" href="{{asset("images/favicon.png")}}" />

        <!-- Meta -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- CSS -->
        @vite(['resources/css/app.css', 'resources/scss/theme.scss'])
        
        {{ $slot }}
</head>
