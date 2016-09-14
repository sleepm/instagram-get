<?php
$html = <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Instagram Get?)</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<style>
		body{margin:0 auto;max-width: 640px;}
		a{text-decoration: none;}
	</style>
</head>
<body>
	<input type="url" name="src" id="src">
	<input type="submit" value="Get it!" id="get">
	<script>
		var get = document.getElementById('get');
		get.onclick = function(){
			var src = document.getElementById('src').value;
			var input = document.getElementById('src');
			input.onfocus = function(){
				this.value='';
			}
			if(src.match("https://www.instagram.com/p/")){
				src = src.split('/');
				src = src.pop()==""?src.pop():null;
				getJson(src);
			}else if(src.length==11){
				getJson(src);
			}else{
				error();
			}
		}
		//var ajax = new XDomainRequest() | new XMLHttpRequest();
		function getJson(src){
			var ajax = new XMLHttpRequest();
			ajax.open('GET', '?src='+src);
			ajax.send();
			ajax.onload = function(){
				var json = JSON.parse(ajax.responseText);
				var imageUrl = json.image_url;
				var videoUrl = json.hasOwnProperty('video_url')?json.video_url:'';
				var displayResult = document.createElement('fieldset');
				displayResult.innerHTML = '<legend>'+json.title+'</legend><a href="'+imageUrl+'">get picture</a>'+(videoUrl==''?'':'<br/><a href="'+videoUrl+'">get video</a>');
				document.body.appendChild(displayResult);
			}
		}
		function error(){
			alert('are you sure?\nplease check again..');
			return 'i am all right';
		}
	</script>
</body>
</html>
HTML;
isset($_GET['src'])?$url = 'https://www.instagram.com/p/'.$_GET['src']:'';
if(isset($url)){
	echo returnJson($url);
}else{
	echo $html;
}
function returnJson($src)
{
	$result = file_get_contents($src);
	preg_match('#<meta name="medium" content="(.*)" />#', $result, $type);
	preg_match('#title" content="(.*)" />#', $result ,$title);
	preg_match('#image" content="(.*)" />#', $result, $imageUrl);
	if($type[1]=='video'){
        	preg_match('#video:secure_url" content="(.*)" />#', $result, $videoUrl);
        	$videoUrl = $videoUrl[1];
	}
	$json = '{"title":"'.$title[1].'","image_url":"'.$imageUrl[1].(isset($videoUrl)?'","video_url":"'.$videoUrl.'"}':'"}');
	header("Content-Type: application/json");
	return $json;
}
