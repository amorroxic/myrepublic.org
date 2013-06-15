package ro.nouauzina.scenes {
	
	import flash.events.*;
	import flash.display.*;
	import ro.nouauzina.utils.*;
	
	import gs.*;
	import gs.easing.*;
	
	import flash.text.*;

	public class SceneFullScreen extends Sprite {
	
		private var fullScreenOn:MovieClip;
		private var fullScreenOff:MovieClip;
	
		public function SceneFullScreen()
		{
		
			fullScreenOn = new FullScreenOn();
			fullScreenOn.alpha = .3;
			addChild(fullScreenOn);
		   	fullScreenOn.buttonMode = true;
		   	fullScreenOn.useHandCursor = true;			

			fullScreenOff = new FullScreenOff();				
			fullScreenOff.alpha = .3;
			fullScreenOff.visible = false;
			addChild(fullScreenOff);
		   	fullScreenOff.buttonMode = true;
		   	fullScreenOff.useHandCursor = true;			
			
			fullScreenOn.addEventListener(MouseEvent.CLICK, clickFullScreenOn);
			fullScreenOn.addEventListener(MouseEvent.MOUSE_OVER, elementOver);
			fullScreenOn.addEventListener(MouseEvent.MOUSE_OUT, elementOut);				
	
			fullScreenOff.addEventListener(MouseEvent.CLICK, clickFullScreenOff);
			fullScreenOff.addEventListener(MouseEvent.MOUSE_OVER, elementOver);
			fullScreenOff.addEventListener(MouseEvent.MOUSE_OUT, elementOut);
		
		}
		
		public function adjustSizeToStage() {
		
			var fsOnX:int = OutsideProperties.stage.stageWidth - fullScreenOn.width - 10;
			var fsOnY:int = OutsideProperties.stage.stageHeight - fullScreenOn.height - 14;
			var fsOffX:int = OutsideProperties.stage.stageWidth - fullScreenOff.width - 10;
			var fsOffY:int = OutsideProperties.stage.stageHeight - fullScreenOff.height - 14;
			
			fullScreenOn.x = fsOnX;
			fullScreenOn.y = fsOnY;
			fullScreenOff.x = fsOffX;
			fullScreenOff.y = fsOffY;
			//TweenLite.to(fullScreenOn, 1, {x:fsOnX, y:fsOnY, ease:Strong.easeOut});
			//TweenLite.to(fullScreenOff, 1, {x:fsOffX, y:fsOffY, ease:Strong.easeOut});
		}

		private function elementOver(event:MouseEvent) {
			event.currentTarget.alpha = 1;
		}
		private function elementOut(event:MouseEvent) {
			event.currentTarget.alpha = .3;
		}

		private function clickFullScreenOn(event:MouseEvent) {
			OutsideProperties.stage.displayState = StageDisplayState.FULL_SCREEN;
			fullScreenOn.visible = false;
			fullScreenOff.visible = true;
		}
		
		private function clickFullScreenOff(event:MouseEvent) {
			OutsideProperties.stage.displayState = StageDisplayState.NORMAL;
			fullScreenOff.visible = false;
			fullScreenOn.visible = true;
		}


				
	}
}
