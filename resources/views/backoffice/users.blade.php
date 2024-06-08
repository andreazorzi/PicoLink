<!DOCTYPE html>
<html>
    {{-- Head --}}
    <x-backoffice.head title="Users"></x-backoffice.head>
    
    <body class="container-fluid" hx-headers='{"X-CSRF-TOKEN": "{{csrf_token()}}"}' hx-ext="ajax-header" hx-target="#request-response">
        {{-- Header --}}
        <x-backoffice.header />

        <div id="container">
            <div class="row mb-3">
                {{-- Title --}}
                <x-backoffice.title title="Gestione Utenti">
                    Aggiungi e modifica i utenti
                </x-backoffice.title>
            </div>
        
            {{-- Search Table --}}
            <x-search-table-filters.users />
            <x-search-table :model="new App\Models\User()" query=""></x-search-table>
            
            {{-- Modal --}}
            <x-modal></x-modal>
        </div>
        
        {{-- Footer --}}
        <x-backoffice.footer />
                
        {{-- Menu --}}
        <x-backoffice.menu />
        
        {{-- Scripts --}}
        <x-backoffice.script></x-backoffice.script>
        
        {{-- Ajax responses --}}
        <div id="request-response"></div>
    </body>
</html>