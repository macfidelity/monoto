<?php

// -----------------------------------------------------------------------------
// Name:		helperFunctions.php
// Function:	This file contains several php methods used in this project
// -----------------------------------------------------------------------------



// -----------------------------------------------------------------------------
// Creates a noty notification popup
// -----------------------------------------------------------------------------
function displayNoty($notyText, $notyType)
{
	// NOTY-TYPES:
	//
	// alert
	// information
	// error
	// warning
	// notification
	// success

    // display notification
	echo "<script>var n = noty({text: '".$notyText."', type: '".$notyType."'});</script>";
}


// -----------------------------------------------------------------------------
// Write to JavaScript console (via javascript)
// -----------------------------------------------------------------------------
function writeToConsoleLog( $message )
{
	echo "<script>console.log('[PHP]".$message."')</script>"; // write to js console
}


// -----------------------------------------------------------------------------
// Translating the UserInterface (#210)
// -----------------------------------------------------------------------------
function translateString( $textForTranslation )
{
	if ( $_SESSION[ 'monoto' ][ 'getText' ] == 0 ) // gettext is not installed - fallback
	{
		$translation = $textForTranslation;
        return $translation;
	}

	// otherwise: gettext is installed -> try to translate

	// I18N support information here
	$language = $_SESSION[ 'monoto' ][ 'lang' ];
	if ( $language == "" ) // Fallback to english
	{
		$language = "en_US";
	}

	putenv("LANG=$language");
	setlocale(LC_ALL, $language);

	// Set the text domain as 'messages'
	$domain = 'monoto';
	bindtextdomain($domain, "./locale");
	textdomain($domain);

	$translation = gettext($textForTranslation);

	// report non-translated texts for debugging
	if ( $translation == $textForTranslation )
	{
		if ( $language != "en_US" )
		{
			writeToConsoleLog("translateString ::: Translation-issue, found no translation for: _".$textForTranslation."_.");
		}
	}

	return $translation;
}


// -----------------------------------------------------------------------------
// Playground for phpunit
// -----------------------------------------------------------------------------
function oddOrEven( $num )
{
    return $num%2; // Returns 0 for odd and 1 for even
}


?>
