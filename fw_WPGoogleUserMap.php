<?php
/*
Plugin Name: fw GoogleUserMap
Plugin URI: http://weblog.fairweb.fr/plugins-wordpress/fw-wpgoogleusermap/
Description: Allows Users to add a marker on a Google map to indicate their location and view other users' location
Version: 1.0-beta
Author: Myriam Faulkner
Author URI: http://www.fairweb.fr
Min WP Version: 2.0

/*  Copyright 2007  PLUGIN_AUTHOR_NAME  (email : web@fairweb.fr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/



require_once('fw_WPGoogleUserMap.class.php');

 $map = new fw_WPGoogleUserMap('map','fwWPGUM_map');
 
 // WRITE YOUR OWN API KEY HERE
 $apikey_WPGUM ='WRITE YOUR OWN API KEY HERE';
 // YOUR MARKER'S URI
 $marker_url = 'http://labs.google.com/ridefinder/images/mm_20_green.png';
 $map_height = "500";
 $map_width = "500";
 $sidebartitleWPGUM = "User list";

function fw_WPGUM_header () {
global $map, $user_ID, $user_info, $apikey_WPGUM, $marker_url, $map_height, $map_width;

$map->setAPIKey($apikey_WPGUM);
$map->setHeight($map_height);
$map->setWidth($map_width);

get_currentuserinfo();
	// update or create user marker coord in database
	if (isset($_POST['user_lat'])) {
		 if ('' == $user_ID) {
			 echo "vous devez &ecirc;tre identifi&eacute; pour vous inscrire sur la carte"; } 
			 else { 
			$map->validformWPGUM ($user_ID);
		}
	}

	// delete user marker from database
	if (isset($_POST['WPGUMsup']) && $user_ID == ($_POST['WPGUMuserid'])) {
		$map->suppusercoordWPGUM ($user_ID);		 
	}
 
      $map->enableZoomEncompass();
     // SET MARKER
	  $map->setMarkerIcon($marker_url);
      //CREATE MARKERS
	  $map->createmarkersWPGUM ();
	  // print javascript   
	  $map->printHeaderJS();
	  $map->printWPGUMJS(); ?>
	  <script  type="text/javascript">window.onload = onLoad</script>
<link rel="stylesheet"  href="<?php bloginfo('url');?>/wp-content/plugins/fw_WPGoogleUserMap/fw_WPGoogleUserMap.css" type="text/css" />

<?php	
}



function fw_WPGUM_display () {
global $map, $sidebartitleWPGUM; ?>
<div id="carte_gauche">
<?php $map->printMap(); // display map ?>
	
</div>
<div id="carte_droite"><div id="maplisttitle"><h3><?php echo $sidebartitleWPGUM;?></h3></div>
<?php $map->printSidebar(); // display list of users ?>
</div>
<?php
}

add_action('wp_head', 'fw_WPGUM_header');
?>