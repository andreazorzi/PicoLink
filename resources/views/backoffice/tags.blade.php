@php
    use App\Models\TagCategory;
@endphp
<!DOCTYPE html>
<html>
    {{-- Head --}}
    <x-backoffice.head title="Tags"></x-backoffice.head>
    
    <body class="container-fluid" hx-headers='{"X-CSRF-TOKEN": "{{csrf_token()}}"}' hx-ext="ajax-header" hx-target="#request-response">
        {{-- Header --}}
        <x-backoffice.header />

        <div id="container" class="pt-2">
            {{-- Title --}}
            <x-backoffice.title :title="__('app.pages.tags.meta_title')" />
            
            {{-- Search Table --}}
            <div class="row mt-1 g-3 justify-content-center">
                <div class="col-md-6">
                    <div class="row g-2">
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary btn-sm"
                                hx-post="{{route("tag.details", [])}}" hx-target="#modal .modal-content">
                                {{__("app.pages.tags.add_tag")}}
                            </button>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div id="tags" class="row g-4">
                                        @fragment("tags")
                                            @foreach (TagCategory::orderBy("name")->get() as $category)
                                                <div class="col-12">
                                                    <h4>{{$category->name}}</h4>
                                                    <hr>
                                                    @foreach ($category->tags()->orderBy("name")->get() as $tag)
                                                        <x-tag :tag="$tag" :editable="true"/>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        @endfragment
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
        
        {{-- Modal --}}
        <x-modal/>
        
        {{-- Ajax responses --}}
        <div id="request-response"></div>
        
        {{-- Scripts --}}
        <x-backoffice.script></x-backoffice.script>
    </body>
</html>