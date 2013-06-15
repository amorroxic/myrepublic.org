package ro.nouauzina.events
{
	import flash.events.Event;
	import flash.display.*;
	
	public class ImageLoadedEvent extends Event
	{
		
		private var _item:Array;
        public static var ITEM_LOADED : String = "image_item_loaded";
        public static var ITEM_FAILED : String = "image_item_failed";
		
		public function ImageLoadedEvent( type:String )
		{
			super( type );
		}
		
		public function set item(value:Array):void
		{
			_item = value;
		}
		
		public function get item():Array
		{
			return(_item);
		}
		
	}
}