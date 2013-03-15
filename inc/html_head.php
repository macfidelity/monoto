<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
		<title>monoto-notes</title>

		<!-- CSS -->
		<!-- general -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" />
		<link rel="alternate stylesheet" type="text/css" href="css/page02.css" title="alt" />
		<!-- blackbird css-->
		<link type="text/css" rel="Stylesheet" href="css/blackbird.css" />
		
		<!-- JS which apply to all pages -->
		<!-- ########################### -->
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script type='text/javascript' src='js/LAB.js'></script>

		<!-- loading the other scripts via LAB.js  ... without loading-blocking so far -->
		<script>
		   $LAB
		   .script("js/m_reallyLogout.js") 			// ask really-logout question if configured by admin
		   .script("js/m_disableRightClick.js")		// disabled the right-click contextmenu
		   .script("js/blackbird.js")				// 3rd: logging window via F2
		   .script("js/styleswitcher.js")			// 3rd: css toggle
		   .script("js/m_menu.js")					// main-menu
		   .script("js/modal.popup.js")				// 3rd: keyboard shortcuts popup
		   .script("js/m_keyboardPopup.js")			// 3rd: keyboard shortcuts popup part2
		</script>
	
		<!-- closing </head> inside each single php file to be able to load other js files inside the head -->