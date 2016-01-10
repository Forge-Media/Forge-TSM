<?PHP
    //Merge nested channel info with default channel settings
	foreach ($channel_info as $key => $value) {
		$channel_info[$key] = $value + $channel_deafults; // preserves keys
		
	}
	print_r($channel_info);
	
	
?>