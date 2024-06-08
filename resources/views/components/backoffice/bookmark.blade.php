@php
    $color = $color ?? "#acacac";
    
    if(isset($randomcolor)){
        $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
@endphp
<div class="col-md-4 mb-4">
    <a href="{{route($route ?? "backoffice.index")}}" class="no-link">
        <div class="bookmark text-center rounded" style="background-color: #f8f8f8; --bookmark-color: {{$color}}">
            <div class="bookmark-head p-2 rounded rounded-bottom-0" style="background-color: color-mix(in srgb, var(--bookmark-color), transparent 66%); border: 2.5px solid var(--bookmark-color);">
                {{$title ?? ""}}
            </div>
            <div class="bookmark-body p-4 rounded rounded-top-0 border-top-0" style="border: 2.5px solid var(--bookmark-color);">
                <i class="fa-solid {{$icon ?? ""}} fs-1"></i>
            </div>
        </div>
    </a>
</div>