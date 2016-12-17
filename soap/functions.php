<?php
function price($find){
	$books=array(
		"java"=>299,
		"c"=>348,
		"php"=>267
		);
	foreach ($books as $book => $value) {
		if($book==$find){
			$price= $value;
		}
	}
	return $price;
}
?>