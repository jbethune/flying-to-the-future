<?php
require_once 'util.php';

util\require_get_parameter( 'cert_id', 'Please provide a certificate ID' );

$cert_id = $_GET[ 'cert_id' ];
$story = util\generate_image_story_from_cert( $cert_id );

if( $story === null ) {
	util\fail( 'Invalid certificate' );
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Certificate for saving the climate</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script type="module" src="main.js"></script>
</head>
<body>

<?php
$story -> render( 'en' );
?>

</body>
</html> 
