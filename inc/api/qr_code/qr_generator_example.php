<?php
if(isset($_POST['type']))
{
	//here we got all data from submitted form
	//we include class file
	include("qrcode.php");
	//create an instance
	$qr = new qrcode();
	//then we check what type of information user wanted to create qr code
	//to know what are possible types and what information needs to inserted, check the example file
	switch($_POST['type'])
	{
		case "url":
			//then we use submitted information here, word inside $_POST[] brackets must match value of name attribute in the input field
			//<p>http://<input type='text' name='url' 
			$qr->link($_POST['url']);
		break;
		case "txt":
			$qr->text($_POST['txt']);
		break;
		case "sms":
			$qr->sms($_POST['sms_phone'], $_POST["sms_text"]);
		break;
		case "bookmark":
			$qr->bookmark($_POST['mms_phone'], $_POST["mms_text"]);
		break;
		case "tel":
			$qr->phone_number($_POST['tel']);
		break;
		case "contactinfo":
			$qr->contact_info($_POST["contact_name"], $_POST["contact_address"], $_POST["contact_phone"], $_POST["contact_email"]);
		break;
		case "email":
			$qr->email($_POST["email_address"], $_POST["email_subject"], $_POST["email_txt"]);
		break;
		case "geo":
			$qr->geo($_POST["geo_lat"], $_POST["geo_lon"], $_POST["geo_above"]);
		break;
		case "wifi":
			$qr->wifi($_POST["wifi_aut"], $_POST["wifi_ssid"], $_POST["wifi_pass"]);
		break;
	}
	//here we specify inputted size and get link to image
	echo "<p><img src='".$qr->get_link($_POST['size'])."' border='0'/></p>";
	//to download image
	$link = $qr->get_link();
	$qr->download_image($link);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>QR-codes</title>
<script type="text/javascript">
//this is a javascript function that changes div elements, based on which type is selected
//to make this function work you need to make sure that
//value of select option element matches ID of div element
//for example <option value="link">Link</option> value link matches <div id='link'>
//and all that divs must be in a <div id='forms'> div element
//so if you want to add new type, you must:
// first add new option to select element
//add new div element inside <div id='forms'></div>
//make sure id of your new div matches value of new select option element
function change_type(id)
{
	var divs = document.getElementById('forms').getElementsByTagName('div');
	for(var i = 0; i < divs.length; i++)
	{
		divs[i].style.display = "none";
	}
	if(document.getElementById(id))
	{
		document.getElementById(id).style.display = "block";
	}
}
</script>
</head>
<body>
<form action='' method='post'>
<!-- Here is a select option element to change divs -->
<p>Select type: <select name='type' onchange='change_type(this.value)'>
<option value="url" <?php if(isset($_POST['type']) && $_POST['type'] == "url") echo "selected";?>>Link</option>
<option value="txt" <?php if(isset($_POST['type']) && $_POST['type'] == "txt") echo "selected";?>>Text</option>
<option value="sms" <?php if(isset($_POST['type']) && $_POST['type'] == "sms") echo "selected";?>>SMS</option>
<option value="bookmark" <?php if(isset($_POST['type']) && $_POST['type'] == "bookmark") echo "selected";?>>Bookmark</option>
<option value="tel" <?php if(isset($_POST['type']) && $_POST['type'] == "tel") echo "selected";?>>Phone number</option>
<option value="contactinfo" <?php if(isset($_POST['type']) && $_POST['type'] == "contactinfo") echo "selected";?>>Contact information</option>
<option value="email" <?php if(isset($_POST['type']) && $_POST['type'] == "email") echo "selected";?>>Email</option>
<option value="geo" <?php if(isset($_POST['type']) && $_POST['type'] == "geo") echo "selected";?>>Geographical information</option>
<option value="wifi" <?php if(isset($_POST['type']) && $_POST['type'] == "wifi") echo "selected";?>>Wifi Network config</option>
</select></p>
<p>Image size in pixels: <input type='text' size='5' name='size' value='250'/></p>
<!-- This div element contains all form div element -->
<div id='forms'>
<!-- This div is for link -->
<div id='url'>
<p>Link:</p>
<p>http://<input type='text' name='url' value='<?php if(isset($_POST['url'])) echo $_POST['url'];?>'/></p>
</div>
<!-- This div is for text, style='display:none;' means it won't be visible from start -->
<div id='txt' style='display:none;'>
<p>Text:</p>
<p><textarea name='txt' rows='10' cols='30'><?php if(isset($_POST['txt'])) echo $_POST['txt'];?></textarea></p>
</div>
<!-- This div is for sms -->
<div id='sms' style='display:none;'>
<p>Phone:</p>
<p><input type='text' name='sms_phone' value='<?php if(isset($_POST['sms_phone'])) echo $_POST['sms_phone'];?>'/></p>
<p>Text:</p>
<p><textarea name='sms_text' rows='10' cols='30'><?php if(isset($_POST['sms_text'])) echo $_POST['sms_text'];?></textarea></p>
</div>
<div id='bookmark' style='display:none;'>
<p>Title:</p>
<p><input type='text' name='bookmark_title' value='<?php if(isset($_POST['bookmark_title'])) echo $_POST['bookmark_title'];?>'/></p>
<p>url:</p>
<p><input name='bookmark_url' value='<?php if(isset($_POST['bookmark_url'])) echo $_POST['bookmark_url'];?>'/></p>
</div>
<div id='tel' style='display:none;'>
<p>Phone nubmer:</p>
<p><input type='text' name='tel' value='<?php if(isset($_POST['tel'])) echo $_POST['tel'];?>'/></p>
</div>
<div id='contactinfo' style='display:none;'>
<p>Contact information</p>
<p>Name:</p>
<p><input type='text' name='contact_name' value='<?php if(isset($_POST['contact_name'])) echo $_POST['contact_name'];?>'/></p>
<p>Address</p>
<p><textarea name='contact_address' rows='10' cols='30'>
<?php if(isset($_POST['contact_address'])) echo $_POST['contact_address'];?>
</textarea></p>
<p>Phone number:</p>
<p><input type='text' name='contact_tel' value='<?php if(isset($_POST['contact_tel'])) echo $_POST['contact_tel'];?>'/></p>
<p>Email address:</p>
<p><input type='text' name='contact_email' value='<?php if(isset($_POST['contact_email'])) echo $_POST['contact_email'];?>'/></p>
</div>
<div id='email' style='display:none;'>
<p>Email(supported by latest phones)</p>
<p>Reciever:</p>
<p><input type='text' name='email_address' value='<?php if(isset($_POST['email_address'])) echo $_POST['email_address'];?>'/></p>
<p>Subject:</p>
<p><input type='text' name='email_subject' value='<?php if(isset($_POST['email_subject'])) echo $_POST['email_subject'];?>'/></p>
<p>Message:</p>
<p><textarea name='email_txt' rows='10' cols='30'>
<?php if(isset($_POST['email_txt'])) echo $_POST['email_txt'];?>
</textarea></p>
</div>
<div id='geo' style='display:none;'>
<p>Geographical information(supported by latest phones)</p>
<p>Latitude:</p>
<p><input type='text' name='geo_lat' value='<?php if(isset($_POST['geo_lat'])) echo $_POST['geo_lat'];?>'/></p>
<p>Longitude:</p>
<p><input type='text' name='geo_lon' value='<?php if(isset($_POST['geo_lon'])) echo $_POST['geo_lon'];?>'/></p>
<p>Meters above earth:</p>
<p><input type='text' name='geo_above' value='<?php if(isset($_POST['geo_above'])) echo $_POST['geo_above'];?>'/></p>
</div>
<div id='wifi' style='display:none;'>
<p>Wifi Network configuration(supported by Android devices)</p>
<p>Authentication type:</p>
<p><select name='wifi_aut'>
<option value='WEP'>WEP</option>
<option value='WAP'<?php if(isset($_POST['wifi_aut']) && $_POST['wifi_aut'] == "WAP") echo "selected";?>>WAP</option>
</select></p>
<p>Network SSID:</p>
<p><input type='text' name='wifi_ssid' value='<?php if(isset($_POST['wifi_ssid'])) echo $_POST['wifi_ssid'];?>'/></p>
<p>Password:</p>
<p><input type='text' name='wifi_pass' value='<?php if(isset($_POST['wifi_pass'])) echo $_POST['wifi_pass'];?>'/></p>
</div>
</div>
<p><input type='submit' value='Get code'/></p>
</form>
<script type="text/javascript">
	window.onload = function () 
	{ 
		//this piece of code is so value selected by user will be shown after form submission
		<?php
		if(isset($_POST['type']))
		{
			echo "change_type('".$_POST['type']."');";
		}
		?>
	}
</script>
</body>
</html>