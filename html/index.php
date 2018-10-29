<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Flying to the future - Getting passive against climate change!</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script type="module" src="main.js"></script>
</head>
<body>

<?php

require_once 'util.php';
require_once 'photo_story.php';
$language = util\get_user_language_from_request();
$language = util\whitelist( $language, 'en', ['en'] ); //make sure that the website uses a language that is supported
$story = photo_story\load_with_language( 'default', $language );
if( $story === null ) {
	echo 'Error: Could not load image story<br>';
}
$story -> render( $language );
?>

<hr />

<p>
You are not alone: There are 
<?php
echo util\count_commitments();
?>
 other people who have an active commitment for change on this website!
</p>
<hr />

<h1>What you can do</h1>

<p>
For many people it is nothing special to fly. It is expected to just take a plane to quickly travel somewhere, simply because it is possible.
And yes, flying has been one of the greatest benefits of human inventiveness and we hope that the not so far future will still have air travel.
But the way we are flying <b>right now</b> is highly damaging to the environment and climate. The problems grow greater and greater with the number of people who take flying for granted.
</p>

<p>
We need to rethink what we expect from our own mobility. And we need to be prepared that this will also include a cultural change, because mobility is a part of our modern culture.
</p>

<p>
This is where you come in: You can say <b>no</b> to air travel and back up your stance by an official commitment from this website! And the best part is: Your commitment has an expiration date, because we believe that we will find a solution in the future!
</p>

<p>Are you living abroad? Do you have relatives who live very far away? We also offer an <a href="citizen-of-the-world.php">alternative certificate</a> for you!</p>


<form action="register.php" method="POST">
<fieldset><legend>Commitment</legend>
<label><input type="text" name="name">Your name</label><br>
<label><input type="text" name="extra_info">additional identifying information if you have a very common name (e.g. which city you are from)</label><br>
<label><input type="date" value="<?php
echo date('Y')+1; echo date('-m-d'); #one year in the future
?>" name="expiry_date">Expiration date for your commitment</label><br />
<p>
In the interest of future generations, I hereby commit to not use air travel until the previosly stated date unless a serious emergency arises that makes air travel necessary.
</p>
<input type="hidden" name="cert_type" value="no-fly" />
<input type="submit" value="get your commitment certificate">
</fieldset>
</form>

<p>
Once you have made your pledge (completely free of charge) feel free to show off your new certificate!
</p>

<p><img src="img/science.png" title="science">Are you a scientist and you need to travel a lot to conferences? Check out this <a href="scientist.html">letter template</a> to give a strong excuse for not going.</p>

<p><img src="img/GitHub-Mark-32px.png" title="GitHub Mascot">Are you a software developer? Check out <a href="https://github.com/jbethune/flying-to-the-future">the code of this project</a> on GitHub</p>

</body>
</html> 
