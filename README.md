# urlGrab
Grabs some urls from a config file, and derives information about those urls

***************************************************************************************
* "urlGrab" is a simple utility that will grab a list of urls, and return some metrics
* written by: Ben Conley 12/13/2015
* 
* - I hope you guys like it, because I really want to work with you!
* 
***************************************************************************************

EXAMPLE INPUT FILES
***************************************************************************************
Text files containing urls should be placed into the 'data' folder.
They may either be plain text with one url on each line:
http://www.google.com
http://www.zombo.com
Or a JSON formatted array containing urls:
["http://www.cnn.com","http://www.gmail.com"] 
***************************************************************************************

ASSUMPTIONS
***************************************************************************************
* As this script iterates over the array of urls, it will attempt to determine the file type and size. If a binary file is encountered, the url will be considered 'parsed.' If the url yields a text file, it will be parsed for additional assets. This will include both binary and text assets such as css, js, etc.
* These assets will be accumulated in order to check file size and total request count. We're not attempting to build a spider here, so this process will only proceed to one level below the initial url array.
* 'totalrequests' for each url will be the sum of the url itself, plus each of the assets included within the first level
* 'totalbytes' will be the sum of the initial url's content, plus the size of any assets contained within the first level
* 'totalsize' will be a human readable version of the total byte count

WAYS TO EXECUTE SCRIPT
***************************************************************************************
Call from command prompt with TEXT output:
    php src/service/urlGrab.php

EXAMPLE OUTPUT
***************************************************************************************
Execution complete!
Total number of urls provided: 4

http://zombo.com
        File Type: text/html
        Total Requests: 3
        Total Size: 1.25 KB

http://google.com
        File Type: text/html; charset=iso-8859-1
        Total Requests: 22
        Total Size: 834.39 KB

http://teamshocker.com/pics/didntread3.gif
        File Type: image/gif
        Total Requests: 1
        Total Size: 2.93 MB

http://teamshocker.com/pics/canthandlemyswag.gif
        File Type: image/gif
        Total Requests: 1
        Total Size: 500.88 KB