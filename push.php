<?php 
	include 'w3socket.php';
	if (isset($_REQUEST)) {
		if (isset($_REQUEST['channel']) && isset($_REQUEST['event']) && isset($_REQUEST['message'])) {
			push($_REQUEST['channel'], $_REQUEST['event'], $_REQUEST['message']);
		}
	}
 ?>