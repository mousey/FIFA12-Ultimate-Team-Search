<?PHP
/**
* @llygoden
* @author - Rob McGhee
* @URL - www.robmcghee.com
* @date - 30/05/12
* @version - 1.0
**/
class Tradeor {
	
	private $EASW_KEY;
	private $EASF_SESS;
	private $PHISHKEY;
	private $XSID;
	
	//initialise the class
	public function __construct($EASW_KEY, $EASF_SESS, $PHISHKEY, $XSID) {
		$this->EASW_KEY 	= $EASW_KEY;
		$this->EASF_SESS 	= $EASF_SESS;
		$this->PHISHKEY 	= $PHISHKEY;
		$this->XSID 		= $XSID;
	}
	
	//$trade is the tradeID for the item you want
	//$value is the value in FIFA coins that you want to BID
	public function bid($trade, $value){
		//URL to bid on trade items
		$bidurl = "https://ut.fut.ea.com/ut/game/ut12/trade/". $trade ."/bid";
		
		//HTML Headers to send to the bid URL, includes 3 keys and the XSID
		$opts = array(
			'http'=>array(
			'method'=>"POST",
			'header'=>"Content-Type: application/json\r\n".
					  "Cookie: ".$this->EASW_KEY."; ".$this->EASF_SESS ."; ".$this->PHISHKEY."\r\n".
					  "x-http-method-override:PUT\r\n".
					  $this->XSID,
			'content'=>'{ "bid": '. $value .'}'
			)
		);
		
		$context = stream_context_create($opts);
		//Contains the JSON file returned from EA
		$EABID = file_get_contents($bidurl, false, $context);
		
		unset ($opts, $context, $trade, $bidurl, $value);
		
		return $EABID;
	}
	
	public function trade($trade){
		//URL to view trade details
		$tradeurl = "https://ut.fut.ea.com/ut/game/ut12/trade?tradeIds=". $trade;
		
		//HTML Headers to send to the trade URL, includes 3 keys and the XSID
		$opts = array(
			'http'=>array(
			'method'=>"POST",
			'header'=>"Content-Type: application/json\r\n".
					  "Cookie: ".$this->EASW_KEY."; ".$this->EASF_SESS ."; ".$this->PHISHKEY."\r\n".
					  "x-http-method-override:GET\r\n".
					  $this->XSID,
		  )
		);

		$context = stream_context_create($opts);
		//Contains the JSON file returned from EA
		$EATRADE = file_get_contents($tradeurl, false, $context);

		unset($opts, $context, $trade, $tradeurl);
		
		return $EATRADE;
	}
}
?>