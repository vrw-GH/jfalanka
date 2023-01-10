<?php
if (isset($_GET['page']) && isset($_GET['subpage'])){
	if ($_GET['subpage']==1) {
		$subid=1;
	}else if ($_GET['subpage']==2) {
		$subid=2;
	}else if ($_GET['subpage']==3) {
		$subid=3;
	}else if ($_GET['subpage']==4) {
		$subid=4;
	}else if ($_GET['subpage']==5) {
		$subid=5;
	}else if ($_GET['subpage']==6) {
		$subid=6;
	}else if ($_GET['subpage']==7) {
		$subid=7;
	}else if ($_GET['subpage']==8) {
		$subid=8;
	}else if ($_GET['subpage']==9) {
		$subid=9;
	}else if ($_GET['subpage']==10) {
		$subid=10;
	}else if ($_GET['subpage']==11) {
		$subid=11;
	}else if ($_GET['subpage']==12) {
		$subid=12;
	}else if ($_GET['subpage']==13) {
		$subid=13;
	}else if ($_GET['subpage']==14) {
		$subid=14;
	}else if ($_GET['subpage']==15) {
		$subid=15;
	}else{
		$subid=1;
	}	
}else{
	$pid=0;
	$subid=1;
}
?>