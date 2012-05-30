<?PHP
/**
* @llygoden
* @author - Rob McGhee
* @URL - www.robmcghee.com
* @date - 30/05/12
* @version - 1.0
**/

//returns the base ID of a player from the resource ID provided
public function baseID($resourceid){
	$rid = $resourceid;
	$version = 0;
	
	WHILE ($rid > 16777216){
		$version++;
		if ($version == 1){
			//the constant applied to all items
			$rid -= 1073741824;
		}elseif ($version == 2){
			//the value added to the first updated version
			$rid -= 50331648;
		}else{
			//the value added on all subsequent versions
			$rid -= 16777216;
		}
	}
	
	$returnable = array('baseID'=>$rid,'version'=>$version);
	return $returnable;
}

//returns the JSON file containing the players information
public function playerinfo($baseID){
	$playerurl = "http://cdn.content.easports.com/fifa/fltOnlineAssets/2012/fut/items/web/". $baseID .".json";
	$EAPLAYER = file_get_contents($playerurl, false);
	
	return $EAPLAYER;
}

//returns the URL of the players image
public function playerimage($baseID){
	$EAPIMAGE = "http://cdn.content.easports.com/fifa/fltOnlineAssets/2010/fut/items/images/players/web/". $baseID .".png";
	
	return $EAPIMAGE;
}

//returns the JSON file containing the managers information
public function managerinfo($assetID){
	$managerurl = "http://cdn.content.easports.com/fifa/fltOnlineAssets/2012/fut/items/web/". $assetID .".json";
	$EAMANAGER = file_get_contents($managerurl, false);
	
	return $EAMANAGER;
}

//returns the URL of the managers image
public function managerimage($assetID){
	$EAMIMAGE = "http://cdn.content.easports.com/fifa/fltOnlineAssets/2012/fut/items/images/players/web/heads_staff_". $assetID .".png";
	
	return $EAMIMAGE;
}

//returns the JSON file containing how many coins your account has to spend	
public function credits($EASW_KEY, $EASF_SESS, $PHISHKEY, $XSID){
	//URL to retrieve credits
	$creditsurl = "https://ut.fut.ea.com/ut/game/ut12/user/credits";
	
	//HTML Headers to send to the credits URL, includes 3 keys and the XSID
	$opts = array(
		'http'=>array(
		'method'=>"POST",
		'header'=>"Content-Type: application/json\r\n".
				  "Cookie: ".$EASW_KEY."; ".$EASF_SESS ."; ".$PHISHKEY."\r\n".
				  "x-http-method-override:GET\r\n".
				  $XSID
		)
	);
	
	$context = stream_context_create($opts);
	//Contains the JSON file returned from EA
	$EACREDITS = file_get_contents($creditsurl, false, $context);
	
	unset ($opts, $context, $trade, $tradeurl, $value);
	
	return $EACREDITS;
}

?>