@php
    
@endphp
<!DOCTYPE html>
<html>
    {{-- Head --}}
    <x-backoffice.head title="Home"></x-backoffice.head>
    
    <body class="container-fluid" hx-headers='{"X-CSRF-TOKEN": "{{csrf_token()}}"}' hx-ext="ajax-header" hx-target="#request-response">
        {{-- Header --}}
        <x-backoffice.header />

        <div id="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    {{-- Title --}}
                    <x-backoffice.title :title="$short->code" :subtitle="$short->description"/>
                        
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            URLs
                                        </div>
                                        <div class="col align-self-center text-end">
                                            {{ucfirst(__("validation.attributes.visits"))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-2">
                                        @foreach ($short->urls()->orderBy("language")->get() as $url)
                                            @php
                                                $language = !is_null($url->language) ?__("languages.{$url->language}") : "Default";
                                            @endphp
                                            <div class="col-12">
                                                <div class="{{!$loop->last ? 'border-bottom' : ''}}">
                                                    <div class="row g-3 {{!$loop->last ? 'pb-2' : ''}}">
                                                        <div class="col-auto">
                                                            <img class="url-flag" title="{{$language}}" alt="{{$language}}" src="{{asset("images/lang/".($url->language ?? 'default').".svg")}}">
                                                        </div>
                                                        <div class="col align-self-center">
                                                            {{$url->url}}
                                                        </div>
                                                        <div class="col-auto">
                                                            {{$url->visits()->count()}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
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