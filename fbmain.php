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

        $updated = date("l, F j, Y", strtotime($fbme['updated_time']));

        //echo "Hello " . $fbme['name'] . "<br />";
        //echo "You last updated your profile on " . $updated;
		
		/*$query = array('method' => 'fql.query', 'query' => 'SELECT aid, name, size FROM album WHERE owner = '.$uid, );
		//Run Query - went missing in the first go
		$albums = $fb->api($query);
		$fbml = "<strong>Bookie Strong Debug:</strong><br /><br />";
		
		//print('No. of albums returned' . count($albums));

		foreach($albums as $album) {
			print($album['name']);
			$fbml .= '<a href="http://apps.new.facebook.com/bookie/album.php?aid='
				.$album['aid'].'">'.$album['name'].'</a> ('.$album['size'].')
			<br />';
		}

		//$fb->api_client->profile_setFBML(null, $uid, $fbml, null, null, $fbml);
		//$fb->api(array('method' => 'profile.setFBML', 'profile' => $fbml));

		//echo '<p>Hello, <fb:name uid="'.$uid.'" useyou="false" />!</p>';
		//echo '<fb:add-section-button section="profile" />';
		//echo $fbml;*/

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
