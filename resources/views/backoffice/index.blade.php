@php
    use App\Models\User;
@endphp
<!DOCTYPE html>
<html>
    {{-- Head --}}
    <x-backoffice.head title="Home"></x-backoffice.head>
    
    <body class="container-fluid" hx-headers='{"X-CSRF-TOKEN": "{{csrf_token()}}"}' hx-ext="ajax-header" hx-target="#request-response">
        {{-- Header --}}
        <x-backoffice.header />

        <div id="container">
            <div class="row mb-3">
                {{-- Title --}}
                <x-backoffice.title title="Example Title">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                </x-backoffice.title>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div id="bookmarks" class="row justify-content-center">
                        @foreach (Route::getRoutes() as $route)
                            @if(strpos($route->getName(), "backoffice.") !== false && !empty($route->defaults["headers"]["menu"] ?? []) && User::current()->canAccessRoute($route) && ($route->defaults["headers"]["menu"] ?? false))
                                <x-backoffice.bookmark :title="__('app.'.$route->getName().'.title')" :route="$route->getName()" :color="__('app.'.$route->getName().'.color')" :icon="__('app.'.$route->getName().'.icon')"/>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
                
        {{-- Menu --}}
        <x-backoffice.menu />
        
        {{-- Scripts --}}
        <x-backoffice.script></x-backoffice.script>
        
        {{-- Ajax responses --}}
        <div id="request-response"></div>
    </body>
</html>