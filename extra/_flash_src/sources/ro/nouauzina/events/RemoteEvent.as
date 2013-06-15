package ro.nouauzina.events
{
	import flash.events.Event;
	import flash.display.*;
	
	public class RemoteEvent extends Event
	{
		
		private var _purpose	:String;
		private var _params		:*;
		
		public static var GREAT_SUCCESS = "remote_success";
		public static var FAILURE = "remote_failure";
		
		public function RemoteEvent( type:String )
		{
			super( type );
		}
		
		public function set purpose(value:String):void
		{
			_purpose = value;
		}
		
		public function get purpose():String
		{
			return(_purpose);
		}
		
		public function set params(value:*) {
			_params = value;
		}

		public function get params():* {
			return (_params);
		}

		
	}
}