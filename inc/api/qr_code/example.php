<?php
/*************************************************************
 * This script is developed by Arturs Sosins aka ar2rsawseen, http://webcodingeasy.com
 * Fee free to distribute and modify code, but keep reference to its creator
 *
 * This class generate QR [Quick Response] codes with proper metadata for mobile  phones
 * using google chart api http://chart.apis.google.com
 * Here are sources with free QR code reading software for mobile phones:
 * http://reader.kaywa.com/
 * http://www.quickmark.com.tw/En/basic/download.asp
 * http://code.google.com/p/zxing/
 *
 * For more information, examples and online documentation visit: 
 * http://webcodingeasy.com/PHP-classes/QR-code-generator-class
 **************************************************************/

include("qrcode.php");

$qr = new qrcode();

//link
$qr->link("http://code-snippets.co.cc");
echo "<p>Link</p>";
echo "<p><img src='".$qr->get_link()."' border='0'/></p>";

//bookmark
$qr->bookmark("WebcodingEasy.com", "http://webcodingeasy.com");
echo "<p>Bookmark</p>";
echo "<p><img src='".$qr->get_link()."' border='0'/></p>";

//text
$qr->text("Any UTF8 characters like �&#65533;ēū");
echo "<p>UTF8 text</p>";
echo "<p><img src='".$qr->get_link()."' border='0'/></p>";

//sms
//First parameter - phone number
//Second parameter - sms text
$qr->sms("12345678", "sms text");
echo "<p>SMS with phone number and text</p>";
echo "<p><img src='".$qr->get_link()."' border='0'/></p>";

//phone number
$qr->phone_number("12345678");
echo "<p>Telephone number</p>";
echo "<p><img src='".$qr->get_link()."' border='0'/></p>";

//email
//First param - email address
//Second param - email subject
//Third param - email text
$qr->email("test@test.com", "Testing email subject", "Testing email text");
echo "<p>Email with subject and message text</p>";
echo "<p><img src='".$qr->get_link(250)."' border='0'/></p>";

//geo location works on smartphones
//First param - latitude
//Second param - longitude
//Third param - height above earth in meters
$qr->geo("40.71872", "-73.98905", "100");
echo "<p>Geographical location</p>";
echo "<p><img src='".$qr->get_link()."' border='0'/></p>";

//wifi network configuration works on Android devices
//First param - Authentication type WPA or WEP
//Second param - Network SSID
//Third param - password
$qr->wifi("WEP", "wifi_name", "password");
echo "<p>Wifi configuration for Android devices</p>";
echo "<p><img src='".$qr->get_link()."' border='0'/></p>";

//starting i-appli aplication which needs to be downloaded beforehand
//First parameter ADF - URL accessed to acquire the ADF of i-αppli to be activated
//Second parameter CMD command - The designated boot command must be declared within the AllowPushBy key of the ADF beforehand.
//Third parameter array of parameters - up to 16 parameters sent to the i-αppli to be activated
$param = array();
$param[] = array("name" => "name1", "value" => "value1");
$param[] = array("name" => "name2", "value" => "value2");
$qr->iappli("http://www.nttdocomo.co.jp/test_appli.jam", "abcde", $param);
echo "<p>i-appli activation</p>";
echo "<p><img src='".$qr->get_link()."' border='0'/></p>";

//encoding files like gif, jpg midi files in QR codes
//First parameter type - QR codes for support (image/gif, image/jpeg, application/x-mld, audio/midi, audio/mid, application/x-toruca)
//Second parameter size of file - the total bytes of content data using decimal code
//Third parameter content itself - Designate the content data using binary code
$qr->content("image/gif", "1385", "zzz");
echo "<p>QR code with file content</p>";
echo "<p><img src='".$qr->get_link()."' border='0'/></p>";


//here is the way to output image
//header("Content-type: image/png");
//echo $qr->get_image();

//and here is the way to force image download
//$file = $qr->get_image();
//$qr->download_image($file)

?>