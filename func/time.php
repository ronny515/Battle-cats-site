<?
	if( !isset($time_start) )
		$time_start = microtime(true);
	//echo $time_start."s start";
		
function exc_time($stime) {
	if(!isset($stime))
		return "no start time";
	else{
		$time_end = microtime(true);
		$time = ($time_end - $stime)/1000;
		//echo $time."s";
		}
	 return $time;
	}
	
	
?>