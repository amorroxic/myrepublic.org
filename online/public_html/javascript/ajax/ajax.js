function ajaxQuery(ajaxURL, ajaxParams, ajaxMethod, updateContainers, callbackSuccess, callbackParams) {

	jQuery.ajax({
	  url: ajaxURL,
	  type: "POST",
	  data: ajaxParams,
	  dataType: "json",
	  success: function(data) {
	    ajaxResult(data,updateContainers,callbackSuccess,callbackParams);
	  },
	  error: function(data) {
	     alert('Sorry, the website is under heavy stress. Please try again later.');
	  }
	});
	
}



function ajaxResult(jsonResponse, updateContainers, callbackSuccess, callbackParams) {
	
	var mainContent;
	

	try {
		var response = jsonResponse;
		for (var container in updateContainers) {

			mainContent = jQuery(container);
			if (mainContent) {
				var ajaxResponse = updateContainers[container]["ajax"];
				var replacementMethod = updateContainers[container]["method"];
				switch (replacementMethod) {
					case "input_value":
								  	mainContent.val(response[ajaxResponse]);
									break;
					case "replace":
								  	mainContent.html(response[ajaxResponse]);
									break;
					case "update":
								  	mainContent.append(response[ajaxResponse]);
									break;
				}
			}
		}
	  	runFunction(callbackSuccess,callbackParams);
		
	} catch(error) {
		alert('Sorry, the website is under heavy stress. Please try again later [2].');
	}

		
}

