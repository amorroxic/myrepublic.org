package ro.nouauzina.data
{	
	
  	import flash.events.*;
  	import flash.net.*;	
  	import ro.nouauzina.events.*;	
  	import ro.nouauzina.utils.*;	
  	import ro.nouauzina.loaders.*;	
  	import ro.nouauzina.scenes.*;

  public class LazyContentLoader extends EventDispatcher
  {
        public var loader : ImageLoader;
        private var photos : Array;
        private var _totalLoading: int;
			
		public function LazyContentLoader() {			
	      loader = new ImageLoader();
	      loader.addEventListener(ImageLoadedEvent.ITEM_LOADED, itemLoaded);
	      loader.addEventListener(ImageLoadedEvent.ITEM_FAILED, itemFailed);
	      _totalLoading = 0;
	      photos = [];
		}
		
		public function removeCurrentQueue() {
			photos = [];
		    _totalLoading = 0;
		  	loader.removeAllLoading();
		}
		
		public function queue(contentData:Array, windowMode:int, keyName:String) {
		
			if (photos[keyName] == undefined) {

				photos[keyName] = contentData;
				switch (windowMode) {
				
					case SceneImages.MODE_WINDOW:
					loader.add(OutsideProperties.basePath+contentData["medium"], {id:keyName});				
					break;
					
					case SceneImages.MODE_FULLSCREEN:
					loader.add(OutsideProperties.basePath+contentData["photo"], {id:keyName});				
					break;
												
				}
		        
		        loader.start();

			}
	        
		}
		
		public function itemLoaded(e:ImageLoadedEvent) {
			var evt:ItemEvent = new ItemEvent(ItemEvent.ITEM_LOADED);
			photos[e.item.id].bitmap = e.item["bitmap"];
			photos[e.item.id].keyname = e.item.id;
			evt.item = photos[e.item.id];
			evt.content_type = "image";
			dispatchEvent(evt);
		}

		public function itemFailed(e:ImageLoadedEvent) {
			var evt:ItemEvent = new ItemEvent(ItemEvent.ITEM_FAILED);
			evt.item = photos[e.item.id];
			evt.content_type = "image";
			dispatchEvent(evt);
		}
		
		public function get totalLoading():int {
			return _totalLoading;
		}	
				
    }
    
}


