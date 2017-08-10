<?php namespace src\service\urlGrab;
    
    use src\domain\urlGrab\urlGrabDomain as urlGrabDomain;

    require_once(__DIR__ . '/../start.php');

	// setup business logic object
    $urlGrabDomain = new urlGrabDomain();

	// run process
	$urlGrabDomain->execGrab();

	$respObj = array (
		'feedback' => $urlGrabDomain->feedbackArr
		, 'urlDetails' => $urlGrabDomain->urlContentArr
	);

	// if this is called via post, it came from the web frontend
	if(isset($_GET['fromAjax']) && intval($_GET['fromAjax']) == 1) {
		header("Content-Type: application/json", true);
		echo json_encode($respObj);
	// called from command line or directly
	} else if (isset($_SERVER["PWD"])) {
		if ( isset($respObj['feedback']) ) {
			echo(chr(10).'Execution complete!'.chr(10));
			// dump feedback array
			foreach($respObj['feedback'] as $item) {
				echo($item . chr(10));
			}

			// urlDetails
			if ( isset($respObj['urlDetails']) ) {
				foreach($respObj['urlDetails'] as $item) {
					echo(chr(10). $item['url'] . chr(10));
					if ( !strcmp( $item['status'], 'success') ) {
						echo(chr(9) . 'File Type: ' . $item['filetype'] . chr(10));
						echo(chr(9) . 'Total Requests: ' . $item['totalrequests'] . chr(10));
						echo(chr(9) . 'Total Size: ' . $item['totalsize'] . chr(10));
					} else {
						echo(chr(9) . 'Failed: ' . $item['details'] . chr(10));

					}

				}
			}
		}
	// called directly from web
	} else {
		if ( isset($respObj['feedback']) ) {
			echo(chr(10).'Execution complete!' . '<br>' .chr(10));
			// dump feedback array
			foreach($respObj['feedback'] as $item) {
				echo($item . '<br>' . chr(10));
			}
			echo('<br>');

			// urlDetails
			if ( isset($respObj['urlDetails']) ) {
				foreach($respObj['urlDetails'] as $item) {
					echo(chr(10) . '<strong>' .$item['url'] . '</strong>' . '<br>' . chr(10));
					if ( !strcmp( $item['status'], 'success') ) {
						echo(chr(9) . 'File Type: ' . $item['filetype'] . '<br>' . chr(10));
						echo(chr(9) . 'Total Requests: ' . $item['totalrequests'] . '<br>' . chr(10));
						echo(chr(9) . 'Total Size: ' . $item['totalsize'] . '<br><br>' . chr(10));
					} else {
						echo(chr(9) . 'Failed: ' . $item['details'] . '<br><br>' . chr(10));

					}

				}
			}
		}
	}