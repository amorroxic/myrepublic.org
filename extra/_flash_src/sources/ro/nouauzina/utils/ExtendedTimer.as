package ro.nouauzina.utils 
{
	import flash.events.TimerEvent;	
	import flash.utils.Timer;

	public class ExtendedTimer extends Timer 
	{
		private var _startTime : Number;
		private var _initialDelay : Number;
		private var _paused : Boolean = false;

		public function ExtendedTimer(delay : Number, repeatCount : int = 0)
		{
			super(delay, repeatCount);
			_initialDelay = delay;
			addEventListener(TimerEvent.TIMER, onTimer, false, 0, true);
		}

		private function onTimer(event : TimerEvent) : void
		{
			_startTime = new Date().time;
			delay = _initialDelay;
		}

		override public function start() : void 
		{
			if(currentCount < repeatCount)
			{
				_paused = false;
				_startTime = new Date().time;
				super.start();
			}
		}

		public function pause() : void 
		{
			if(running)
			{
				_paused = true;
				stop();
				delay = delay - (new Date().time - _startTime);
			}
		}

		public function get paused() : Boolean
		{
			return _paused;
		}

		public function get initialDelay() : Number
		{
			return _initialDelay;
		}
	}
}
