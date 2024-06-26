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
            {{-- Title --}}
            <x-backoffice.title :title="config('app.name')" />
            
            {{-- Search Table --}}
            <x-search-table-filters.shorts />
            <x-search-table :model="new App\Models\Short()"></x-search-table>
            
            <style>
                td:nth-child(2) {
                    max-width: 600px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
            </style>
        </div>
                
        {{-- Menu --}}
        <x-backoffice.menu />
        
        {{-- Modal --}}
        <x-modal/>
        <x-modal id="2"/>
        
        {{-- Ajax responses --}}
        <div id="request-response"></div>
        
        {{-- Scripts --}}
        <x-backoffice.script></x-backoffice.script>
    </body>
</html>