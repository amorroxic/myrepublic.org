package ro.nouauzina.windows {
	
	import flash.events.*;
	import flash.display.*;
	import ro.nouauzina.utils.*;
	
	import gs.*;
	import gs.easing.*;
	
	import flash.text.*;

	public class FlashMessage extends Sprite {
	
		private var holder	:MovieClip;
		
		private var message	:String;
		private var delay	:Number;
	
		private var timer	:ExtendedTimer;
		
		public function FlashMessage(params:Object)
		{
		
			holder = new MovieClip();
			
			if (params.message == undefined) return;
			message = params.message;
			if (params.delay != undefined) {
				delay = params.delay;
			} else {
				delay = 1;
			}

			createBox();
			setupTimer();
			
			OutsideProperties.stage.addChild(holder);

		}
		
		private function createBox() {

			var back = new TextBackground();
			holder.addChild(back);

			var tf:TextFormat = new TextFormat();
			tf.size = 16;
			var theText:MovieClip = new SmallText();
			theText.tfText.textColor = 0xff3399;
			theText.tfText.text = message;
			theText.tfText.autoSize = TextFieldAutoSize.LEFT;
			theText.x = Math.round((OutsideProperties.stage.stageWidth - theText.tfText.textWidth)/2);
			theText.y = Math.round((OutsideProperties.stage.stageHeight - theText.tfText.height)/2);
			holder.addChild(theText);

			back.width = theText.width + 30;
			back.height = theText.height + 30;
			back.x = Math.round((OutsideProperties.stage.stageWidth - back.width)/2);
			back.y = Math.round((OutsideProperties.stage.stageHeight - back.height)/2);
			
		}
		
		private function setupTimer() {
			timer = new ExtendedTimer(delay * 1000);
			timer.reset();
			timer.repeatCount = 1;
			timer.pause()
			timer.addEventListener("timer",timeEvent)
			timer.start();				
		}
		
		private function timeEvent(event:TimerEvent) {
			TweenLite.to(holder, 1, {alpha:0, ease:Strong.easeOut, onComplete: removeWindow});
		}
		
		private function removeWindow() {
			OutsideProperties.stage.removeChild(holder);
			delete this;
		}
				
	}
}
