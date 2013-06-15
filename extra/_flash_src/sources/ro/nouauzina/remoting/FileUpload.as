package ro.nouauzina.remoting {

	import flash.events.*;
	import flash.net.*;
	import ro.nouauzina.events.*;
  	import ro.nouauzina.utils.*;
  	import flash.external.ExternalInterface;	

	public class FileUpload extends EventDispatcher {

		public static var UPLOAD_COMPLETE = "files_completed";

		private var fileReferenceList:FileReferenceList;
		private var uploadURL:URLRequest;
		private var variables:URLVariables;
	
		private var indexFileUploading:Number;
		private var totalFiles:Number;
		
		private var filesUploaded:Array;
		private var filesError:Array;
	
		public function FileUpload(theURL:String) {
		
	        filesUploaded = new Array();
	        filesError = new Array();
	        
	        indexFileUploading = -1;
	        
			fileReferenceList = new FileReferenceList();
	        fileReferenceList.addEventListener(Event.SELECT, selectHandler);
	        fileReferenceList.addEventListener(Event.CANCEL, cancelHandler);			
	        
			variables = new URLVariables();
	        uploadURL = new URLRequest();
	        addParameter("s",OutsideProperties.session);
			/*
            ExternalInterface.call('eval','window.cookieStr = function () {return  document.cookie};')
			var cookieStr:String = ExternalInterface.call('cookieStr');  
			var cookieHeader:URLRequestHeader = new URLRequestHeader("Cookie",cookieStr);
	        variables["Cookie"] = cookieStr;
	        uploadURL.requestHeaders = new Array();
	        uploadURL.requestHeaders.push(cookieHeader);
	        */
	        uploadURL.method = URLRequestMethod.POST;
	        uploadURL.data = variables;
	        uploadURL.url = theURL;	        
	        
		}
		
		public function addParameter(key:String,value:String) {
			variables[key] = value;	        
		}

		public function openUploadDialog() {
			var fileTypes:Array = new Array();
			fileTypes.push(getImageTypeFilter());
			fileReferenceList.browse(fileTypes);
		}

	    private function getImageTypeFilter():FileFilter {
	        return new FileFilter("Images (*.jpg, *.jpeg, *.gif, *.png)", "*.jpg;*.jpeg;*.gif;*.png");
	    }

	    private function selectHandler(event:Event):void {

	        var file:FileReference;
	        totalFiles = fileReferenceList.fileList.length;

	        var e:UploadEvent = new UploadEvent(UploadEvent.FILE_UPLOADED);
	        e.filesUploaded = filesUploaded.length;
	        e.filesError	= filesError.length;
	        e.totalFiles	= totalFiles;
	        dispatchEvent(e);

			uploadNextFile();
			
	    }
	 
	 	private function uploadNextFile() {
			indexFileUploading++;
	 		if (indexFileUploading >= totalFiles) {
		        var event:Event = new Event(FileUpload.UPLOAD_COMPLETE);
		        dispatchEvent(event);
	 			return;
	 		} else {
	 			var file:FileReference = FileReference(fileReferenceList.fileList[indexFileUploading]);
		        file.addEventListener(Event.OPEN, openHandler);
		        file.addEventListener(Event.COMPLETE, completeHandler);
		        file.addEventListener(IOErrorEvent.IO_ERROR, ioErrorHandler);
		        file.addEventListener(ProgressEvent.PROGRESS, progressHandler);
		        file.addEventListener(SecurityErrorEvent.SECURITY_ERROR, securityErrorHandler);
		        file.upload(uploadURL,"image");
		        return;
	 		}
	 	}
	 
	    private function cancelHandler(event:Event):void {
	        var file:FileReference = FileReference(event.target);
	        filesError.push(file.name);
	        uploadNextFile();
	    }

	    private function openHandler(event:Event):void {
	        //var file:FileReference = FileReference(event.target);
	        //trace("openHandler: name=" + file.name);
	    }
	 
	    private function progressHandler(event:ProgressEvent):void {
	        //var file:FileReference = FileReference(event.target);
	        //trace("progressHandler: name=" + file.name + " bytesLoaded=" + event.bytesLoaded + " bytesTotal=" + event.bytesTotal);
	    }
	 
	    private function completeHandler(event:Event):void {

	        var file:FileReference = FileReference(event.target);
	        filesUploaded.push(file.name);
	    
	        var e:UploadEvent = new UploadEvent(UploadEvent.FILE_UPLOADED);
	        e.filesUploaded = filesUploaded.length;
	        e.filesError	= filesError.length;
	        e.totalFiles	= totalFiles;
	        dispatchEvent(e);
	    
	        uploadNextFile();
	    }
	 
	    private function httpErrorHandler(event:Event):void {
	        var file:FileReference = FileReference(event.target);
	        filesError.push(file.name);
	        uploadNextFile();
	    }
	 
	    private function ioErrorHandler(event:Event):void {
	        var file:FileReference = FileReference(event.target);
	        filesError.push(file.name);
	        uploadNextFile();
	    }
	 
	    private function securityErrorHandler(event:Event):void {
	        var file:FileReference = FileReference(event.target);
	        filesError.push(file.name);
	        uploadNextFile();
	    }

        private function httpStatusHandler(event:HTTPStatusEvent):void {
	        var file:FileReference = FileReference(event.target);
	        filesError.push(file.name);
	        uploadNextFile();
        }

	    			
	}
	

}