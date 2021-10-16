<!DOCTYPE html>
<html>
	<head>
		<title>AKASH ARUMUGAM B S</title>
	</head>
	
	<body>
		<h1>MD5 cracker</h1>
		
		<p>This application takes an MD5 hash
		of a four digit pin and check all 10,000
		possible four digit PINs to determine the PIN.
		</p>

		<pre>
			<?php
				print "\n"; 
				$goodtext = "Not found";
				$checkCount = 0;
				if ( isset($_GET['md5']) ) {
				    $time_pre = microtime(true);
				    $md5 = $_GET['md5'];

				    $txt = "0123456789";
				    $show = 15;

				    for($i=0; $i<strlen($txt); $i++ ) {
				        $ch1 = $txt[$i];
				        
				        for($j=0; $j<strlen($txt); $j++ ) {
				            $ch2 = $txt[$j];
				            
				            for($k=0; $k<strlen($txt); $k++) { 
				            	$ch3 = $txt[$k];

				            	for($l=0; $l<strlen($txt); $l++) {
				            		$ch4 = $txt[$l];

				            		$try = (($ch1.$ch2).$ch3).$ch4;

						            $check = hash('md5', $try);
						            $checkCount++;
						            if ( $check == $md5 ) {
						                $goodtext = $try;
						                break;
						            }

						            if ( $show > 0 ) {
						                print "$check $try\n";
						                $show = $show - 1;
						            }
				            	}

				            	if($goodtext !== "Not found") {
				            		break;
				            	}
				            }

				            if($goodtext !== "Not found") {
				            	break;
				            }
				        }

				        if($goodtext !== "Not found") {
				       		break;
				        }
				    }

				    $time_post = microtime(true);
				    print "\nTotal checks: $checkCount\n";
				    print "Elapsed time: ";
				    print $time_post-$time_pre;
				    print "\n";
				}
			?>
		</pre>

		<p>PIN: <?= htmlentities($goodtext); ?></p>

		<form>
			<input type="text" name="md5" size="60" />
			<input type="submit" value="Crack MD5"/>
		</form>
		
		<ul>
			<li><a href="index.php">Reset</a></li>
		</ul>
	</body>
</html>