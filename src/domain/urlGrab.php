<?php namespace src\domain\urlGrab;

use src\dao\urlGrab\urlGrabDAO as urlGrabDAO;

// start class urlGrabDomain
class urlGrabDomain {

public $urlArr = [];
public $urlContentArr = [];
public $feedbackArr = [];

/**********************************************************************
FUNC: execGrab()
DESC: Gathers information about the urls from the array provided 
**********************************************************************/
public function execGrab() {
	// get data
	$urlGrabDAO = new urlGrabDAO();

	$this->urlArr = $urlGrabDAO->getUrls();

	// build up the feedback info
	if ( !sizeof($this->urlArr) ) {
		array_push( $this->feedbackArr, 'We were unable to find any urls. Please enter some in text files contained under the data folder' );
	} else {
		array_push( $this->feedbackArr, 'Total number of urls provided: ' . sizeof($this->urlArr) ); 
	}
	// Loop over the urls and grab the content
	foreach ($this->urlArr as $url) {
		if ( $this->isValidUrl($url) ) {
			$this->loadUrlContent($url);
		}
	}
}

/**********************************************************************
FUNC: isValidUrl()
DESC: Gives a good faith effort to determining if a url is valid 
**********************************************************************/
public function isValidUrl($url) {
	return (!filter_var(trim($url), FILTER_VALIDATE_URL) === false);
}


/**********************************************************************
FUNC: loadUrlContent()
DESC: Gather information about a give url and it's associated assets 
**********************************************************************/
public function loadUrlContent($url) {
	$currUrlDetails['url'] = $url;
    
	// don't blow up entirely if there is a problem
	try {
		$currUrlDetails['filetype'] = '';
		$currUrlDetails['totalbytes'] = 0;
		$currUrlDetails['totalrequests'] = 1;
		$currUrlDetails['status'] ='success';
		// get the file headers
		$urlHeaders = get_headers($url);

        // attempt to find the filetype and size
		foreach($urlHeaders as $headerInfo) {
			$keyArr = explode(':', $headerInfo);
			
			// get filetype
			if ( stristr( trim(strtolower( $keyArr[0] )), 'content-type' ) ) {
				$currUrlDetails['filetype'] = trim(strtolower($keyArr[1]));
			// get filesize
			} else if ( stristr( trim(strtolower( $keyArr[0] )), 'content-length' ) ) {
				$currUrlDetails['totalbytes'] = intval( $keyArr[1] );
			}

			// if we have a text file, we need to parse it for assets
			if ( stristr( $currUrlDetails['filetype'], 'text' ) ) {

				// get the content of the actual text file
				
				//$urlContent = file_get_contents($url);
				$urlContent = $this->curlRetrieve($url);

				// parse text file for any links
				$regex = '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';
				preg_match_all($regex, $urlContent, $matches);
				$urls = $matches[0];
				// if this contains additional assets, get some details
				if ( sizeof($urls) ) {
					foreach($urls as $asset) {
					    // increment the count of embedded assets
					    $currUrlDetails['totalrequests']++;
					    // find the size of the individual assets

					    $currUrlDetails['totalbytes'] += mb_strlen($this->curlRetrieve($asset), '8bit');
					}
				}

			}
		}

		$currUrlDetails['totalsize'] = $this->humanReadableSize($currUrlDetails['totalbytes']);
	} catch (Exception $e) {
		$currUrlDetails['status'] ='failure';
		$currUrlDetails['details'] = $e;
	}
	array_push($this->urlContentArr, $currUrlDetails);

}

/**********************************************************************
FUNC: curlRetrieve()
DESC: Grab that junk 
**********************************************************************/
public function curlRetrieve($url) {
	$ch = curl_init();


	curl_setopt_array(
	$ch, array( 
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 2, 
        CURLOPT_TIMEOUT => 4
	));

	$content = curl_exec($ch);

	curl_close($ch);

	return $content;
}

/**********************************************************************
FUNC: humanReadableSize()
DESC: Convert total bytes into something that is a little more useful 
**********************************************************************/
public function humanReadableSize($totalBytes) {
	if ($totalBytes == 0)
		return "0.00 B";

	$s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	$e = floor(log($totalBytes, 1024));

	return round($totalBytes/pow(1024, $e), 2). ' ' . $s[$e];
}
// end class urlGrabDomain	
}
