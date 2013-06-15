 $(document).ready(function(){


 });


function addUserSet(setName) {

	var myURL = "/ro/my-account/add-set";
	var myMethod = "post";
	var locationID = $("#destination-list").val();
	var myParams = { 'set-name':setName, "location-id": locationID};
	var callback = "monitorSetFilters";
	var updateContainers = { 
			"#set-container" : {"ajax":"sets", "method":"replace"}
	};
	//runAfter,runParams are defined in the render calling this function or above	
	ajaxQuery(myURL, myParams, myMethod, updateContainers, callback);
}

function deleteUserSet(setID) {

	var myURL = "/ro/my-account/delete-set";
	var myMethod = "post";
	var locationID = $("#destination-list").val();
	var myParams = { 'set-id':setID, "location-id": locationID };
	var callback = "monitorSetFilters";
	var updateContainers = { 
			"#set-container" : {"ajax":"sets", "method":"replace"}
	};
	//runAfter,runParams are defined in the render calling this function or above	
	ajaxQuery(myURL, myParams, myMethod, updateContainers,callback);
}

function addClickHandlerForSetPhotos() {
	$("ul.selectable li").click(function() {
		if($(this).hasClass("ui-selected")) {
			$(this).removeClass("ui-selected");
			var setID = $("#set-list").val();
			removePhotoFromSet(setID,$(this).attr("rel"));
		} else {
			$(this).addClass("ui-selected");
			var setID = $("#set-list").val();
			addPhotoToSet(setID,$(this).attr("rel"));
		}
	});
	
}

function removePhotoFromSet(setID,photoID) {
	var myURL = "/ro/my-account/photoset";
	var myMethod = "post";
	var myParams = { 'set-id':setID, "photo-id":photoID, "operation":"remove" };
	var updateContainers = {};
	//runAfter,runParams are defined in the render calling this function or above	
	ajaxQuery(myURL, myParams, myMethod, updateContainers);
}

function addPhotoToSet(setID,photoID) {
	var myURL = "/ro/my-account/photoset";
	var myMethod = "post";
	var myParams = { 'set-id':setID, "photo-id":photoID, "operation":"add" };
	var updateContainers = {};
	//runAfter,runParams are defined in the render calling this function or above	
	ajaxQuery(myURL, myParams, myMethod, updateContainers);
}

function filterSetPhotos() {
	$('#dialog-addphotos').dialog('close');
	var myURL = "/ro/my-account/filter-set-photos";
	var myMethod = "post";
	var setID = $("#set-list").val();
	var locationID = $("#destination-list").val();
	var myParams = { 'set-id':setID, "location-id":locationID};
	var callback = "addClickHandlerForSetPhotos";
	var updateContainers = { 
			"#photo-set" : {"ajax":"photos", "method":"replace"}
	};
	//runAfter,runParams are defined in the render calling this function or above	
	ajaxQuery(myURL, myParams, myMethod, updateContainers, callback);
}

function viewSetPhotos() {
	var myURL = "/ro/my-account/filter-set-photos";
	var myMethod = "post";
	var setID = $("#set-list").val();
	var locationID = "set";
	var myParams = { 'set-id':setID, "location-id":locationID};
	var callback = "addClickHandlerForSetPhotos";
	var updateContainers = { 
			"#photo-set" : {"ajax":"photos", "method":"replace"}
	};
	//runAfter,runParams are defined in the render calling this function or above	
	ajaxQuery(myURL, myParams, myMethod, updateContainers, callback);
}

function monitorSetFilters() {
	$("#destination-list").bind('change',function() {
		filterSetPhotos();
	});
	$("#set-list").bind('change',function() {
		viewSetPhotos();
	});
	addClickHandlerForSetPhotos();
}

function photoActive(id,photo,favorite) {

	var photoURL = "http://www.facebook.com/sharer.php?u="+"http://www.myrepublic.org"+window.location.pathname + "?p="+id;
	$('#share-link').attr("href",photoURL);
	$('#big-image-link').attr("href",photo);
	$('#photo-id').val(id);
	favorite = ""+favorite;
	switch (favorite) {
		case "1":
					$('.like').addClass("selected");
					break;
		case "0":
					$('.like').removeClass("selected");
					break;
	}
	
    Shadowbox.setup("#big-image-link", {
        overlayOpacity: .9
    });

	
}
