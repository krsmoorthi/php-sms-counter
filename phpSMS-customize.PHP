<?php
$content = 'Larger content (concatenated SMS, multipart or segmented SMS, or "long SMS") can be sent using multiple messages, in which case each message will start with a user data header (UDH) containing segmentation information. Since UDH is part of the payload, the number of available characters per segment is lower: ஆன்லைனில் Google உள்ளீட்டு கருவியை முயற்சிக்கவும்இணையத்தில்'; // 'ஆன்லைனில் Google உள்ளீட்டு கருவியை முயற்சிக்கவும்இணையத்தில் எங்கிருந்தாலும் நீங்கள் தேர்வுசெய்யும் மொழியில் தட்டச்சு செய்வதை Google உள்ளீட்டு கருவி எளிதாக்குகிறது. மேலும் அறிக இதை முயற்சிக்க, கீழே உங்கள் மொழி மற்றும் உள்ளீட்டு கருவியைத் தேர்வுசெய்து, தட்டச்சு செய்யத் தொடங்கவும்.' ; //"\\". //'"';//' ';

print $content . "<BR>";
print ' isGsm7bit '.isGsm7bit($content). "<br>";
print ' getNumberOfSMSsegments '.getNumberOfSMS($content). "<br>";

function getNumberOfSMS($content, $MaxSMS=10){
	/*
	Larger content (concatenated SMS, multipart or segmented SMS, or "long SMS") can be sent using multiple messages, in which case each message will start with a user data header (UDH) containing segmentation information. Since UDH is part of the payload, the number of available characters per segment is lower: 
	153 for 7-bit encoding, 
	134 for 8-bit encoding and 
	67 for 16-bit encoding. 
	The receiving handset is then responsible for reassembling the message and presenting it to the user as one long message. While the standard theoretically permits up to 255 segments,[35] 6 to 8 segment messages are the practical maximum, and long messages are often billed as equivalent to multiple SMS messages. See concatenated SMS for more information. Some providers have offered length-oriented pricing schemes for messages, however, the phenomenon is disappearing.	*/
	$TotalSMS 		= 0;
	$textLength 	= mb_strlen($content, 'utf8'); // .'<br>';
	// check empty SMS
	if($textLength == 0) return false;
	// check 7-bit or 16-bit
	if(isGsm7bit($content)) { // echo '7-bit';
	    $SingleMax=160;
	    $ConcatMax=153;
	} else { // echo 'UCS-2 Encoding (16-bit)';
	    $SingleMax=70;
	    $ConcatMax=67;
	}
	// SMS count
	if($textLength<=$SingleMax) {
	    $TotalSMS = 1;
	} else {
	    $TotalSMS = ceil($textLength/$ConcatMax);
	}
	// Check SMS count
	if($TotalSegment>$MaxSMS) return false;

	return $TotalSMS;
}

function isGsm7bit($content){
	$gsm7bitChars = "\\\@£\$¥èéùìòÇ\nØø\rÅåΔ_ΦΓΛΩΠΨΣΘΞÆæßÉ !\"#¤%&'()*+,-./0123456789:;<=>?¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ§¿abcdefghijklmnopqrstuvwxyzäöñüà^{}[~]|€";
	$textLength = mb_strlen($content);
	for ($i = 0;$i < $textLength; $i++){
	    if ((strpos($gsm7bitChars, $content[$i])==false) && ($content[$i]!="\\")){return false;} //strpos not able to detect \ in string
	}
	return true;
}

?>