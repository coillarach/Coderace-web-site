<?php
	// window height and width are passed in from menu
	$bannerWidth = intval($windowWidth) * 0.8;
	$hscale = " style='width: " . $bannerWidth . "; height: auto;' ";

	echo "<img id='banner' src='./images/banner.jpg' " . $hscale . "/>";
?>