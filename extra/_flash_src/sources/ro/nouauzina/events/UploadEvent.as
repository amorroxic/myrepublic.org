package ro.nouauzina.events
{
	import flash.events.Event;
	import flash.display.*;
	
	public class UploadEvent extends Event
	{
		
		public static var FILE_UPLOADED = "file_uploaded";
		
		private var _filesUploaded:Number;
		private var _filesError:Number;
		private var _totalFiles:Number;
		
		public function UploadEvent( type:String )
		{
			super( type );
		}
		
		public function set filesUploaded(value:Number):void
		{
			_filesUploaded = value;
		}
		
		public function get filesUploaded():Number
		{
			return(_filesUploaded);
		}
		public function set filesError(value:Number):void
		{
			_filesError = value;
		}
		
		public function get filesError():Number
		{
			return(_filesError);
		}
		public function set totalFiles(value:Number):void
		{
			_totalFiles = value;
		}
		
		public function get totalFiles():Number
		{
			return(_totalFiles);
		}
		
	}
}