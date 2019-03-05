<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Deal or No Deal</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="./main.css">
</head>
<body>
<h1>Deal or No Deal</h1>
	<?php
 include 'lib.php';
 $state = 0;

 session_start();
 if (isset($_POST["submit"])) {
     $state = $_POST['nextState'];

     updateArrayOfCases($_SESSION["arrayOfCases"]);

     echo "<form method='post' action='index.php'>";

     if ($state <= 2) {
         echo showCases($_SESSION['arrayOfCases'], $state);
     } elseif ($state == 3) {
         echo "<h2>Now time for banker!</h2>";
         $sumOfUnopenedCases = 0;
         for ($i = 0; $i < 25; $i++) {
             if (!$_SESSION["arrayOfCases"][$i]->isOpened()) {
                 $sumOfUnopenedCases += $_SESSION["arrayOfCases"][
                     $i
                 ]->getValue();
             }
         }
         $averageVal = round($sumOfUnopenedCases / 19, 2);

         $_SESSION["bankerAmount"] = $averageVal;

         echo "<p>The bank will offer you \$" .
             number_format($_SESSION["bankerAmount"], 2) .
             "</p>";
         echo "<p>Do you accept?</p>";
         echo "<label>Yes<input type='radio' name='acceptBankOffer' value='yes' required/></label><br />";
         echo "<label>No<input type='radio' name='acceptBankOffer' value='no'/></label>";
         echo "<br>";
     } elseif ($state == 4) {
         $caseValue = $_SESSION['arrayOfCases'][
             $_SESSION['prizeCaseIndex']
         ]->getValue();

         $bankerValue = $_SESSION["bankerAmount"];

         if ($_POST["acceptBankOffer"] == "yes") {
             echo "<p>You accepted the banker's offer!</p>";
             echo "<p>The bank offered you  <strong>$" .
                 number_format($bankerValue, 2) .
                 "</strong></p>";
             echo "<p>Your case had <strong>$" .
                 number_format($caseValue, 2) .
                 "</strong></p>";

             if ($caseValue > $bankerValue) {
                 echo "<p>Yikes! Bad luck :/</p>";
             } else {
                 echo "<p>Nice! Good job :)</p>";
             }
         } elseif ($_POST["acceptBankOffer"] == "no") {
             echo "<p>You decline the banker's offer.</p>";
             echo "<p>The bank offered you  <strong>$" .
                 number_format($bankerValue, 2) .
                 "</strong></p>";
             echo "<p>Your case had <strong>$" .
                 number_format($caseValue, 2) .
                 "</strong></p>";

             if ($caseValue < $bankerValue) {
                 echo "<p>Yikes! Bad luck :/</p>";
             } else {
                 echo "<p>Nice! Good job :)</p>";
             }
         }
     } elseif ($state > 4) {
         // Reload the page
         echo "<script>
		 	window.location.href = window.location.pathname + window.location.search + window.location.hash;
		 </script>";
     }

     echo "<input type='hidden' name='nextState' value=" . ($state + 1) . " />";
     echo "<input type='submit' name='submit' value='Submit' />";
     echo "</form>";
 } else {
     $arrayOfCases = [];

     array_push($arrayOfCases, new MyCase(1));
     array_push($arrayOfCases, new MyCase(5));
     array_push($arrayOfCases, new MyCase(10));
     array_push($arrayOfCases, new MyCase(25));
     array_push($arrayOfCases, new MyCase(50));
     array_push($arrayOfCases, new MyCase(75));
     array_push($arrayOfCases, new MyCase(100));
     array_push($arrayOfCases, new MyCase(200));
     array_push($arrayOfCases, new MyCase(300));
     array_push($arrayOfCases, new MyCase(400));
     array_push($arrayOfCases, new MyCase(500));
     array_push($arrayOfCases, new MyCase(750));
     array_push($arrayOfCases, new MyCase(1000));
     array_push($arrayOfCases, new MyCase(5000));
     array_push($arrayOfCases, new MyCase(10000));
     array_push($arrayOfCases, new MyCase(25000));
     array_push($arrayOfCases, new MyCase(50000));
     array_push($arrayOfCases, new MyCase(75000));
     array_push($arrayOfCases, new MyCase(100000));
     array_push($arrayOfCases, new MyCase(200000));
     array_push($arrayOfCases, new MyCase(300000));
     array_push($arrayOfCases, new MyCase(400000));
     array_push($arrayOfCases, new MyCase(500000));
     array_push($arrayOfCases, new MyCase(750000));
     array_push($arrayOfCases, new MyCase(1000000));
     shuffle($arrayOfCases);

     $_SESSION['arrayOfCases'] = $arrayOfCases;
     echo "<form method='post' action='index.php'>";
     echo showCases($_SESSION['arrayOfCases'], $state);
     echo "<input type='hidden' name='nextState' value=" . ($state + 1) . " />";
     echo "<input type='submit' name='submit' value='Submit' />";
     echo "</form>";
 }

 echo getCashValues($_SESSION['arrayOfCases']);
 ?>

<!-- Starts over (sends you back home without any data) -->
 <form action="index.php" method="POST">
 	<input type="submit" name="Start Over" value="Start Over"/>
 </form>
 <script>
	const submitBtn = document.getElementsByName("submit")[0];
	
	submitBtn.addEventListener("click", (e) => {
		
		if (document.getElementsByName("openedCases[]").length > 1) {
			let checked = 0;
			const checkboxes = document.getElementsByName("openedCases[]");
			checkboxes.forEach(box => {
				if (box.checked) {
					checked++;
				}
			});
			if (checked != 6) {
				e.preventDefault();
				alert("You have to select 6 cases");
			}
			
		}
	})

 </script>
</body>
</html>