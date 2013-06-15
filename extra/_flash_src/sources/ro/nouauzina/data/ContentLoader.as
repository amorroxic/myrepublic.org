package ro.nouauzina.data
{	
	
  	import flash.events.*;
  	import flash.net.*;	
    import br.com.stimuli.loading.BulkLoader;
    import br.com.stimuli.loading.BulkProgressEvent;
  	import ro.nouauzina.events.*;	
  	import ro.nouauzina.utils.*;	
  	import ro.nouauzina.loaders.*;	

  public class ContentLoader extends EventDispatcher
  {
        //public var loader : BulkLoader;
        public var loader : ImageLoader;
        private var photos : Array;
        private var thumbs : Array;
        private var _currentLoading: int;
        private var _totalLoading: int;
			
		public function ContentLoader() {			
	      //loader = new BulkLoader("main-site");
	      //loader.logLevel = BulkLoader.LOG_SILENT;
	      loader = new ImageLoader();
	      _currentLoading = 0;
	      _totalLoading = 0;
		}
		
		public function removeCurrentQueue() {
			photos = [];
			thumbs = [];
			_currentLoading = 0;
		    _totalLoading = 0;
		  	loader.removeAllLoading();
		}
		
		public function loadContent(contentData:Array) {
			photos = new Array();
			thumbs = new Array();
			var i:int;
	        _totalLoading = contentData.length;

			for (i=0; i<contentData.length; i++) {
				var photoInfo:Array = contentData[i] as Array;
				photos["image"+i] = photoInfo;
				thumbs["thumb"+i] = photoInfo;
		        //loader.add(OutsideProperties.basePath + photoInfo["thumb"], {id:"thumb"+i,fileType:"thumb"});
		        loader.add(OutsideProperties.basePath + photoInfo["photo"], {id:"image"+i,fileType:"image"});
				//loader.get("image"+i).addEventListener(Event.COMPLETE, itemLoaded);
			}
	        //loader.addEventListener(BulkLoader.COMPLETE, contentLoaded);
	        //loader.addEventListener(ImageLoader.COMPLETED, contentLoaded);
	        loader.addEventListener(ImageLoadedEvent.ITEM_LOADED, itemLoaded);
	        loader.start();
		}
		
		public function contentLoaded(e:BulkProgressEvent) {
		}
		
		/*
		public function itemLoaded(e:Event) {
			var evt:ItemEvent = new ItemEvent(ItemEvent.ITEM_LOADED);
			photos[e.target.id].bitmap = loader.getBitmap(e.target.id);
			evt.item = photos[e.target.id];
			dispatchEvent(evt);
		}*/
		public function itemLoaded(e:ImageLoadedEvent) {
			var evt:ItemEvent = new ItemEvent(ItemEvent.ITEM_LOADED);
			//photos[e.item.id].bitmap = loader.getBitmap(e.target.id);
			//evt.item = photos[e.target.id];
			if (e.item["type"] == "thumb") {
				thumbs[e.item.id].thumb = e.item["bitmap"];
				evt.item = thumbs[e.item.id];
			} else {
				photos[e.item.id].bitmap = e.item["bitmap"];
				evt.item = photos[e.item.id];
				_currentLoading++;
			}
			evt.content_type = e.item["type"];
			dispatchEvent(evt);
		}
		
		public function get totalLoading():int {
			return _totalLoading;
		}	
		public function get currentLoading():int {
			return _currentLoading;
		}	
				
    }
    
}


