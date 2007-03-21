<?php require_once('GoogleMapAPI.class.php');

class fw_WPGoogleUserMap  extends GoogleMapAPI {
    var $owner;
	var $map_id;
	var $sidebar_id;
	var $app_id;
	
	 function fw_WPGoogleUserMap($map_id = 'map', $app_id = 'MyMapApp') {
	 
        $this->map_id = $map_id;
        $this->sidebar_id = 'sidebar_' . $map_id;
        $this->app_id = $app_id;
		
		$this->enableTypeControls();
		$this->setMapType('hybrid');
		$this->disableDirections();
		$this->enableOverviewControl();  
		$this->enableInfoWindow();
    }
	
    function getWPGUMJS()  {
	global $user_ID, $userdata;
        $_output = $this->getMapJS();
		get_currentuserinfo();

		
		$add_fonction =		'var compteurclick = 0;'."\n";
	
	$add_fonction .=	'GEvent.addListener('.$this->map_id.', "click", function(marker, point) {' . "\n".'	
  			if (marker) {' . "\n".'	
			} else {' . "\n".'	
  				if (compteurclick == 0) {' . "\n".'	
		
		var fwmarker = new GMarker(point, {draggable: true});' . "\n".'	

		GEvent.addListener(fwmarker, "dragstart", function() {' . "\n".'	
		  map.closeInfoWindow();' . "\n".'	
		  });' . "\n".'	
		
		GEvent.addListener(fwmarker, "dragend", function() {' . "\n".'
		var fwcoord = fwmarker.getPoint() ;
		var pointlat = fwcoord.lat();' . "\n".'	
		var pointlng = fwcoord.lng();' . "\n".'	';
		
		
      if ('' == $user_ID) {
        $alerter =  "vous devez &ecirc;tre identifi&eacute;";
      } else {
	 
  $alerter = '<h3>' . $userdata->user_login.'</h3><form action=\"\" method=\"post\" name=\"fwWPGUMform\"><label for=\"user_lat\">Latitude : </label><input name=\"user_lat\" value=\""+pointlat+"\" type=\"text\"><br /><label for=\"user_lng\">Longitude : </label><input name=\"user_lng\" value=\""+pointlng+"\" type=\"text\"><br /><input name=\"Submit\" type=\"Submit\" value=\"valider\" /></form>';
	}
		
		$add_fonction.=' fwmarker.openInfoWindowHtml("'.$alerter.'");' . "\n".'	
		  });
	map.addOverlay(fwmarker);' . "\n".'	
		var pointlat = point.lat();' . "\n".'	
		var pointlng = point.lng();' . "\n".'';
		
		  if ('' == $user_ID) {
        $alerter =  'Vous devez &ecirc;tre identifi&eacute; pour pouvoir poser votre marqueur.<br /><a href=\"'.get_bloginfo('url').'/wp-login.php?action=register\">Cr&eacute;ez un compte</a> ou <a href=\"'.get_bloginfo('url').'/wp-login.php?action=login\">Identifiez-vous</a>';
      } else {
	 
  $alerter = '<h3>' . $userdata->user_login.'</h3><form action=\"\" method=\"post\" name=\"fwWPGUMform\"><label for=\"user_lat\">Latitude : </label><br /><input name=\"user_lat\" value=\""+pointlat+"\" type=\"text\"><br /><label for=\"user_lng\">Longitude : </label><br /><input name=\"user_lng\" value=\""+pointlng+"\" type=\"text\"><br /><input name=\"Submit\" type=\"Submit\" value=\"valider\" /></form>';
	}
	$add_fonction.=	'fwmarker.openInfoWindowHtml("'.$alerter.'");' . "\n".'	
		compteurclick++;' . "\n".'	
	} else {' . "\n".'	
	
	
	}' . "\n".'	
	
  } ' . "\n".'	
});' . "\n".'	';


$_output .= str_replace(sprintf('map = new GMap2(document.getElementById("%s"));',$this->map_id) . "\n", sprintf('map = new GMap2(document.getElementById("%s"));',$this->map_id) . "\n".$add_fonction,$_output);
		
	return $_output;
    }	
	 function printWPGUMJS() {
        echo $this->getWPGUMJS();
    }
	
	 function suppusercoordWPGUM ($user_id) {
		global $table_prefix, $wpdb;
		$query = $wpdb->query("DELETE FROM ".$table_prefix."usermeta WHERE user_id = '".$user_id."' AND meta_key = 'wp_GUMcoord'");
		
	
	}
	
	 function addMarkerByCoordsWPGUM($lon,$lat,$title = '',$html = '',$id) {
        $_marker['lon'] = $lon;
        $_marker['lat'] = $lat;
        $_marker['html'] = (is_array($html) || strlen($html) > 0) ? $html : $title;
        $_marker['title'] = $title;
        $this->_markers[] = $_marker;
        $this->adjustCenterCoords($_marker['lon'],$_marker['lat']);
        // return index of marker
       // return count($this->_markers) - 1;
	   return($id);
	  
    }
	
	function validformWPGUM ($user_id) {
	global $wpdb, $table_prefix, $user_ID, $userdata;
	
		if ($wpdb->get_row("SELECT * FROM ".$table_prefix."usermeta WHERE user_id = '".$user_id."' AND meta_key = 'wp_GUMcoord'") == NULL) {
		// enregistrer le user
		$wpdb->query("INSERT INTO ".$table_prefix."usermeta VALUES ('', '".$user_id."','wp_GUMcoord','".$_POST['user_lat']."|".$_POST['user_lng']."')");
		
		} else {
		$wpdb->query("UPDATE  ".$table_prefix."usermeta SET meta_value ='".$_POST['user_lat']."|".$_POST['user_lng']."' WHERE user_id='".$user_id."' AND meta_key= 'wp_GUMcoord'");
		}
	}
	
	
	
	function createmarkersWPGUM (){
	global $wpdb, $table_prefix,$user_ID, $userdata;
		if ('' != $user_ID) {
		$user_info = get_userdata($user_ID);
		$user_info_id = $user_info->ID;
		} else {
		$user_info_id ='';
		
		}
		$marker = $wpdb->get_results ("SELECT META.*, US.ID, US.display_name, US.user_url FROM ".$table_prefix."usermeta META LEFT JOIN ".$table_prefix."users US ON (META.user_id = US.ID) WHERE META.meta_key='wp_GUMcoord' AND META.meta_value !='' ORDER BY US.display_name"); 
		// if no marker exists, create one for admin user
		if ($marker == NULL) {
			$marker = $wpdb->query("INSERT INTO ".$table_prefix."usermeta VALUES ('', '1','wp_GUMcoord','43.518260129361|4.146158695220947')");
		}
		
		
		foreach ($marker as $newmarker) {
			$profil = $wpdb->get_row ("SELECT meta_key, meta_value FROM ".$table_prefix."usermeta WHERE user_id='".$newmarker->ID."' AND meta_key='description'");
			
			$user_url = ($newmarker->user_url == '') || $newmarker->user_url == 'http://' ? '' : '<br /><a href="'.$newmarker->user_url.'" target="_blank" title="'.$newmarker->display_name.'">'.$newmarker->user_url.'</a>';
			$user_description = $profil->meta_value == '' ? '' : '<p>'.$profil->meta_value.'</p>';
			list($lat,$lng) = explode("|",$newmarker->meta_value);
			if ($user_info_id==$newmarker->ID) {
		
		$user_spec ='<form action="" method="post" name="formWPGUMsupp"><input name="WPGUMuserid" type="hidden" value="'.$user_info_id.'" /><input name="WPGUMsup" type="Submit" value="Supprimer mon marqueur" /></form>';
			} else {
			$user_spec ="";
			}
			
			
				
			$this->addMarkerByCoordsWPGUM($lng,$lat,$newmarker->display_name,'<h3>'.$newmarker->display_name.'</h3>'.$user_url.$user_description." ".$user_spec,$newmarker->ID);
			
		}
	
	}
		
	

	
}?>