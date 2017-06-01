<?PHP
$text = 'Larger content (concatenated SMS, multipart or segmented S ஆன்லைனில் ';//'ஆன்லைனில் Google உள்ளீட்டு கருவியை முயற்சிக்கவும்' ; //"\\". //'"';//' ';

print $text . "<br>";
print ' isGsm7bit '.isGsm7bit($text). "<br>";
print 'sms segment '.getNumberOfSMSsegments($text). "<br>";

function getNumberOfSMSsegments($text, $MaxSegments=6){
/*
http://en.wikipedia.org/wiki/SMS
Larger content (concatenated SMS, multipart or segmented SMS, or "long SMS") can be sent using multiple messages, 
in which case each message will start with a user data header (UDH) containing segmentation information. 
Since UDH is part of the payload, the number of available characters per segment is lower: 
153 for 7-bit encoding, 
134 for 8-bit encoding and 
67 for 16-bit encoding. 
The receiving handset is then responsible for reassembling the message and presenting it to the user as one long message. 
While the standard theoretically permits up to 255 segments,[35] 6 to 8 segment messages are the practical maximum, 
and long messages are often billed as equivalent to multiple SMS messages. See concatenated SMS for more information. 
Some providers have offered length-oriented pricing schemes for messages, however, the phenomenon is disappearing.
*/
$TotalSegment=0;
$textlen = mb_strlen($text);
if($textlen==0) return false; //I can see most mobile devices will not allow you to send empty sms, with this check we make sure we don't allow empty SMS

if(isGsm7bit($text)){ echo '7-bit';
    $SingleMax=160;
    $ConcatMax=153;
}else{ echo 'UCS-2 Encoding (16-bit)';
    $SingleMax=70;
    $ConcatMax=67;
}

if($textlen<=$SingleMax){
    $TotalSegment = 1;
}else{
    $TotalSegment = ceil($textlen/$ConcatMax);
}

if($TotalSegment>$MaxSegments) return false; //SMS is very big.
return $TotalSegment;
}

function isGsm7bit($text){
$gsm7bitChars = "\\\@£\$¥èéùìòÇ\nØø\rÅåΔ_ΦΓΛΩΠΨΣΘΞÆæßÉ !\"#¤%&'()*+,-./0123456789:;<=>?¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ§¿abcdefghijklmnopqrstuvwxyzäöñüà^{}[~]|€";
$textlen = mb_strlen($text);
for ($i = 0;$i < $textlen; $i++){
    if ((strpos($gsm7bitChars, $text[$i])==false) && ($text[$i]!="\\")){return false;} //strpos not     able to detect \ in string
}
return true;
}

?>
