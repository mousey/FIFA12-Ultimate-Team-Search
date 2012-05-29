<?PHP
/**
* @llygoden
* @author - Rob McGhee
* @URL - www.robmcghee.com
* @date - 28/05/12
* @version - 1.0
**/
class Connector {
	
	private $user;
	private $password;
	private $hash;
	
	//initialise the class
	public function __construct() {
		$this->user 	= $user;
		$this->password = $password;
		$this->hash 	= $hash;
	}
	
	public function connect(){
		//EA Web App Username & Password
		//$user = "test@ea.com";
		//$password = "eaPassword";
		//Secret Question Hash Value
		//$hash = "d2d59e74d02d877ef6ae94bf868c18e0";
		//displayname for auth string
		$dispname = "bot";
		//locale for auth string
		$locale = "en-GB";
		//Time now in milliseconds
		$time = time();

		//The 4 EA URLs we need to call in this order
		$login	= "https://www.ea.com/uk/football/services/authenticate/login";
		$account= "http://www.ea.com/p/fut/a/card/l/en_GB/s/p/ut/game/ut12/user/accountinfo?timestamp=". $time;
		$auth	= "http://www.ea.com/p/fut/a/card/l/en_GB/s/p/ut/auth";
		$quest	= "http://www.ea.com/p/fut/a/card/l/en_GB/s/p/ut/game/ut12/phishing/validate";

		//HTML Headers to send to the login URL, includes Username and Password
		$opts = array(
		  'http'=>array(
			'method'=>"POST",
			'header'=>"Content-Type: application/x-www-form-urlencoded",
			'content'=>"email=".$this->user."&password=".$this->password
		  )
		);

		$context = stream_context_create($opts);
		//Contains the file returned from EA
		$EALOGIN = file_get_contents($login, false, $context);
		//The Headers returned from EA
		$r = $http_response_header;

		//EASW Key
		$s = explode(":", $r[7]);
		$t = explode(";", $s[1]);
		$EASW_KEY = $t[0]; 
		//Session Key
		$m = explode(":", $r[8]);
		$n = explode(";", $m[1]);
		$EASF_SESS = $n[0];
		//nuc
		$a = explode("<nucleusId>", $EALOGIN);
		$b = explode("</nucleusId>", $a[1]);
		$NUC = $b[0];

		//display the keys that we've found
		//echo $EASW_KEY.   "<br />";
		//echo $EASF_SESS.  "<br />";
		//echo "NUC: ".$NUC."<br />";

		//unset the variables used in this section as we will use them again
		unset($opts, $context, $EALOGIN, $http_response_header, $r, $s, $t, $m, $n, $a, $b);

		//HTML Headers to send to the account info URL, includes the two keys from above
		$opts = array(
		  'http'=>array(
			'method'=>"GET",
			'header'=>"Content-Type: application/x-www-form-urlencoded\r\n".
						"Cookie: ".$EASW_KEY.";".$EASF_SESS
		  )
		);

		$context = stream_context_create($opts);
		//Contains the file returned from EA
		$EAACCOUNT = file_get_contents($account, false, $context);
		//The Headers returned from EA
		//$r = $http_response_header;

		//Get personaID and Platform
		$d = json_decode($EAACCOUNT);
		$personaID = $d->userAccountInfo->personas[0]->personaId;
		$platform  = $d->userAccountInfo->personas[0]->userClubList[0]->platform;

		//display the variables we've got
		//echo "personaId: ".$personaID."<br />";
		//echo "platform: " .$platform. "<br />";

		//unset the variables used in this section as we will use them again
		unset($opts, $context, $EAACCOUNT, $http_response_header, $d);

		//HTML Headers to send to the auth URL, includes Both Keys and General User Info
		$opts = array(
		  'http'=>array(
			'method'=>"POST",
			'header'=>"Content-Type: application/json\r\n".
						"Cookie: ".$EASW_KEY."; ".$EASF_SESS,
			'content'=>'{ "isReadOnly": false, "sku": "499A0001", "clientVersion": 3, "nuc": '.$NUC.', "nucleusPersonaId": '.$personaID.', "nucleusPersonaDisplayName": "'.$dispname.'", "nucleusPersonaPlatform": "'.$platform.'", "locale": "'.$locale.'", "method": "idm", "priorityLevel":4, "identification": { "EASW-Token": "" } }'
		  )
		);

		$context = stream_context_create($opts);
		//Contains the file returned from EA
		$EAAUTH = file_get_contents($auth, false, $context);
		//The Headers returned from EA
		$r = $http_response_header;
		//User Session ID
		$XSID = $r[4];
		//Display the User Session ID
		//echo $XSID. "<br />";

		//unset the variables used in this section as we will use them again
		unset($opts, $context, $EAAUTH, $http_response_header, $r, $NUC, $personaID, $platform, $dispname, $locale);

		//HTML Headers to send to the Validation URL, includes Both Keys, Session ID and our Question Hash
		$opts = array(
		  'http'=>array(
			'method'=>"POST",
			'header'=>"Content-Type: application/x-www-form-urlencoded\r\n".
					  "Cookie: ".$EASW_KEY."; ".$EASF_SESS ."\r\n".
					  $XSID,
			'content'=>"answer=".$this->hash
		  )
		);

		$context = stream_context_create($opts);
		//Contains the file returned from EA
		$EAVALIDATE = file_get_contents($quest, false, $context);
		//The Headers returned from EA
		$r = $http_response_header;
		//Phishing Key
		$s = explode(":", $r[7]);
		$t = explode(";", $s[1]);
		$PHISHKEY = $t[0];
		//Display the Phishing Key 
		//echo $PHISHKEY. "<br />";

		unset($opts, $context, $EAVALIDATE, $hash, $http_response_header, $r, $s, $t);
		
		$returnitems = array('EASW_KEY' => $EASW_KEY, 'EASF_SESS' => $EASF_SESS, 'XSID' => $XSID, 'PHISHKEY' => $PHISHKE);
	}
}
?>