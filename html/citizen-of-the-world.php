<!DOCTYPE html>
<html>
<head><title>Certificates for Individuals living abroad</title></head>
<body>
<h1>Certificates for Individuals living abroad</h1>

<h2>The Citizen of the World Pass</h2>
<p>
Some of us live far away from home. We have left loved ones behind to pursue our
careers or to be close to significant others. Taking a plane is the only
realistic way to still see those people that mean so much to us.
</p>

<p>
We acknowledge that not everyone has the freedom to live without air travel. We
acknowledge that life can be complicated. And most importantly, we acknowledge
you as an individual. Your own situation might not allow a 100% air-travel-free
lifestyle, but you are very aware of your choices and consequences. We also want
to support people like you and we still offer the <em>Citizen of the World
Pass</em> as an alternative to the regular <em>no-fly commitment</em>.
</p>

<form action="register.php" method="POST">
<fieldset><legend>Commitment</legend>
<label><input type="text" name="name">Your name</label><br>
<label><input type="text" name="extra_info">additional identifying information if you have a very common name (e.g. which city you are from)</label><br>
<label><input type="date" value="<?php
echo date('Y')+1; echo date('-m-d'); #one year in the future
?>" name="expiry_date">Expiration date for your commitment</label><br />
<p>
In the interest of future generations, I hereby commit to only use air travel, until the previosly stated date, for reasons that are of great personal or public importance.
</p>
<input type="hidden" name="cert_type" value="world" />
<input type="submit" value="get your commitment certificate">
</fieldset>
</form>

<!--
<div style="background-image:url(img/world.jpg); width: 1024px; height:
724px; border: 2px solid black">

<p style="font-size: 40px; width: 512px; text-align: center">
It is hereby certified that <u>John Doe</u>, who is a citizen of the world, has
made a silver-tier commitment to contribute to saving the climate by only using
air travel for causes that are of great personal or public importance.</p>
</div>
-->

</body>
</html>
