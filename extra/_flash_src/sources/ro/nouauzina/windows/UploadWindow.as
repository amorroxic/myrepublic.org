package ro.nouauzina.windows {
	
	import flash.events.*;
	import flash.display.*;
	import ro.nouauzina.utils.*;
	import ro.nouauzina.events.*;
	import ro.nouauzina.remoting.*;
	import com.bit101.components.*;
	import com.gsolo.encryption.*;
	import flash.geom.ColorTransform;	
	
	import gs.*;
	import gs.easing.*;
	
	import flash.text.*;

	public class UploadWindow extends Sprite {
	
		private var holder	:MovieClip;
		private var delay	:Number;
		
		private var locationText	:InputText;
		private var countryText		:InputText;
		private var pushbutton		:PushButton;
		private var progress		:ProgressBar;
		
		public function UploadWindow()
		{
		
			holder = new MovieClip();
			holder.name = "appwindow";
			holder.x = -500;
			createBox();
			OutsideProperties.stage.addChild(holder);

		}
		
		private function createBox() {

			var back = new TextBackground();
			holder.addChild(back);

			back.width = 220;
			back.height = 120;

			var label:Label = new Label(holder, 20, 20);
			label.text = "LOCATION";
			
			locationText = new InputText(holder, 90, 20);
			locationText.text = "";     

			var passLabel:Label = new Label(holder, 20, 50);
			passLabel.text = "COUNTRY";
			
			countryText = new InputText(holder, 90, 50);
			countryText.text = "";
			
			pushbutton = new PushButton(holder, 90, 80);
			pushbutton.label = "BROWSE FILES";
			pushbutton.width = 100;
			pushbutton.addEventListener(MouseEvent.CLICK,onBrowseClick);
			
			progress = new ProgressBar(holder,90,80);
			progress.visible = false;

			var btnClose = new ButtonClose();
			btnClose.x = back.width - btnClose.width/2;
			btnClose.y = -btnClose.height/2;
			holder.addChild(btnClose);
			btnClose.addEventListener(MouseEvent.CLICK,onCloseWindow)
		   	btnClose.buttonMode = true;
		   	btnClose.useHandCursor = true;			
			     
			holder.y = Math.round((OutsideProperties.stage.stageHeight - holder.height)/2);
			var holderX:int =  Math.round((OutsideProperties.stage.stageWidth - holder.width)/2);
			holder.alpha = 0;
			holder.x = holderX - 150;
			TweenLite.to(holder, .5, {alpha:1,x:holderX, ease:Strong.easeOut});
			
		}
						
		private function removeWindow() {
			OutsideProperties.stage.removeChild(holder);
			delete this;
		}
		
		private function onCloseWindow(e:MouseEvent) {
			var holderX:int =  Math.round((OutsideProperties.stage.stageWidth - holder.width)/2);
			TweenLite.to(holder, .5, {x:holderX+150,alpha:0, ease:Strong.easeOut, onComplete: removeWindow});
		}
		
		private function onBrowseClick(e:MouseEvent) {
			if (fieldsCheck()) {
				var upload:FileUpload = new FileUpload(OutsideProperties.basePath + "/upload/flash/");
				upload.addParameter("location",locationText.text);
				upload.addParameter("country",countryText.text);
				upload.addEventListener(UploadEvent.FILE_UPLOADED, filesStart);
				upload.addEventListener(FileUpload.UPLOAD_COMPLETE, filesCompleted);
				upload.addEventListener(UploadEvent.FILE_UPLOADED, filesProgress);
				upload.openUploadDialog();
			}
		}
		
		private function filesStart(e:UploadEvent) {
			pushbutton.visible = false;
			progress.visible = true;
			progress.maximum = e.totalFiles;
			progress.value = e.filesUploaded;
		}
		
		private function filesCompleted(e:Event) {
			onCloseWindow(new MouseEvent(MouseEvent.CLICK));
			var a:FlashMessage = new FlashMessage({message: "Your files are uploaded", delay:2})
		}
		
		private function filesProgress(e:UploadEvent) {
			progress.value = e.filesUploaded;
		}
		
		private function fieldsCheck():Boolean {

			if (locationText.text == "") {
				showErrorMessage("Please specify the location");
				return false;
			}
			if (countryText.text == "") {
				showErrorMessage("Please specify the country");
				return false;
			}

			return true;
			
		}
		
		private function showErrorMessage(mess:String) {
			var errorHolder:MovieClip = new MovieClip();
			errorHolder.name = "errorHolder";
			
			var errorBack:MovieClip = new TextBackground();
			errorBack.width = 220;
			errorBack.height = 20;
			
			var newColorTransform:ColorTransform = errorBack.transform.colorTransform;
			newColorTransform.color = 0xff3366;
			errorBack.transform.colorTransform = newColorTransform;
			
			errorHolder.addChild(errorBack);
			
			var label:Label = new Label(errorHolder, 15, 1);
			label.text = mess.toUpperCase();
			
			errorHolder.alpha = 0;
			errorHolder.y = 120;
			
			holder.addChild(errorHolder);

			TweenLite.to(errorHolder, 2, {alpha:1, ease:Strong.easeOut, onComplete: removeErrorHolder});

		}
		
		private function removeErrorHolder() {
			holder.removeChild(holder.getChildByName("errorHolder"));
			delete this;
		}
		
				
	}
}
