function like() {

	var myURL = "/ro/destinations/like";
	var myMethod = "post";
	var photoID = jQuery('#photo-id').val();
	var myParams = { 'p': photoID };
	
	var callbackSuccess = "likeStatus";
	var	callbackParams = {};

	var updateContainers = { 
			"#fav-status" : {"ajax":"content", "method":"input_value"}, 
	};
	
	ajaxQuery(myURL, myParams, myMethod, updateContainers,callbackSuccess);
}

function like() {

	var myURL = "/ro/destinations/like";
	var myMethod = "post";
	var photoID = jQuery('#photo-id').val();
	var myParams = { 'p': photoID };
	
	var callbackSuccess = "likeStatus";
	var	callbackParams = {};

	var updateContainers = { 
			"#fav-status" : {"ajax":"content", "method":"input_value"}, 
	};
	
	ajaxQuery(myURL, myParams, myMethod, updateContainers,callbackSuccess);
}

function likeStatus() {
	var status = jQuery('#fav-status').val();
	switch (status) {
		case "added":
					$('.like').addClass("selected");
					flashFavorite("1");
					break;
		case "removed":
					flashFavorite("0");
					$('.like').removeClass("selected");
					break;
		case "not_auth":
					alert("You are not authentified.");
					break;
	}
}


function getFlashMovie(movieName) {
  var isIE = navigator.appName.indexOf("Microsoft") != -1;
  return (isIE) ? window[movieName] : document[movieName];
}

function flashFavorite(favStatus) 
{
	var photoID = jQuery('#photo-id').val();
	getFlashMovie('cities').setFlashFavorite(photoID,favStatus);
}