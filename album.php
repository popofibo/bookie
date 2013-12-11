<?php

/*
	Copyright (C) 2011 Nitin Pathak (www.popofibo.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once 'facebook.php';

$api_key = '244997602202125';
$secret = '2d51fe974b741ca9249bf3ec39eebf09';

$fb = new Facebook(array(
            'appId' => $api_key,
            'secret' => $secret,
            'cookie' => true
));

$session = $fb->getAccessToken();
$fbme = null;
$user = $fb->getUser();
$aid = null;
$album = null;

if(!$user) {
	$url = $fb->getLoginUrl(array(
        'canvas' => 1,
        'fbconnect' => 0,
		'scope' => 'user_photos,user_photo_video_tags,publish_stream,friends_photos,user_birthday,user_location,user_work_history',
    ));
	echo '<script type="text/javascript">top.location.href="'.$url.'"</script>';
	header("Location:{$fb->getLoginUrl(array('req_perms' => 'user_status,publish_stream,user_photos'))}");
	}

if (!$session) {
    
    $loginUrl = $fb->getLoginUrl(array(
        'canvas' => 1,
        'fbconnect' => 0,
		'scope' => 'user_photos,user_photo_video_tags,publish_stream,friend_photos,user_birthday,user_location,user_work_history',
    ));

   echo '<script type="text/javascript">top.location.href="'.$loginUrl.'"</script>';
   header("Location:{$fb->getLoginUrl(array('req_perms' => 'user_status,publish_stream,user_photos'))}");
   exit;
 

} else {

    try {

        $uid = $fb->getUser();
        $fbme = $fb->api('/me');

		$aid = $_GET['aid'];
		
		$fql = 'SELECT name, size FROM album WHERE aid =\'' . $aid . '\'';// and acces_token='. $sess;
			$param = array(
				'method' => 'fql.query',
				'query' => $fql,
				'callback' => ''
			);
			
		$albums = $fb->api($param);
		$album = $albums[0];

	} catch (FacebookApiException $e) {
        echo "Error:" . print_r($e, true);
    }
}

	function d($d){
		echo '<pre>';
		print_r($d);
		echo '</pre>';
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	 	<!-- Include support librarys first -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
		<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>	
		<LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
	</head>
	<body class="fbbody">
		<div id="fb-root"></div><!-- required div tag -->
		Viewing <strong><?=$album['name']?></strong><br />
		There are <strong><?=$album['size']?></strong> photo(<?=$album['size'] > 0 ? 's' : ''?>) in this album.
		<br /><br />
		<div id="flashContent">
			<h1>You need at least Flash Player 9.0 to view this page.</h1>
			<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
		</div>
		<script type="text/javascript">			
			swfobject.embedSWF("BookieApp.swf?aid=<? echo($aid) ?>", "flashContent", "700", "500", "9.0", null, null, null, {name:"flashContent"});
		</script>
		</br>
		<a href="index.php">Back to Bookie Main Page</a><br />
		</div>
		<center><a href='event:http://www.popofibo.com/'>Copyright 2011 Nitin Pathak (www.popofibo.com)</a></center>
	</body>
</html>