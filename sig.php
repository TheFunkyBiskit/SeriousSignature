<?php
include_once('simple_html_dom.php');

function getData(){
	$html = new simple_html_dom();
	$id = "STEAM_0:0:24693485";
	$url = "http://www.seriousgmod.com/stats/stats.php?steamid=" . $id;
	$html->load_file($url);
	
	$name = $html->find('div[class=w-section main-section]',0)->find('h1',0)->innertext;
	$totalTime = $html->find('div[class=w-row totals]',0)->find('div',0)->find('h4',0)->find('strong',0)->innertext;
	$kills = $html->find('div[class=w-row totals]',0)->find('div',1)->find('h4',0)->find('strong',0)->innertext;
	$deaths = $html->find('div[class=w-row totals]',0)->find('div',2)->find('h4',0)->find('strong',0)->innertext;
	$headshots = $html->find('div[class=w-row totals]',0)->find('div',3)->find('h4',0)->find('strong',0)->innertext;
	
	$kdratio = round($kills/$deaths,2);
	$hsratio = round($headshots/$kills,2);
	
	$userData = array(
		"name" => $name,
		"totalTime" => $totalTime,
		"kills" => $kills,
		"deaths" => $deaths,
		"headshots" => $headshots,
		"kdratio" => $kdratio,
		"hsratio" => $hsratio,
	);
	
	return $userData;
}

function makeImage(){
	$userData = getData();
	
	$im = imagecreatefrompng('bg.png');
	imagesavealpha($im, true);
	$my_img = imagecreatetruecolor(860, 150);
	imagesavealpha($my_img, true);
	$trans_color = imagecolorallocatealpha($my_img, 0, 0, 0, 127);
	imagefill($my_img, 0, 0, $trans_color);
	imagecopy($my_img, $im, 0, 0, 0, 0, 860, 150);
	
	$font_file = 'quicksand.ttf';
	$white = imagecolorallocate($my_img, 255, 255, 255);
	$blue = imagecolorallocate($my_img, 33, 137, 255);
	
	imagettftext($my_img, 30, 0, 50, 50, $white, $font_file, $userData['name']);
	imagettftext($my_img, 15, 0, 50, 120, $white, $font_file, $userData['totalTime']);
	imagettftext($my_img, 15, 0, 205, 120, $white, $font_file, $userData['kills']);
	imagettftext($my_img, 15, 0, 345, 120, $white, $font_file, $userData['deaths']);
	imagettftext($my_img, 15, 0, 485, 120, $white, $font_file, $userData['headshots']);
	imagettftext($my_img, 15, 0, 610, 120, $white, $font_file, $userData['kdratio']);
	imagettftext($my_img, 15, 0, 750, 120, $white, $font_file, $userData['hsratio']);

	header("Content-type: image/png");
	imagepng($my_img);
	imagecolordeallocate($blue);
	imagecolordeallocate($white);
	imagecolordeallocate($trans_color);
	imagedestroy($my_img);
	imagedestroy($im);
}
//echo getData()['hsratio'];
makeImage();
?>