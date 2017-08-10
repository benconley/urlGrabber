<?php namespace src\dao\urlGrab;

class urlGrabDAO {
private $dataPath = '../data';
public $urlArr = array();

/**********************************************************************
FUNC: getUrls()
DESC: Gets the actual array of urls and returns it to the calling method 
**********************************************************************/
public function getUrls() {
	// load files from data path
	$this->getDataFiles();
	return $this->urlArr;

}

/**********************************************************************
FUNC: getDataFiles()
DESC: Extract the urls from the files contained within the data folder
**********************************************************************/
public function getDataFiles() {

	// command line requires a different way to access the data folder
	if (isset($_SERVER["PWD"])) {
		$this->dataPath = __DIR__ . '/../data';
	}

	if ($dirhandle = opendir($this->dataPath)) {


		while (false !== ($filename = readdir($dirhandle))) {

			if ($filename != "." && $filename != "..") {
				// open paths
				if (is_readable($this->dataPath . '/' . $filename)) {
					$inStream = file_get_contents($this->dataPath . '/' . $filename);
					// if file is json, parse it
					try {
						$jsonArr = json_decode($inStream);
						if ($jsonArr !== NULL) {
							foreach($jsonArr as $url) {
								// add valid urls to the array of urls
								if (!filter_var(trim($url), FILTER_VALIDATE_URL) === false) {
									array_push($this->urlArr, trim($url));
								}
							}
						} else {
							// loop over each of the lines if this is simple text
							$lines = explode("\n", $inStream);
							foreach($lines as $url) {
								// add valid urls to the array of urls
								if (!filter_var(trim($url), FILTER_VALIDATE_URL) === false) {
									array_push($this->urlArr, trim($url));
								}
							}

						}
						
					}
					// catch any errors so that we'll get as much data as possible
					catch (Exception $e) {

					}

				}

			}	
		}	
		closedir($dirhandle);
	}
}


// end class	
}
