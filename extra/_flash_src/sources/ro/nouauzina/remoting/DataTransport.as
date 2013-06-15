package ro.nouauzina.remoting {

	import flash.events.*;
	import flash.net.*;
	import ro.nouauzina.events.*;
  	import ro.nouauzina.utils.*;	

	public class DataTransport extends EventDispatcher {

		private var serviceName:String = "";
		private var _purpose:String = "";
		
		private var nc:NetConnection;
		private var parameters:Array;
		private var responder:Responder;
	
		public function DataTransport() {
		
			nc = new NetConnection();
			responder = new Responder(onResult,onError);
			nc.connect(OutsideProperties.basePath + "/flash/amf");
			nc.addEventListener(NetStatusEvent.NET_STATUS, onStatus);
			parameters = new Array();
					
		}
		
		public function set service(value:String) {
			serviceName = value;
		}

		public function set purpose(value:String) {
			_purpose = value;
		}
		
		public function setParameter(key:String, param:*) {
			parameters[key] = param;
		}
		
		public function resetParameters() {
			parameters = new Array();
		}
		
		public function getData() {
			nc.call(serviceName,responder,parameters);
		}
	
	
		function onResult(e:Object)
		{
			var a:RemoteEvent = new RemoteEvent(RemoteEvent.GREAT_SUCCESS);
			a.purpose = _purpose;
			a.params = e;
			dispatchEvent(a);
		}
		
		function onError(e:Object)
		{	
			trace("dt: "+e.description);
			var a:RemoteEvent = new RemoteEvent(RemoteEvent.FAILURE);
			a.purpose = _purpose;
			a.params = e;
			dispatchEvent(a);
		}
		function onStatus(e:NetStatusEvent) {
			if (e.info.level == "error") onError(new Object());
		}		
			
	
	}
	

}