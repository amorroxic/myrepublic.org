package ro.nouauzina.loaders {

	import flash.events.*;
	import flash.net.*;
	import ro.nouauzina.events.*;
  	import ro.nouauzina.utils.*;
  	import flash.external.ExternalInterface;
  	import flash.display.*;
  	import flash.system.*;	

	public class ImageLoader extends EventDispatcher {

		public static var COMPLETED = "all_completed";

		private var indexFileDownloading:Number;
		private var files:Array;
	
		//private var imageLoader:Loader;
		private var loaderContext:LoaderContext;
		private var _loading:Boolean;
		
		public function ImageLoader() {
		
	        files = new Array();
	        _loading = false;
	        indexFileDownloading = -1;
		}
		
		public function removeAllLoading() {
			if (indexFileDownloading<files.length && indexFileDownloading>-1) {
				files[indexFileDownloading]["bitmap"].contentLoaderInfo.removeEventListener( Event.COMPLETE, handleImageLoaded );
				files[indexFileDownloading]["bitmap"].contentLoaderInfo.removeEventListener( IOErrorEvent.IO_ERROR, handleImageFailed );
				files[indexFileDownloading]["bitmap"].unload();
			}
			indexFileDownloading = -1;
			files = [];
		}
		
		public function add(theURL:String, params:Object) {
			var file:Array = new Array();
			file["url"] 	= theURL;
			file["id"] 		= params.id;
			file["type"]	= params.fileType;
			files.push(file);
		}
		
		public function start() {
			if (!_loading) {
				_loading = true;
				indexFileDownloading = 0;
				loaderContext = new LoaderContext();
				loaderContext.checkPolicyFile = false;
				
				loadSingleImage();
			}
			
		}		
		
		private function loadSingleImage() {

			if (indexFileDownloading<files.length) {
				var imageLoader:Loader = new Loader();
				imageLoader.contentLoaderInfo.addEventListener( Event.COMPLETE, handleImageLoaded );
				imageLoader.contentLoaderInfo.addEventListener( IOErrorEvent.IO_ERROR, handleImageFailed );
				imageLoader.load( new URLRequest( files[indexFileDownloading]["url"] ), loaderContext );
				files[indexFileDownloading]["bitmap"] = imageLoader;
			} else {
				finishedLoading();
			}
				
		}
		

		private function handleImageLoaded(e:Event) {

        	//var bmd = Bitmap(e.currentTarget.content).bitmapData;
			//var tmpBmp:BitmapData = bmd.clone();
			//imageLoader.unload();
			//var a:Bitmap = new Bitmap(tmpBmp, PixelSnapping.AUTO, true);
			//files[indexFileDownloading]["bitmap"] = a;
			var event:ImageLoadedEvent = new ImageLoadedEvent(ImageLoadedEvent.ITEM_LOADED);
			event.item = files[indexFileDownloading];
			dispatchEvent(event);
			
			if (indexFileDownloading < files.length-1) {
				indexFileDownloading++;
				loadSingleImage();

			} else {
				finishedLoading();
			}

		}
				
		private function handleImageFailed(e:Event) {
			if (indexFileDownloading < files.length) {

				var event:ImageLoadedEvent = new ImageLoadedEvent(ImageLoadedEvent.ITEM_FAILED);
				event.item = files[indexFileDownloading];
				dispatchEvent(event);

				indexFileDownloading++;
				loadSingleImage();

			} else {
				finishedLoading();
			}

		}				
				
		private function finishedLoading() {
			_loading = false;
			var event:Event = new Event(ImageLoader.COMPLETED);
			dispatchEvent(event);

		}
		
	    			
	}
	

}