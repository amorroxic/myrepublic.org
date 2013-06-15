function deletePhoto(id) {

	var myURL = "/admin/photos/delete";
	var myMethod = "post";
	var myParams = { 'id': id };
	
	var updateContainers = {}; 
	
	//runAfter,runParams are defined in the render calling this function or above	
	ajaxQuery(myURL, myParams, myMethod, updateContainers,runAfter,runParams);
}

function assignPhoto(id,locationID) {

	var myURL = "/admin/photos/assign";
	var myMethod = "post";
	var myParams = { 'id':id, 'location_id': locationID};
	
	var updateContainers = {};
	
	//runAfter,runParams are defined in the render calling this function or above	
	ajaxQuery(myURL, myParams, myMethod, updateContainers,runAfter,runParams);
}

function refreshLocation(params) {
	
	var myURL = "/admin/photos/locations";
	var myMethod = "post";
	var myParams = { 'destination': params.destination, 'page':params.page };
	
	
	var updateContainers = { 
			"#photo-list" : {"ajax":"photos", "method":"replace"}, 
	};
	
	ajaxQuery(myURL, myParams, myMethod, updateContainers);
	
}

function refreshCountry(params) {
	
	var myURL = "/admin/photos/locations";
	var myMethod = "post";
	var myParams = { 'country': params.country, 'page':params.page };
	
	var updateContainers = { 
			"#photo-list" : {"ajax":"photos", "method":"replace"}, 
	};
	
	ajaxQuery(myURL, myParams, myMethod, updateContainers);
	
}

function refreshNewest(params) {
	
	var myURL = "/admin/photos/newest";
	var myMethod = "post";
	var myParams = { 'page':params.page };
	
	var updateContainers = { 
			"#photo-list" : {"ajax":"photos", "method":"replace"}, 
	};
	
	ajaxQuery(myURL, myParams, myMethod, updateContainers);
	
}

function refreshUserPhotos(params) {
	
	var myURL = "/admin/users/";
	var myMethod = "post";
	var myParams = { 'id': params.id, 'page':params.page };
	
	var updateContainers = { 
			"#photo-list" : {"ajax":"photos", "method":"replace"}, 
	};
	
	ajaxQuery(myURL, myParams, myMethod, updateContainers);
	
}

function refreshSearchPhotos(params) {

	var myURL = "/admin/photos/search/";
	var myMethod = "post";
	var myParams = { 'search': params.search,'search_for':params.search_for, 'page':params.page };
	
	var updateContainers = { 
			"#photo-list" : {"ajax":"photos", "method":"replace"}, 
	};
	
	ajaxQuery(myURL, myParams, myMethod, updateContainers);

}
