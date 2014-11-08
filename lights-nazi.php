<?php
$status_url = "http://p-space.gr/status/";
$apollo_url = "http://localhost/Apollo/Helios.php";
$verbose = FALSE;
	
$oldData = -1;
for(;;)
{
	if($verbose)
		echo "Connecting to '".$status_url."'\n";

	$data = file_get_contents($status_url);
	if($data !== FALSE)
	{
		if($verbose)
		{
			echo "Connected. Data received:\n";
			echo $data."\n";
		}
		if($data != $oldData)
		{
			$oldData = $data;

			echo "Status Changed. New status is: ";
			echo ($data == 1? "Open":"Closed")."\n";

			$handle = curl_init($apollo_url);
			$data = array('state' => ($data == 1? 'ALL_ON':'ALL_OFF'));
			curl_setopt($handle, CURLOPT_POST, true);
			curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
			for($i=0; $i<3; $i++)
			{
				echo "Calling Apollo (".($i+1)." of 3)\n";
				$retVal = curl_exec($handle);
			
				if($retVal == false)
				{
					echo "Couldn't connect to: ".$apollo_url."\n";
					echo "Error: ";
					echo curl_errno($handle)." ";
					echo curl_error($handle)."\n";
					sleep(9);
				}
				else
				{
					echo "Apollo responded. Success!\n";
				}
				sleep(1);
				
			}
			
			curl_close($handle);
		}
	}
	else
	{
		if($verbose)
			echo "Error connecting to '".$status_url."'. Trying again in 30 seconds.\n";

		sleep(25);
	}
	// echo "Connecting in 5 seconds...\n";
	sleep(5);
}

?>