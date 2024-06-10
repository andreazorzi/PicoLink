import './bootstrap';

// Bootstrap
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// jQuery
import $ from "jquery";
window.$ = $;

// Toastify
import Toastify from 'toastify-js'
window.Toastify = Toastify;

// Htmx.org
import htmx from "htmx.org";
window.htmx = htmx;
window.htmx.on("htmx:responseError", function (evt) {
	let error = evt.detail.xhr.responseText;
	
	try {
		error = JSON.parse(evt.detail.xhr.responseText).message;
	} catch (error) {}
	
	Toastify({
		text: error,
		escapeMarkup: false,
		duration: '-1',
		close: true,
		className: "danger",
		gravity: "bottom",
		position: "center"
	}).showToast();
	
	if(error == "CSRF token mismatch."){
		window.location.reload();
	}
});
import "htmx.org/dist/ext/ajax-header.js";

// SelectSearch
import SelectSearch from "@andreazorzi/selectsearch";
window.SelectSearch = SelectSearch;

// Selectize
import selectize from "@selectize/selectize";

// Chart.js
import Chart from 'chart.js/auto';
window.Chart = Chart;

// Sortablejs
// import Sortable from 'sortablejs';
// window.Sortable = Sortable;

// Airdatepicker
// import AirDatepicker from 'air-datepicker';
// windows.AirDatepicker = AirDatepicker;
// import locale_en from 'air-datepicker/locale/en';
// import locale_it from 'air-datepicker/locale/it';
// import locale_de from 'air-datepicker/locale/de';
// window.locale_en = locale_en;
// window.locale_it = locale_it;
// window.locale_de = locale_de;

// Autocompleter
// import autocomplete from 'autocompleter';
// window.autocomplete = autocomplete;


import * as Sentry from "@sentry/browser";

Sentry.init({
  dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
});
