@php
	$page = request()->route()->defaults["headers"]["name"] ?? null;
@endphp
<head>
	<title>
		@if(!empty($page) || !empty($title ?? null))
			{{!empty($page) ? __("app.pages.$page.meta_title") : $title}} - 
		@endif
		
		{{config("app.name")}}
	</title>
	
	<!-- Icons -->
	<link rel="icon" type="image/png" href="{{asset("images/favicon.png")}}" />

	<!-- Meta -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="{{__("app.pages.$page.meta_description")}}">
	<meta name="theme-color" content="#f8f9fa"/>

	<!-- CSS -->
	@vite(['resources/css/app.css', 'resources/scss/theme.scss'])

	{{$slot}}

</head>