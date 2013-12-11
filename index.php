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

require_once 'fbmain.php';

$config['baseurl'] = 'http://popofibo.com/bookie/release';

//if user is logged in and session is valid
if ($fbme) {
	try{
		$movies = $fb->api('/me/movies');
		$albums = $fb->api('/me/albums');
	}
	catch(Exception $o){
		d($o);
	}
	
	try{
			$param = array(
				'method' => 'users.getinfo',
				'uids' => $fbme['id'],
				'fields' => 'name,current_location,profile_url',
				'callback'=> ''
			);
		$userInfo = $fb->api($param);
		}
		catch(Exception $o){
			d($o);
		}

		if(isset($_POST['tt'])) {
			try {
				$statusUpdate = $fb->api('/me/feed', 'post', array('message'=> $_POST['tt'], 'cb' => ''));
			} catch (FacebookApiException $e) {
				d($e);
			}
		}

		try{
			$uid = $fb->getUser();

			$fql = 'SELECT aid, name, size FROM album WHERE owner=' . $uid;// and acces_token='. $sess;
			$param = array(
				'method' => 'fql.query',
				'query' => $fql,
				'callback' => ''
			);
			
		$fqlResult = $fb->api($param);
		
		}
		catch(Exception $o){
			d($o);
		}
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
        <title>Bookie base code</title>
    </head>
<body class="fbbody">
    <div id="fb-root"></div>
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({appId: '<?=$fbconfig['appid' ]?>', status: true, cookie: true, xfbml: true});

                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
                    login();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
                    logout();
                });
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());

            function login(){
                document.location.href = "<?=$config['baseurl']?>";
            }
            function logout(){
                document.location.href = "<?=$config['baseurl']?>";
            }
</script>
<style type="text/css">
    .box{
        margin: 5px;
        border: 1px solid #60729b;
        padding: 5px;
        width: 500px;
        height: 200px;
        overflow:auto;
        background-color: #e6ebf8;
    }
</style>

    <center><h3>Bookie Slider</h3></center>
    
    <!-- all time check if user session is valid or not -->
    <?php if ($fbme){ ?>
    <table border="0" cellspacing="3" cellpadding="3">
		<tr>
            <td>
                <div align="left">
                    <b>My Bookie Album Info</b>
					</br></br>
                    <?php foreach($fqlResult as $album) { ?>
					<a href="album.php?aid=<?=$album['aid']?>"> <?=$album['name']?></a> (<?=$album['size']?>)<br />
					<?php } ?>
                </div>
            </td>
			<td align="right">
                <div align="right">
					<img src="bookie.png" alt="Bookie Slider" align=right/>
                </div>
            </td>
        </tr>
    </table>
	<center><a href='event:http://www.popofibo.com/'>Copyright 2011 Nitin Pathak (www.popofibo.com)</a></center>
	<?php } ?>
    </body>
</html>