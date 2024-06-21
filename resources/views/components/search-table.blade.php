@php
	use App\Classes\Help;
	
	// Get modal single and plural names
	$model_name = Str::kebab(class_basename($model));
	$model_plural = Str::plural($model_name);
	$modelfilter = $modelfilter ?? [];
	
	// Get model fields
	$fields = $model::getTableFields();
	
	// Get pagination parameters
	$page = $page ?? 1;
	$query ??= "";
	$advanced ??= [];
	$advanced_values ??= [];
	$limit = $limit ?? 15;
	
	// Get model primary key
	$model_key = $model::getModelKey();
	
	// Prepare query filters
	$filter = [];
	$filter_values = [];
	
	foreach($fields as $key => $field){
		
		if(!empty($field["filter"])){
			if(!empty($advanced[$key])){
				if(($field["advanced-type"] ?? null) == "date-range"){
					$dates = explode(" - ", $advanced[$key]);
					
					$filter[] = ($field["custom-filter"] ?? $key)." BETWEEN ? AND ?";
					$advanced_values[] = Help::convert_date($dates[0]);
					$advanced_values[] = Help::convert_date($dates[1] ?? $dates[0]);
				}
				else if(($field["advanced-type"] ?? null) == "in-array"){
					$multi_filter = [];
					
					foreach($advanced[$key] as $value){
						$multi_filter[] = "CONVERT(".($field["custom-filter"] ?? $key)." using 'utf8') LIKE '%".$value."%'";
					}
					
					$filter[] = "(".implode(" AND ", $multi_filter).")";
				}
				else{
					$multi_filter = [];
					
					foreach($advanced[$key] as $value){
						$multi_filter[] = "CONVERT(".($field["custom-filter"] ?? $key)." using 'utf8') = ?";
						$advanced_values[] = $value;
					}
					
					$filter[] = "(".implode(" OR ", $multi_filter).")";
				}
			}
			else if(Help::empty_dictionary($advanced)){
				$filter[] = "CONVERT(".($field["custom-filter"] ?? $key)." using 'utf8') LIKE ?";
				$filter_values[] = "%".$query."%";
			}
		}
		
		if(!empty($field["sort"])){
			$model_sort[] = ($field["custom-filter"] ?? $key)." ".$field["sort"];
		}
	}
	
	// Perform model search
	$search = !empty($filter) ? $model::whereRaw("(".implode(Help::empty_dictionary($advanced) ? " OR " : " AND ", $filter).")", !Help::empty_dictionary($advanced) ? $advanced_values : $filter_values) : $model::query();
	
	if(!empty($advanced)){
		// dd($advanced);
		// $search->dd();
		// dd(Help::empty_dictionary($advanced));
	}
	
	// Check model filter
	foreach($modelfilter as $key => $value){
		$search = $search->whereRaw("CONVERT(".($fields[$key]["custom-filter"] ?? $fields[$key]["key"] ?? $key)." using 'utf8') = ?", $value);
	}
	
	// Clone search for count rows
	$count = clone($search);
	
	// Apply order by
	$search->orderByRaw(implode(",", $model_sort));
@endphp
{{-- <div class="row">
	<div class="col-md-12">
		@dump($search->dump())
	</div>
</div> --}}
<div class="row justify-content-center mt-3">
    <div class="col-md-{{$size ?? 8}}">
        <table class="{{$model_plural}}-table table-search table table-striped">
            {{-- Table header --}}
            <thead class="table-dark">
                <tr>
                    <th colspan="100%">
                        <div class="container-fluid px-0">
                            <div class="row">
                                {{-- Filter input --}}
                                <div class="col align-self-center">
                                    <input type="text" id="filter" name="filter" class="form-control p-1" placeholder="Cerca" data-value=""
                                        hx-post="{{route($model_plural.'.list')}}" hx-trigger="keyup changed" hx-target="#{{$model_plural}}-table-data" hx-include="[name^='advanced_search']"
                                        @if (!empty($modelfilter))
                                            hx-vals='{{json_encode(["modelfilter" => $modelfilter])}}'                                             
                                        @endif
                                        >
                                </div>
                                
                                {{-- Add new button --}}
								@empty($disableaddbutton)
									<div class="col-auto py-2 ps-0">
										@if(!empty($addRedirect))
											<a href="{{$addRedirect}}" class="text-white">
												<i role="button" class="add-new fa-solid fa-plus pe-2"></i>
											</a>
										@else
											<i role="button" id="add-new-{{$model_name}}" class="add-new fa-solid fa-plus pe-2"
												data-bs-target="#modal" data-bs-toggle="modal"
												hx-post="{{route($model_name.".details", [])}}" hx-target="#modal .modal-content"
												@if (!empty($modelfilter))
													hx-vals='{{json_encode($modelfilter)}}'                                             
												@endif
												></i>
										@endif
									</div>
								@endempty
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    {{-- Fields header --}}
                    @foreach ($fields as $key => $field)
                        @continue($field["hidden"] ?? false)
                        <th>{{$field["custom-label"] ?? Str::ucfirst(__("validation.attributes.".$key))}}</th>
                    @endforeach
                    
                    {{-- Primary column header --}}
					@if (method_exists($model, "getTableActions") && count($model->getTableActions($model_name, $model_key, $model->{$model_key})) > 0)
						<th class="text-end">Gestisci</th>
					@endif
                </tr>
            </thead>
            
            {{-- Table body --}}
            <tbody id="{{$model_plural}}-table-data">
                @fragment("search-table-body")
					
					{{-- <tr>
						<td>
							@dump($search->dumpRawSql())
						</td>
					</tr> --}}
					
					{{-- Loop trough results --}}
					@foreach ($search->paginate($limit, ['*'], 'page', $page) as $model_obj)
						<tr data-id="{{($model_obj->{$model_key})}}">
							{{-- Model fields --}}
							@foreach ($fields as $key => $field)
								@continue($field["hidden"] ?? false)
								<td>{!!(!empty($field["custom-value"]) ? $model_obj->{$field["custom-value"]}() : $model_obj->{$key})!!}</td>
							@endforeach
							
							@if (method_exists($model, "getTableActions") && count($model_obj->getTableActions($model_name, $model_key, $model->{$model_key})) > 0)
								{{-- Model edit button --}}
								<td class="text-end">
									@foreach ($model_obj->getTableActions($model_name, $model_key, $model_obj->{$model_key}) as $action)
										@isset($action["url"])
											<a href="{{$action["url"]}}" class="d-inline-block ms-3 text-decoration-none text-black" title="{{$action["title"] ?? ''}}" {!!$action["custom-attributes"] ?? ''!!}>
												{!!$action["icon"]!!}
											</a>
										@else
											<span role="button" class="d-inline-block ms-3" {!!$action["custom-attributes"]!!}>
												{!!$action["icon"]!!}
											</span>
										@endisset
									@endforeach
								</td>
							@endif
						</tr>
					@endforeach
					
					{{-- Pagination --}}
					<tr class="table-dark">
						<td class="text-center" colspan="100%">
							<div class="row">
								<div class="col text-end align-self-center">
									<button class="paginator text-white bg-transparent border-0" data-action="previous">
										<i class="fa-solid fa-chevron-left"></i>
									</button>
								</div>
								<div class="col-auto align-self-center">
									<input type="text" id="page" name="page" class="table-page" value="{{$page}}"
										hx-post="{{route($model_plural.'.list')}}" hx-trigger="keyup changed, change" hx-target="#{{$model_plural}}-table-data" hx-include="[name='filter'],[name^='advanced_search']"
										@if (!empty($modelfilter))
											hx-vals='{{json_encode(["modelfilter" => $modelfilter])}}'
										@endif
										>
									/
									<span id="last-page">{{ceil($count->count() / $limit)}}</span>
								</div>
								<div class="col text-start align-self-center">
									<button class="paginator text-white bg-transparent border-0" data-action="next">
										<i class="fa-solid fa-chevron-right"></i>
									</button>
								</div>
							</div>
						</td>
					</tr>
				@endfragment
            </tbody>
        </table>
    </div>
</div>

<script>
	document.addEventListener("click", function(e){
		for (var el=e.target; el && el!=this; el=el.parentNode){
			if(el.matches('.paginator')){
				let action = $(el).attr("data-action");
				let current_page = parseInt($("#page").val());
				let last_page = parseInt($("#last-page").text());
				
				if(action == "previous"){
					current_page -= current_page > 1;
				}
				else if(action == "next"){
					current_page += current_page < last_page;
				}
				
				$("#page").val(current_page);
				htmx.trigger("#page", "change");
				
				break;
			}
		}
	}, false);
</script>