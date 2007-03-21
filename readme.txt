=== Plugin Name ===
Contributors: Myriam Faulkner
Donate link :https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=web%40fairweb%2efr&item_name=fw%20WP%20plugins&item_number=fw%2dwpgoogleusermap&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=FR&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: google, google map, geo
Requires at least: 2.0.9
Tested up to: 2.1
Stable tag: trunk

Allows Wordpress logged in Users to add a marker on a Google map to indicate their location and view other users’ location.


== Description ==

Allows Wordpress logged in Users to add a marker on a Google map to indicate their location and view other users’ location. Non-logged in users may only view the map and markers but may not add a marker. This plugin is based on the GoogleMap API and the GoogleMapAPI php class. You must sign in for a GoogleMap API key to be able to use this plugin.

I have not modified the original GoogleMapAPI.class.php file. I have created a child class in fw_WPGoogleUserMap.class.php which is the one interferring with Wordpress and adding useful functions for the purpose of this plugin.

I’m happy to offer this plugin to the Wordpress community but I’m sorry I do not have time for support.

== Installation ==

* Put the fw-WPGoogleUserMap folder in your wp-content/plugins/ directory except the sample-page folder
* Set your own information in the fw_WPGoogleUserMap.php file as follows :
      `$apikey_WPGUM ='WRITE YOUR OWN API KEY HERE';`
      // YOUR MARKER’S URI
      `$marker_url = ‘http://labs.google.com/ridefinder/images/mm_20_green.png’;`
      `$map_height = “500?;`
      `$map_width = “500?;`
      `$sidebartitleWPGUM = “User list”;`
* Activate the plugin
* Put the fw_gum.php file in you template directory (this file is in the sample-page folder)
* Create a new page and apply the fw_gum template.
