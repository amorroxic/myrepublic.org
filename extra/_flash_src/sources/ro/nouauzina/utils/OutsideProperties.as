package ro.nouauzina.utils {

	/**
	 * Noua Uzina 2008
	 */

	import flash.display.Stage;
 	
	public class OutsideProperties {
			
		private static var _stage: Stage;
		private static var _authStatus: Boolean = false;
		private static var _basePath: String = "http://www.myrepublic.org";
		private static var _session: String = "";
		
		public static function set stage(thestage:Stage) {
			OutsideProperties._stage = thestage;
		}
		
		public static function get stage():Stage {
			return OutsideProperties._stage;
		}

		public static function set authStatus(value:Boolean) {
			OutsideProperties._authStatus = value;
		}
		
		public static function get authStatus():Boolean {
			return OutsideProperties._authStatus;
		}

		public static function set basePath(value:String) {
			OutsideProperties._basePath = value;
		}
		
		public static function get basePath():String {
			return OutsideProperties._basePath;
		}
		
		public static function set session(value:String) {
			OutsideProperties._session = value;
		}
		
		public static function get session():String {
			return OutsideProperties._session;
		}

							
	}

}