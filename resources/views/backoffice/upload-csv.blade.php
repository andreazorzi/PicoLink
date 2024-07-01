@php
    use App\Models\TagCategory;
@endphp
<!DOCTYPE html>
<html>
    {{-- Head --}}
    <x-backoffice.head :title="__('app.pages.upload-csv.meta_title')"></x-backoffice.head>
    
    <body class="container-fluid" hx-headers='{"X-CSRF-TOKEN": "{{csrf_token()}}"}' hx-ext="ajax-header" hx-target="#request-response">
        {{-- Header --}}
        <x-backoffice.header />

        <div id="container" class="pt-2">
            {{-- Title --}}
            <x-backoffice.title :title="__('app.pages.upload-csv.meta_title')" />
            
            {{-- Search Table --}}
            <div class="row mt-1 g-3 justify-content-center">
                <div class="col-md-4">
                    <form class="row g-2">
                        <div class="col-md-12">
                            <label>{{__('app.pages.upload-csv.csv_file')}}</label>
                            <input type="file" class="form-control" name="csv" accept=".csv">
                            <a href="{{route("backoffice.csv-template")}}" class="mt-2 fs-7" download>
                                {{__('app.pages.upload-csv.template')}}
                            </a>
                        </div>
                        <div class="col-md-12 text-center">
                            @method('PUT')
                            <button type="submit" class="btn btn-primary"
                                hx-post="{{route("short.upload-csv")}}" hx-target="#request-response" hx-encoding="multipart/form-data" hx-disabled-elt="this">
                                {{__('app.pages.upload-csv.upload')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                
        {{-- Menu --}}
        <x-backoffice.menu />
        
        {{-- Modal --}}
        <x-modal/>
        
        {{-- Ajax responses --}}
        <div id="request-response"></div>
        
        {{-- Scripts --}}
        <x-backoffice.script></x-backoffice.script>
    </body>
</html>