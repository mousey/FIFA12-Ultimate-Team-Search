<?PHP
/**
* @llygoden
* @author - Rob McGhee
* @URL - www.robmcghee.com
* @date - 29/05/12
* @version - 1.0
**/
class Searchor {
	
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
	
	public function playersearch($start = 0,$count = 15,$level,$formation,$position,$nationality,$league,$team,$minBid,$maxBid,$minBIN,$maxBIN){
		//URL to search for items
		$searchurl = "https://ut.fut.ea.com/ut/game/ut12/auctionhouse?";
		//String that holds our search variables
		$searchstring = "";
		
		//Add to the search string based on the variables passed
		if ($level != "" && $level != "any"){
			$searchstring .= "&lev=$level";
		}
		
		if ($formation != "" && $formation != "any"){
			$searchstring .= "&form=$formation";
		}
		
		if ($position != "" && $position != "any"){
			if ($position == "defense" || $position == "midfield" || $position == "attacker"){
				$searchstring .= "&zone=$position";
			}else{
				$searchstring .= "&pos=$position";
			}
		}
		
		if ($nationality > 0){
			$searchstring .= "&nat=$nationality";
		}
		
		if ($league > 0){
			$searchstring .= "&leag=$league";
		}
		
		if ($team > 0){
			$searchstring .= "&team=$team";
		}
		
		if ($minBid > 0){
			$searchstring .= "&micr=$minBid";
		}
		
		if ($maxBid > 0){
			$searchstring .= "&macr=$maxBid";
		}
		
		if ($minBIN > 0){
			$searchstring .= "&minb=$minBid";
		}
		
		if ($maxBIN > 0){
			$searchstring .= "&maxb=$maxBid";
		}
		
		//HTML Headers to send to the search URL, includes 3 keys and the XSID
		$opts = array(
			'http'=>array(
			'method'=>"POST",
			'header'=>"Content-Type: application/json\r\n".
					  "Cookie: ".$this->EASW_KEY."; ".$this->EASF_SESS ."; ".$this->PHISHKEY."\r\n".
					  "x-http-method-override:GET\r\n".
					  $this->XSID
			)
		);
		
		$context = stream_context_create($opts);
		//create the final search string
		$search = $searchurl . "type=player&start=$start&num=$count" . $searchstring;
		//Contains the JSON file returned from EA
		$EAPSEARCH = file_get_contents($search, false, $context);
		
		unset ($start,$count,$level,$formation,$position,$nationality,$league,$team,$minBid,$maxBid,$minBIN,$maxBIN, $opts, $context, $search, $searchstring);
		
		return $EAPSEARCH;
	}
	
	public function staffsearch($start = 0,$count = 15, $level, $cat, $minBid, $maxBid, $minBIN, $maxBIN){
		//URL to search for items
		$searchurl = "https://ut.fut.ea.com/ut/game/ut12/auctionhouse?";
		//String that holds our search variables
		$searchstring = "";
		
		//Add to the search string based on the variables passed
		if ($level != "" && $level != "any"){
			$searchstring .= "&lev=$level";
		}
		
		if ($cat != "" && $cat != "any"){
			$searchstring .= "&cat=$cat";
		}
		
		if ($minBid > 0){
			$searchstring .= "&micr=$minBid";
		}
		
		if ($maxBid > 0){
			$searchstring .= "&macr=$maxBid";
		}
		
		if ($minBIN > 0){
			$searchstring .= "&minb=$minBid";
		}
		
		if ($maxBIN > 0){
			$searchstring .= "&maxb=$maxBid";
		}
		
		//HTML Headers to send to the search URL, includes 3 keys and the XSID
		$opts = array(
			'http'=>array(
			'method'=>"POST",
			'header'=>"Content-Type: application/json\r\n".
					  "Cookie: ".$this->EASW_KEY."; ".$this->EASF_SESS ."; ".$this->PHISHKEY."\r\n".
					  "x-http-method-override:GET\r\n".
					  $this->XSID
			)
		);
		
		$context = stream_context_create($opts);
		//create the final search string
		$search = $searchurl . "type=staff&blank=10&start=$start&num=$count" . $searchstring;
		//Contains the JSON file returned from EA
		$EASSEARCH = file_get_contents($search, false, $context);
		
		unset ($start,$count,$level,$cat,$minBid,$maxBid,$minBIN,$maxBIN, $opts, $context, $search, $searchstring);
		
		return $EASSEARCH;
	}
}
?>