package ro.nouauzina.scenes {

	import flash.external.ExternalInterface;
	
	import flash.events.*;
	import flash.display.*;
	import ro.nouauzina.utils.*;
	import ro.nouauzina.windows.*;
 	import ro.nouauzina.events.*;
	import ro.nouauzina.remoting.*;
 	import ro.nouauzina.data.*;
	import ro.nouauzina.controls.*;
	
	import gs.*;
	import gs.easing.*;
	
	import flash.text.*;

	public class SceneImages extends Sprite {
	
		private var holder:Sprite;
		
		private var textBackground		:MovieClip;
		private var imageName			:MovieClip;
		private var imageDescription	:MovieClip;
		private var textHolder			:Sprite;
		
		private var nextArrow			:MovieClip;
		private var prevArrow			:MovieClip;
		
		private var currentItem			:int;
		private var imageArray			:Array;

		private var dataLoader			: DataTransport;
		private var contentLoader		: LazyContentLoader;
				
		private var orderHolder			: Sprite;
		
		private var loadStatus			: MovieClip;
		private var debug				: MovieClip;
		private var totalPhotos			: int = 0;
		
		public static var MODE_WINDOW			:int = 1;
		public static var MODE_FULLSCREEN		:int = 2;
		private var windowMode			: int;
		private var resources			: Array;
		private var direction			: int 	= 1;
		private var preloadAmount		: int 	= 1;
		private var loader				: MovieClip;
		private var startWithPhotoID	: int   = 0;
		private var userID				: int   = -1;
		private var favoriteByUserID	: int   = -1;
			
		public function SceneImages()
		{
			init();
			addNavigation();
			OutsideProperties.stage.addEventListener(Event.FULLSCREEN, switchResolutions);			
		}
		
		public function setFirstPhoto(photoID) {
			startWithPhotoID = photoID;
		}

		public function setUserID(theUserID) {
			userID = theUserID;
		}
		
		public function setFavoriteByUserID(uid) {
			favoriteByUserID = uid;
		}
		
		public function getPhotosForDestination(destinationID:String) {
			totalPhotos = 0;
			dataLoader.purpose = "photos";
			dataLoader.service = "PhotoService.getPhotosByDestination";
			dataLoader.setParameter("id",destinationID);
			if (favoriteByUserID > 0) dataLoader.setParameter("favuser",favoriteByUserID);
			dataLoader.getData()
		}		
		
		public function getPhotosForSet(setID:String) {
			totalPhotos = 0;
			dataLoader.purpose = "photos";
			dataLoader.service = "PhotoService.getPhotosBySet";
			dataLoader.setParameter("id",setID);
			dataLoader.getData()
		}			
		
		public function destroy() {
			var i:Number;
			
			while(holder.numChildren)
			{
			    holder.removeChildAt(0);
			}			

			while(orderHolder.numChildren)
			{
			    orderHolder.removeChildAt(0);
			}			
			
			contentLoader.removeCurrentQueue();
			imageArray = [];
			currentItem = -1;
			totalPhotos = 0;
			positionControls();
		}
		
		private function init() {
		
			holder = new Sprite();
			addChild(holder);
			
			orderHolder = new Sprite();
			addChild(orderHolder);
			
			imageArray = new Array();
			currentItem = -1;
			windowMode = SceneImages.MODE_WINDOW;
			resources = [];
			
			debug = new SmallestText();
			debug.tfText.text = "";
			debug.tfText.autoSize = TextFieldAutoSize.LEFT;
			debug.x = 50;
			debug.y = 20;
			//addChild(debug);


			loader = new LoadAnim();
			addChild(loader);
			loader.width = 40;
			loader.height = 40;


			dataLoader = new DataTransport();
			dataLoader.addEventListener(RemoteEvent.GREAT_SUCCESS, onDataSuccess);
			dataLoader.addEventListener(RemoteEvent.FAILURE, onDataFailure);

			contentLoader = new LazyContentLoader();
			contentLoader.addEventListener(ItemEvent.ITEM_LOADED,addPhoto);
			contentLoader.addEventListener(ItemEvent.ITEM_FAILED,loadPhotoFailed);

		}
				
		private function onDataSuccess(e:RemoteEvent) {
			switch (e.purpose) {
				case "photos":
								var photos:Array = e.params as Array;
								contentLoader.removeCurrentQueue();
								resources = photos.slice();
								trimPhotosByUserID();
								var photoIndex = getPhotoIndex(startWithPhotoID);
								if (photoIndex >= 0) {
									currentItem = photoIndex;
								} else {
									currentItem = 0;
								}
								preload();
								break;
			}
		}
		
		public function trimPhotosByUserID() {
			if (userID > 0) {
				for (var i=resources.length-1; i>=0; i--) {
					if (resources[i]["user_id"] != userID) resources.splice(i,1);
				}
			}
		}
		
		public function preload() {
			for (var i:int = 0; i<preloadAmount; i++) {
				if (direction == 1) {
					if (resources[currentItem+i] != undefined) {
						contentLoader.queue(resources[currentItem+i], windowMode, getKeyName(resources[currentItem+i]));
					}
				} else {
					if (resources[currentItem-i] != undefined) {
						contentLoader.queue(resources[currentItem-i], windowMode, getKeyName(resources[currentItem+i]));
					}
				}
			}
		}
		
		public function d(str:String) {
			debug.tfText.text = str;
		}
		
		public function showPhoto(photoData:Array) {
			var actualPhoto = holder.getChildAt(holder.numChildren-1);
			var nextPhoto:DisplayObject = holder.getChildByName(getKeyName(photoData));
			d(getKeyName(photoData));
			currentItem = getPhotoIndex(photoData["id"]);
			if (nextPhoto != null) {
				actualPhoto.visible = false;
				loader.visible = false;
				nextPhoto.alpha = 0;
				nextPhoto.visible = true;
				//holder.setChildIndex(actualPhoto,0);
				holder.setChildIndex(nextPhoto,holder.numChildren-1);
				loadStatus.tfText.text = "Photo "+(currentItem+1) + "/" + resources.length;
				adjustSizeToStage();
				//TweenLite.to(actualPhoto, 1, {alpha:0, ease:Strong.easeOut});
				TweenLite.to(nextPhoto, 1, {alpha:1, ease:Strong.easeOut});
				ExternalInterface.call("photoActive", photoData["id"], photoData["photo"], photoData["favorite"]);
				ExternalInterface.addCallback("setFlashFavorite", flashFavorite);				
			} else {
				loader.visible = true;
				preload();
			}
		}
		
		public function flashFavorite(photoID:int, fav:String) {
			var itemIndex:int = getPhotoIndex(photoID);
			debug.tfText.text = itemIndex;
			resources[itemIndex]["favorite"] = fav;
		}
		
		public function switchResolutions(e:Event) {
			if (OutsideProperties.stage.displayState == StageDisplayState.NORMAL) {
				windowMode = SceneImages.MODE_WINDOW;
			} else {
				windowMode = SceneImages.MODE_FULLSCREEN;
			}
			showPhoto(resources[currentItem]);
		}
		
		public function hidePhoto(photo:DisplayObject) {
			photo.visible = false;
		}
		
		public function getPhotoIndex(id:int) {
			for (var i=0;i<resources.length;i++) {
				if (resources[i]["id"] == id) return i;
			}
			return -1;
		}
		
		public function getKeyName(photoData:Array) {
			return "image_"+photoData["id"]+"_"+windowMode;
		}
		
		private function onDataFailure(e:RemoteEvent) {
			trace("fail: "+e.purpose);
		}		
				
		public function addPhoto(e:ItemEvent) {
			
			var photoData:Array = e.item as Array;
			var image:Loader = photoData.bitmap as Loader;
			
			image.name = photoData.keyname;
			image.visible = false;
			holder.addChildAt(image,0);
			if (currentItem == getPhotoIndex(photoData["id"])) showPhoto(photoData); 
				
		}

		public function loadPhotoFailed(e:ItemEvent) {
			var photoData:Array = e.item as Array;
			var photoIndex:int = getPhotoIndex(photoData["id"]); 
			resources.splice(photoIndex,1);
			preload();
				
		}
		
		private function addNavigation() {

			prevArrow = new PrevArrow();
			addChild(prevArrow);
			prevArrow.addEventListener(MouseEvent.CLICK, onClickPrev);
			nextArrow = new NextArrow();
			addChild(nextArrow);
			nextArrow.addEventListener(MouseEvent.CLICK, onClickNext);
			nextArrow.alpha=.3;
			prevArrow.alpha=.3;

			nextArrow.addEventListener(MouseEvent.MOUSE_OVER, onNavOver);
			nextArrow.addEventListener(MouseEvent.MOUSE_OUT, onNavOut);
			prevArrow.addEventListener(MouseEvent.MOUSE_OVER, onNavOver);
			prevArrow.addEventListener(MouseEvent.MOUSE_OUT, onNavOut);
			
			nextArrow.visible = false;
			prevArrow.visible = false;
			
			loadStatus = new SmallestText();
			loadStatus.alpha = .3;
			loadStatus.tfText.text = "";
			loadStatus.tfText.autoSize = TextFieldAutoSize.LEFT;
			loadStatus.addEventListener(MouseEvent.MOUSE_OVER, onNavOver);
			loadStatus.addEventListener(MouseEvent.MOUSE_OUT, onNavOut);
			addChild(loadStatus);

			
		}

		
		public function adjustSizeToStage() {
		
			var stageWidth:int 	= OutsideProperties.stage.stageWidth;
			var stageHeight:int = OutsideProperties.stage.stageHeight;

			var stageAspectRatio:Number = stageWidth / stageHeight;
			var imageAspectRatio:Number;
			var newImageWidth:Number;
			var newImageHeight:Number;

			if (holder.numChildren > 0) {
				var child:DisplayObject = holder.getChildAt(holder.numChildren-1);
				imageAspectRatio = child.width / child.height;
				
				if (imageAspectRatio > stageAspectRatio) {
					newImageWidth 	= stageWidth;
					newImageHeight 	= (newImageWidth / imageAspectRatio);
				} else {
					newImageHeight 	= stageHeight;
					newImageWidth 	= newImageHeight * imageAspectRatio;
				}
				
				child.width 	= newImageWidth;
				child.height 	= newImageHeight;
				child.x = (stageWidth-newImageWidth)/2;
				child.y = (stageHeight-newImageHeight)/2;
			}			

			positionControls();			
			
		}
		
		private function positionControls() {
			
			var i:Number = 0;
			var hierarchy:Array = new Array();

			loader.x = (OutsideProperties.stage.stageWidth - loader.width)/2 + loader.width/2;
			loader.y = (OutsideProperties.stage.stageHeight - loader.height)/2 + loader.height/2;


			loadStatus.x = 	OutsideProperties.stage.stageWidth - 50 - loadStatus.width - 5;	
			loadStatus.y = 	OutsideProperties.stage.stageHeight - 47;	


			if (resources.length > 0) {
			
				nextArrow.visible 		= true;
				prevArrow.visible 		= true;
				prevArrow.x = 5;
				prevArrow.y = (OutsideProperties.stage.stageHeight-prevArrow.height)/2;
				nextArrow.x = OutsideProperties.stage.stageWidth - nextArrow.width-5;
				nextArrow.y = (OutsideProperties.stage.stageHeight-prevArrow.height)/2;
				
				nextArrow.y = prevArrow.y = loadStatus.y;
				nextArrow.x = loadStatus.x - nextArrow.width - 10;
				prevArrow.x = nextArrow.x - prevArrow.width - 10;


			} else {
				nextArrow.visible 		= false;
				prevArrow.visible 		= false;			
			}
			


		}
		
		function onNavOver(e:MouseEvent) {
			e.currentTarget.alpha = 1;
		}
		function onNavOut(e:MouseEvent) {
			e.currentTarget.alpha = .3;
		}

		
		private function onClickNext(e:MouseEvent) {
			direction = 1;
			var nextItem = currentItem + 1;
			if (nextItem >= resources.length) nextItem = resources.length-1;//0;
			if (nextItem < resources.length) {
				showPhoto(resources[nextItem]);
			}
			positionControls();
		}

		private function onClickPrev(e:MouseEvent) {
			direction = 0;
			var prevItem = currentItem - 1;
			if (prevItem < 0) prevItem = 0;//imageArray.length-1;
			if (prevItem >= 0) {
				showPhoto(resources[prevItem]);
			}
			positionControls();
			
		}

		private function elementOver(event:MouseEvent) {
			event.currentTarget.alpha=.5;
			
		}
		private function elementOut(event:MouseEvent) {
			event.currentTarget.alpha = 1;
		}

	}
}
