<?php
session_start();
if (isset($_POST['codeValue']) && !empty($_POST['codeValue'])) {
	$myCode = $_POST['codeValue'];
	//print_r($myCode);
	require __DIR__ . '/vendor/autoload.php';

$client = new \Google_Client();

$client->setApplicationName('Google Sheets and PHP');

$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);

$client->setAccessType('offline');

$client->setAuthConfig(__DIR__ . '/credentials.json');

$service = new Google_Service_Sheets($client);

$spreadsheetId = "15VgvGkjEBLC3tb7-oqePd3YmuwtzVqcokCLwE45PiqI"; //It is present in your URL
$get_range = 'A:I';
$get_range1 = 'B:C';

function getData($service,$range,$sheetId){
    
    $response = $service->spreadsheets_values->get($sheetId, $range);
    //getting values
    $values = $response->getValues();
    //return $values;
}

function fetchName($service,$range,$sheetId,$myCode){
    
    $response = $service->spreadsheets_values->get($sheetId, $range);
    $values = $response->getValues();
    foreach($values as $key=>$content) {  
						$Name   = $content[0];
						//$vidURL   = $content['Original_url'];
						$Code   = $content[1];
						//echo $Name;
						//echo $Code;
						if($Code==$myCode) {
							return [$key, $Name];
						}
				}
}

function UpdateData($service,$range,$sheetId,$val){
    $body = new Google_Service_Sheets_ValueRange([

        'values' => $val
    
      ]);
    
      $params = [
    
        'valueInputOption' => 'RAW'
    
      ];
      $update_sheet = $service->spreadsheets_values->update($sheetId, $range, $body, $params);
      return($update_sheet);
    
}

//updateing values
$update_range = 'B:C'; 

$values = [[
     
],
[
  "Pawan",
],
[
  "Shubham"
]
];

$resultName = fetchName($service,$get_range1,$spreadsheetId,$myCode);
$_SESSION['result'] = $resultName;
print_r($resultName);
//print_r(getData($service,$get_range,$spreadsheetId));

//print_r(UpdateData($service,$update_range,$spreadsheetId,$values));

// print_r($response);

}
?>
<html>
    <head>
        <title>Winner Winner, Chicken Dinner!!!</title>
		<link rel="icon" type="image/png" href="favicon.ico"/>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="main.css" type="text/css" />
		
		
        <script type="text/javascript" src="Winwheel.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    </head>
    <body>
	
	<?php 
		if (!isset($_SESSION['result'])) {
	?>
	<div id="enterCode" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Enter your access code here...</h4>
			  </div>
			  <div class="modal-body">
				<form method="POST" action="">
				  <div class="form-group">
					<label for="code">Enter Code</label>
					<input type="text" class="form-control" id="codeValue" placeholder="Enter code" name="codeValue">
					<!--small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small-->
				  </div>
				  <button type="submit" class="btn btn-primary">Enter Site</button>
				</form>
			  </div>
			</div>

		  </div>
	</div>
	<?php } ?>
	
	
        <div align="center">
           <h3>Welcome Pawan!</h3>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>
                        <div class="power_controls">
                            <br />
                            <br />
                            <table class="power" cellpadding="10" cellspacing="0">
                                <tr>
                                    <th align="center">Power</th>
                                </tr>
                                <tr>
                                    <td width="78" align="center" id="pw3" onClick="powerSelected(3);">High</td>
                                </tr>
                                <tr>
                                    <td align="center" id="pw2" onClick="powerSelected(2);">Med</td>
                                </tr>
                                <tr>
                                    <td align="center" id="pw1" onClick="powerSelected(1);">Low</td>
                                </tr>
                            </table>
                            <br />
                            <img id="spin_button" src="spin_off.png" alt="Spin" onClick="startSpin();" />
                            <br /><br />
                            &nbsp;&nbsp;<a href="#" onClick="resetWheel(); return false;">Play Again</a><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(reset)
                        </div>
                    </td>
                    <td width="318" height="418" class="the_wheel" align="center" valign="center">
                        <canvas id="canvas" width="315" height="418">
                            <p style="{color: white}" align="center">Sorry, your browser doesn't support canvas. Please try another.</p>
                        </canvas>
                    </td>
                </tr>
            </table>
        </div>
		
		
		<script>
		$(window).on('load', function(){        
				$('#enterCode').modal('show');
			}); 
        </script>
        <script>
            // Create new wheel object specifying the parameters at creation time.
            let theWheel = new Winwheel({
                'numSegments'       : 8,         // Specify number of segments.
                'outerRadius'       : 150,       // Set outer radius so wheel fits inside the background.
                'drawMode'          : 'image',   // drawMode must be set to image.
                'drawText'          : true,      // Need to set this true if want code-drawn text on image wheels.
                'textFontSize'      : 0,        // Set text options as desired.
                'textOrientation'   : 'curved',
                'textDirection'     : 'reversed',
                'textAlignment'     : 'outer',
                'textMargin'        : 5,
                'textFontFamily'    : 'monospace',
                'textStrokeStyle'   : 'black',
                'textLineWidth'     : 2,
                'textFillStyle'     : 'white',
                'segments'     :                // Define segments.
                [
                   {'text' : '400'},
                   {'text' : '100'},
                   {'text' : '200'},
                   {'text' : 'no'},
                   {'text' : 'no'},
                   {'text' : '100'},
                   {'text' : 'no'},
                   {'text' : 'no'}
                ],
                'animation' :                   // Specify the animation to use.
                {
                    'type'     : 'spinToStop',
                    'duration' : 5,     // Duration in seconds.
                    'spins'    : 8,     // Number of complete spins.
                    'callbackFinished' : alertPrize
                }
            });

            // Create new image object in memory.
            let loadedImg = new Image();

            // Create callback to execute once the image has finished loading.
            loadedImg.onload = function()
            {
                theWheel.wheelImage = loadedImg;    // Make wheelImage equal the loaded image object.
                theWheel.draw();                    // Also call draw function to render the wheel.
            }

            // Set the image source, once complete this will trigger the onLoad callback (above).
            loadedImg.src = "whl.png";



            // Vars used by the code in this page to do power controls.
            let wheelPower    = 0;
            let wheelSpinning = false;

            // -------------------------------------------------------
            // Function to handle the onClick on the power buttons.
            // -------------------------------------------------------
            function powerSelected(powerLevel)
            {
                // Ensure that power can't be changed while wheel is spinning.
                if (wheelSpinning == false) {
                    // Reset all to grey incase this is not the first time the user has selected the power.
                    document.getElementById('pw1').className = "";
                    document.getElementById('pw2').className = "";
                    document.getElementById('pw3').className = "";

                    // Now light up all cells below-and-including the one selected by changing the class.
                    if (powerLevel >= 1) {
                        document.getElementById('pw1').className = "pw1";
                    }

                    if (powerLevel >= 2) {
                        document.getElementById('pw2').className = "pw2";
                    }

                    if (powerLevel >= 3) {
                        document.getElementById('pw3').className = "pw3";
                    }

                    // Set wheelPower var used when spin button is clicked.
                    wheelPower = powerLevel;

                    // Light up the spin button by changing it's source image and adding a clickable class to it.
                    document.getElementById('spin_button').src = "spin_on.png";
                    document.getElementById('spin_button').className = "clickable";
                }
            }

            // -------------------------------------------------------
            // Click handler for spin button.
            // -------------------------------------------------------
            function startSpin()
            {
                // Ensure that spinning can't be clicked again while already running.
                if (wheelSpinning == false) {
                    // Based on the power level selected adjust the number of spins for the wheel, the more times is has
                    // to rotate with the duration of the animation the quicker the wheel spins.
                    if (wheelPower == 1) {
                        theWheel.animation.spins = 2;
                    } else if (wheelPower == 2) {
                        theWheel.animation.spins = 5;
                    } else if (wheelPower == 3) {
                        theWheel.animation.spins = 8;
                    }

                    // Disable the spin button so can't click again while wheel is spinning.
                    document.getElementById('spin_button').src       = "spin_off.png";
                    document.getElementById('spin_button').className = "";

                    // Begin the spin animation by calling startAnimation on the wheel object.
                    theWheel.startAnimation();

                    // Set to true so that power can't be changed and spin button re-enabled during
                    // the current animation. The user will have to reset before spinning again.
                    wheelSpinning = true;
                }
            }

            // -------------------------------------------------------
            // Function for reset button.
            // -------------------------------------------------------
            function resetWheel()
            {
                theWheel.stopAnimation(false);  // Stop the animation, false as param so does not call callback function.
                theWheel.rotationAngle = 0;     // Re-set the wheel angle to 0 degrees.
                theWheel.draw();                // Call draw to render changes to the wheel.

                document.getElementById('pw1').className = "";  // Remove all colours from the power level indicators.
                document.getElementById('pw2').className = "";
                document.getElementById('pw3').className = "";

                wheelSpinning = false;          // Reset to false to power buttons and spin can be clicked again.
            }

            // -------------------------------------------------------
            // Called when the spin animation has finished by the callback feature of the wheel because I specified callback in the parameters.
            // note the indicated segment is passed in as a parmeter as 99% of the time you will want to know this to inform the user of their prize.
            // -------------------------------------------------------
            function alertPrize(indicatedSegment)
            {
                // Do basic alert of the segment text. You would probably want to do something more interesting with this information.
                alert("The wheel stopped on " + indicatedSegment.text);
            }
        </script>
		
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		
    </body>
</html>
