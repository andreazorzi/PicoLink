window.scrollToElement = function(element, padding_top = 0){
	let header_height = document.querySelector("header").offsetHeight;
	let y = element.getBoundingClientRect().top + $(window).scrollTop() - header_height - padding_top;
	
	window.scrollTo({top: y, behavior: 'smooth'});
}

window.format_date = function(date, format = "Y-m-d"){
	if(format == "Y-m-d"){
		return date.getFullYear() + "-" + String(date.getMonth() + 1).padStart(2, '0') + "-" + String(date.getDate()).padStart(2, '0');
	}
	else if(format == "d/m/Y"){
		return String(date.getDate()).padStart(2, '0') + "/" + String(date.getMonth() + 1).padStart(2, '0') + "/" + date.getFullYear();
	}
}