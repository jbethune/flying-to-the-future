<?php
require_once 'util.php';

function fail( $msg ) {
	util\fail( $msg );
}

function require_post_parameter( $param_name, $error_message ) {
	util\require_post_parameter( $param_name, $error_message );
}

require_post_parameter( 'name', 'Please provide your name' );
require_post_parameter( 'expiry_date', 'Please provide the expiry date' );
require_post_parameter( 'cert_type', 'Please provide the type of your certificate' );

$name = htmlentities( $_POST[ 'name' ] );
$cert_type = $_POST[ 'cert_type' ];

$expiry_date = $_POST[ 'expiry_date ' ] ?? 'invalid';
if( util\validate_date( $expiry_date ) ) {
	fail( 'Please provide a valid expiry date' );
}

$extra_info = null;
if( isset( $_POST[ 'extra_info' ] ) ) {
	$extra_info = htmlentities( $_POST[ 'extra_info' ] );
}


$background_image = util\whitelist(
	$_POST[ 'image' ] ?? 'angel.jpg',
	'angel.jpg',
	array( 'angel.jpg' ) //To be extended
);


$cert_id = util\uuid();

$dbh = new PDO( 'sqlite:db/commitments.sqlite3' );
$dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$dbh -> beginTransaction();
if( $extra_info == null ) {
	$sql = $dbh -> prepare( 'INSERT INTO commitments ( cert_id, cert_type, name ) VALUES ( :cert_id, :cert_type, :name )' );
} else {
	$sql = $dbh -> prepare( 'INSERT INTO commitments ( cert_id, cert_type, name, extra_info ) VALUES ( :cert_id, :cert_type, :name, :extra_info )' );
	$sql -> bindParam( ':extra_info', $extra_info );
}

$sql -> bindParam( ':cert_id', $cert_id );
$sql -> bindParam( ':cert_type', $cert_type );
$sql -> bindParam( ':name', $name );

if( $sql -> execute() ) {

}
$dbh -> commit();

header( "Location: certificate.php?cert_id=$cert_id&image=$background_image" );
?>
