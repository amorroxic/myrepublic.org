package ro.nouauzina.data
{	
	
  import flash.events.*;
  import flash.net.*;	
  import ro.nouauzina.utils.*;	

  public class DataLoader extends EventDispatcher
  {
			
		private var dataDescriptor:XML;
		private var images:XMLList;		
		private var imageArray:Array;
		private var myService:NetConnection;

		public function DataLoader(method:String = "xml") {			
			
			switch (method) {
				case "xml":
					//loadXMLData();
					break;
				case "remoting":
					//loadRemoteData();
					break;					
			}
		}
			
		private function loadXMLData() {
			var xmlloader:URLLoader = new URLLoader();
			xmlloader.addEventListener(Event.COMPLETE, onXmlComplete);
			xmlloader.load(new URLRequest(OutsideProperties.basePath + "/data/data.xml"));			
		}
					
		private function loadRemoteData() {
		    myService = new NetConnection();
		    myService.objectEncoding = ObjectEncoding.AMF0;
		    myService.connect(OutsideProperties.basePath + "amf/gateway.php");
				var responder = new Responder(remoteDataReceived, broadcastFail);
				myService.call("Movies.getMovie", responder);
		}
				
		private function remoteDataReceived(stream:Object) {
		
			imageArray = new Array();
		
			for (var i:Number=0; i<stream.data.channel.length; i++) {
				
				var note:Object = stream.data.channel[i];
				
				var t:String = note.title;
				var backgroundURL:String = "backgrounds/"+note.image;
				var timeToInsert:Number = parseFloat(note.schedule) * 1000;
				var appearance:Number = parseFloat(note.duration) * 1000;
				var pause:String = note.moviepause;
				var data = {title: t, imageURL: backgroundURL, schedule: timeToInsert, duration: appearance, moviepause: pause};
				imageArray.push( data );				
			}
			
			broadcastLoaded();
	
		}		
		
		private function onXmlComplete(event:Event) {
			
			dataDescriptor = new XML(event.currentTarget.data);	
			images = dataDescriptor.image;	
			var numberOfImages:Number = images.length();
			
			imageArray = new Array();
	
			for (var i:Number=0; i<numberOfImages; i++) {
				var image:XML = images[i];
				
				var backgroundURL:String = image.url.text();
				var data = { imageURL: backgroundURL };
				imageArray.push( data );				
			}
			
			broadcastLoaded();
		}
				
		private function broadcastLoaded() {
			var finishEvent = new Event("loaded");
			dispatchEvent(finishEvent);
		}
		
		private function broadcastFail(e:Event) {
			var finishEvent = new Event("fail");
			dispatchEvent(finishEvent);
		}
		
	    public function get data() {
	    	return imageArray;
	    }
				
    }
    
}


