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
                <x-backoffice.title :title="config('app.name')" />
            </div>
            
            {{-- Search Table --}}
            {{-- <x-search-table-filters.users /> --}}
            <x-search-table :model="new App\Models\Short()"></x-search-table>
        </div>
                
        {{-- Menu --}}
        <x-backoffice.menu />
        
        {{-- Scripts --}}
        <x-backoffice.script></x-backoffice.script>
        
        {{-- Ajax responses --}}
        <div id="request-response"></div>
    </body>
</html>