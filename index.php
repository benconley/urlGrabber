<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Url Grab</title>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

	<style>
		.medPad { padding-left:40px; }
		ul { padding-left:40px; list-style: none; }
		li { padding-left:40px; }
	</style>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">&nbsp;</div>
		</div>
		<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>
							URL Grab
							<a class="btn btn-primary btn-xs" href="javascript:execGrab();" role="button">Execute Grab</a>
							<a class="btn btn-primary btn-xs" href="javascript:clearResults();" role="button">Clear Results</a>
						</h4>
					</div>
					<div class="panel-body" id="results">
						
					</div>
				</div>
			</div>
			<div class="col-md-1"></div>
		</div>
	</div>

	
	<script>
		function execGrab() {
			$.ajax({ url: 'service/urlGrab.php',
				type: 'get',
				dataType: "json",
				data:{'fromAjax':1},
				beforeSend: function() {
					var tempText = '<p class="text-center"><strong>Retrieving your urls. This may take a moment...</strong></p>';
					$('#results').html(tempText);

				},
				success: function(data) {

					$('#results').html('<div></div>');

					// include general feedback
					if ( typeof(data.feedback) !== 'undefined' ) {
						var feedback = '<p><strong>Feedback:</strong></p><ul id="feedback" style="list-style: none;"></ul>';
						$('#results').append(feedback);
						$.each(data.feedback, function(i, item) {
							$('#feedback').append('<li>'+item+'</li>');
						});
					}

					// include url details
					if ( typeof(data.urlDetails) !== 'undefined' ) {
						var urlDetails = '<p><strong>URL Details:</strong></p><p id="urlDetails"></p>';
						$('#results').append(urlDetails);
						$.each(data.urlDetails, function(i, item) {
							// each item is a url with detailed information
							detailHead = '<p class="medPad"><strong>'+item.url+'</strong> <a href="'+item.url+'" target="_blank"><small>(Launch Url)</small></a></p>';
							$('#urlDetails').append(detailHead);
							detailBody = '<ul>';
							if (item.status == 'success') {
								detailBody += '<li><strong>File Type: </strong>' + item.filetype + '</li>';
								detailBody += '<li><strong>Total Requests: </strong>' + item.totalrequests + '</li>';
								detailBody += '<li><strong>Total Size: </strong>' + item.totalsize + '</li>';
							} else {
								detailBody += '<li><strong>Failed: </strong>' + item.details + '</li>';
							}		
							detailBody += '</ul>';
							$('#urlDetails').append(detailBody);
						});
					}


				}
			});
		}

		function clearResults() {
			resultsContent = '<p class="text-center"><strong>Results will be displayed here.</strong></p>'+
				'<p class="text-center">Any urls contained within the &quot;data&quot; folder will be retrieved and assessed.</p>' +
				'<p class="text-center">Script may be run by command line or via this web interface.</p>';
			$('#results').html(resultsContent);
		}

		$( clearResults() );
	</script>
</body>
</html>


