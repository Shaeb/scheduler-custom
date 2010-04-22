<?php
$flash = $_SESSION[ 'flash' ];
if( isset($_SESSION['flash'] ) ) {
	$data = "<p class='highlight'>{$flash}</p>";
}
?>