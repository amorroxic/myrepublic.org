package {
	
	import flash.events.*;
	import flash.display.*;
 	
 	import ro.nouauzina.data.*;
 	import ro.nouauzina.utils.*;
 	import ro.nouauzina.scenes.*;
 	import ro.nouauzina.events.*;
 	
	import ro.nouauzina.windows.*;
	import ro.nouauzina.remoting.*;
	
	import gs.*;
	import gs.easing.*;
 	

	public class Cities extends MovieClip {
			
		private var sceneImages		: SceneImages;
		private var sceneFullScreen	: SceneFullScreen;
		
		public static var MODE_PHOTOSET			: String = "1";
		public static var MODE_DESTINATION		: String = "2";
		public static var MODE_USERPHOTOS		: String = "3";
		
		private var operationMode				: String;

		private var _currentScene	: *;
		
		public function Cities()
		{
		
			stage.align = StageAlign.TOP_LEFT;
			stage.scaleMode = StageScaleMode.NO_SCALE;
			OutsideProperties.stage = stage;
			prepareScenes();
			stage.addEventListener(Event.RESIZE, resizeHandler);
			
			var paramObj:Object = LoaderInfo(this.root.loaderInfo).parameters;

			operationMode = Cities.MODE_DESTINATION;

			var setID:String = paramObj["set"];
			if (setID != null) operationMode = Cities.MODE_PHOTOSET;

			sceneImages.destroy();
			
			switch (operationMode) {
				case Cities.MODE_DESTINATION:

								var destinationID:String = paramObj["did"];
								if (destinationID == null) destinationID = "78";
					
								var photoID:String = paramObj["pid"];
								if (photoID == null) photoID = "0";
					
								var userID:String = paramObj["uid"];
								var favByUserID:String = paramObj["fid"];

								sceneImages.setFirstPhoto(photoID);
								if (userID != null) sceneImages.setUserID(userID);
								if (favByUserID != null) sceneImages.setFavoriteByUserID(favByUserID);
								sceneImages.getPhotosForDestination(destinationID);

								break;
								
				case Cities.MODE_PHOTOSET:
								sceneImages.getPhotosForSet(setID);
								break;
			}
			
			currentScene = sceneImages;
			
			
		}
		
		private function prepareScenes() {
		
			sceneImages	= new SceneImages();
			sceneImages.visible = false;
			addChild(sceneImages);

			sceneFullScreen	= new SceneFullScreen();
			addChild(sceneFullScreen);

			resizeHandler(new Event("resize"));
			currentScene = sceneImages;
			
			
		}
		
		public function set currentScene(newScene:*) {

			newScene.y = -newScene.height;
			newScene.visible = true;

			if (_currentScene != undefined) {
				TweenLite.to(_currentScene, 1, {y:OutsideProperties.stage.stageHeight, ease:Strong.easeOut,onComplete:onFinishTween, onCompleteParams:[_currentScene]});
				TweenLite.to(newScene, 1, {x:0,y:0, ease:Strong.easeOut});
			} else {
				TweenLite.to(newScene, 1, {x:0,y:0, ease:Strong.easeOut});
			}
			_currentScene = newScene;
		}
		
		private function onFinishTween(element:DisplayObject) {
			element.visible = false;
			element.y = -element.width;
		}
		
		private function resizeHandler(e:Event) {
			sceneImages.adjustSizeToStage();
			sceneFullScreen.adjustSizeToStage();
		}
						
	}
}
