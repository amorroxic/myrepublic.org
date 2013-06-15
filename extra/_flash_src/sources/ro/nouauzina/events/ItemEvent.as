package ro.nouauzina.events
{
	import flash.events.Event;
	import flash.display.*;
	
	public class ItemEvent extends Event
	{
		
		private var _item:Array;
        public static var ITEM_LOADED : String = "item_loaded";
        public static var ITEM_FAILED : String = "item_failed";
        private var _type:String = "";
		
		public function ItemEvent( type:String )
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

		public function set content_type(value:String):void
		{
			_type = value;
		}
		
		public function get content_type():String
		{
			return(_type);
		}

		
	}
}