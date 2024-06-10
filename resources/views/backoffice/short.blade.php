@php
    $from ??= date("Y-m-d", strtotime("-6 days"));
    $to ??= date("Y-m-d");
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
                        
                    <div class="row mt-3 g-3">
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
                                                        <div class="col-auto align-self-center">
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row g-3">
                                        <div class="col align-self-center">
                                            Timeline
                                        </div>
                                        <div class="col-auto align-self-center text-end">
                                            <input type="text" id="range" class="form-select" name="range" role="button" readonly
                                                hx-post="{{route("short.get-timeline-data", [$short])}}" hx-target="#timeline-container">
                                        </div>
                                    </div>
                                </div>
                                <div id="timeline-container" class="card-body">
                                    @fragment("timeline")
                                        <div style="max-height: 350px; width: 100%;">
                                            <canvas id="timeline" class="w-100"></canvas>
                                            <script>
                                                @if (!is_null($fragment ?? null))
                                                    data = @json($short->getTimeline($from, $to));
                                                    
                                                    labels = data.map(entry => entry.date + " - " + entry.total_visits);
                                                    languages = [...new Set(data.flatMap(entry => Object.keys(entry.visits)))];
                                                    
                                                    datasets = languages.map((language, index) => {
                                                        return {
                                                            label: `${language}`,
                                                            data: data.map(entry => entry.visits[language] || 0),
                                                            backgroundColor: `rgba(${index * 60 % 256}, ${(index * 120 + 50) % 256}, ${(index * 200 + 100) % 256}, 0.2)`,
                                                            borderColor: `rgba(${index * 60 % 256}, ${(index * 120 + 50) % 256}, ${(index * 200 + 100) % 256}, 1)`,
                                                            borderWidth: 1
                                                        };
                                                    });
                                    
                                                    new Chart(
                                                        document.getElementById('timeline'),
                                                        {
                                                            type: 'bar',
                                                            data: {
                                                                labels: labels,
                                                                datasets: datasets
                                                            },
                                                            options: {
                                                                responsive: true,
                                                                maintainAspectRatio: false,
                                                                scales: {
                                                                    x: {
                                                                        stacked: true,
                                                                    },
                                                                    y: {
                                                                        stacked: true,
                                                                        beginAtZero: true
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    );
                                                @endif
                                            </script>
                                        </div>
                                    @endfragment
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                
        {{-- Menu --}}
        <x-backoffice.menu />
        
        {{-- Ajax responses --}}
        <div id="request-response"></div>
        
        {{-- Scripts --}}
        <x-backoffice.script></x-backoffice.script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                delivery_date = new AirDatepicker('#range', {
                    locale: locale_{{App::getLocale()}},
                    dateFormat: 'dd/MM/yyyy',
                    autoClose: false,
                    toggleSelected: true,
                    showOnFocus: true,
                    range: true,
                    multipleDatesSeparator: ' - ',
                    buttons: [
                        {
                            content(dp) { return '<i class="fa-solid fa-trash text-danger"></i>'; },
                            onClick(dp) {
                                delivery_date.clear();
                            }
                        },
                        {
                            content(dp) { return "Ultimo Mese"; },
                            onClick(dp) {
                                delivery_date.selectDate([
                                    new Date('{{date("Y-m-d", strtotime("first day of last month"))}}'),
                                    new Date('{{date("Y-m-d", strtotime("last day of last month"))}}')
                                ]);
                            }
                        },
                        {
                            content(dp) { return "Mese Corrente"; },
                            onClick(dp) {
                                delivery_date.selectDate([
                                    new Date('{{date("Y-m-d", strtotime("first day of this month"))}}'),
                                    new Date('{{date("Y-m-d", strtotime("last day of this month"))}}')
                                ]);
                            }
                        }
                    ],
                    onRenderCell({date, cellType}) {
                        
                    },
                    onSelect({date}) {
                        htmx.trigger("#range", "change");
                    }
                });
                delivery_date.selectDate([new Date('{{date("Y-m-d", strtotime($from))}}'), new Date('{{date("Y-m-d", strtotime($to))}}')]);
            });
        </script>
    </body>
</html>