<?php

namespace util;
require_once 'photo_story.php';

/** Return the short-name of the language configured in the browser.
 * Short names include "de" for german, "en" for english.
 * If the browser is set to a specific dialect of a language like
 * "en-us", only the part before the dash will be returned.
 */
function get_user_language_from_request() {
	if( isset( $_GET[ 'lang' ] ) ) {
		return $_GET[ 'lang' ];
	}
	$lang_code = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en';
	return explode( '-', $lang_code )[ 0 ];
}

/** Check if a provided value is a string and if it only consists of
 * alphanumeric characters
 */
function is_alphanum( $string ) {
	return gettype( $string ) == 'string' && preg_match( "/^\w*$/", $string );
}


/** Generate a random unique id
 */
function uuid() {
	return bin2hex(random_bytes(20));
}

/** Check if a value is in a whitelist and return that value if it is.
 *  Else return a default value
 */
function whitelist( $check_value, $default_value, $whitelist ) {
	if( in_array( $check_value, $whitelist ) ) {
		return $check_value;
	} else {
		return $default_value;
	}
}

/** validate that a user has inserted a valid date.
 */
function validate_date( $date ) {
	$date_parts = explode( '-', $date );
	if( count( $date_parts ) != 3 ) {
		return false;
	}
	$day = intval( $date_parts[ 2 ] );
	$month = intval( $date_parts[ 1 ] );
	$year = intval( $date_parts[ 0 ] );

	if( $day * $month * $year == 0 ) {
		return false; //invalid number
	}

	if( $day < 0 || $month < 0 || $year < 0 ) {
		return false; //outside of range;
	} else if( $month > 12 ) {
		return false;
	} else if( $day > 31 ) {
		return false; //TODO check for maximum number of days in each month
	}

	if( intval( date( 'Y' ) ) >= $date ) {
		return true;
	}
	elseif( intval( date( 'n' ) ) >= $month ) {
		return true;
	} elseif( intval( date( 'j' ) ) >= $day ) {
		return true;
	}
	return false;
}

/** Count the number of commitments in the database */
function count_commitments() { //TODO make commitments expire
	$dbh = new \PDO( 'sqlite:db/commitments.sqlite3' );
	$query_result = $dbh -> query( 'SELECT COUNT(*) FROM commitments' );
	return $query_result -> fetchAll()[ 0 ][ 0 ]; //first row, first column
}

/** Load information on a certificate */
function generate_image_story_from_cert( $cert_id ) {
	if( $cert_id === null ) {
		return null;
	}
	$dbh = new \PDO( 'sqlite:db/commitments.sqlite3' );
	$sql = $dbh -> prepare( 'SELECT cert_type, name, extra_info FROM commitments WHERE cert_id = :cert_id' );
	$sql -> bindParam( 'cert_id', $cert_id );
	$sql -> execute();
	$data = $sql -> fetchAll( \PDO::FETCH_ASSOC );
	if( count( $data ) == 0 ) {
		return null;
	}
	$name = $data[ 0 ][ 'name' ];
	$extra_info = $data[ 0 ][ 'extra_info' ];
	$cert_type = $data[ 0 ][ 'cert_type' ];

	$text = "It is hereby certified that $name";
	if( $extra_info != null ) {
		$text .= ", further identified by &quot$extra_info&quot";
	}
	if( $cert_type == 'no-fly' ) {
		$text .= 'has commited to contribute to saving the climate by not using air travel';
	} else {
		$text .= 'has made a commitment to contribute to saving the climate  by only using air travel for causes of great personal or public importance';
	}

	$image_url = 'img/angel.jpg'; //TODO pass image parameter through GET
	if( $cert_type == 'world' ) {
		$image_url = 'img/world_climber.jpg';
	}

	$text_block = new \photo_story\TextBlock( 10, 10, $text, 'black' );
	$image = new \photo_story\StoryImage( 'certificate_image', $image_url );
	$image -> set_text( 'en', array( $text_block ) ); //TODO pass language through GET
	$story = new \photo_story\Story( 'certificate' );
	$story -> add_image( $image );
	return $story;
}

function fail( $msg ) {
	header( 'Content-Type: text/plain' );
	die( $msg );
}

function require_array_value( $param_name, $array, $error_message ) {
	if( !isset( $array[ $param_name ] ) ) {
		fail( $error_message );
	}
}

function require_post_parameter( $param_name, $error_message ) {
	require_array_value( $param_name, $_POST, $error_message );
}

function require_get_parameter( $param_name, $error_message ) {
	require_array_value( $param_name, $_GET, $error_message );
}

?>
