
<?php

if( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ) 
 $OS = "WIN";
else
 $OS = "UNIX";

echo "OS:".$OS."(".php_uname('s').")<br>";
echo "PHP:".phpversion()."<br>";

if (function_exists('xml_set_element_handler')) {
    echo "<font color=blue>XML functions are available.</font><br />\n";
} else {
    echo "<font color=red>XML functions are not available.</font><br />\n";
	if( $OS == "UNIX" ) echo "다음 URL을 참고해 주세요 http://kr2.php.net/manual/en/ref.xml.php ";
	else echo "다음 URL을 참고해 주세요 http://www.php.net/manual/en/install.windows.extensions.php";
}
if (function_exists('openssl_get_publickey')) {
    echo "<font color=blue>OPENSSL functions are available.</font><br />\n";
} else {
    echo "<font color=red>OPENSSL functions are not available.</font><br />\n";
		if( $OS == "UNIX" ) echo "다음 URL을 참고해 주세요 http://kr2.php.net/manual/en/ref.openssl.php ";
	else echo "다음 URL을 참고해 주세요 http://www.php.net/manual/en/install.windows.extensions.php";

}
if (function_exists('socket_create')) {
    echo "<font color=blue>SOCKET functions are available.</font><br />\n";
} else {
    echo "<font color=red>SOCKET functions are not available.</font><br />\n";
	if( $OS == "UNIX" ) echo "다음 URL을 참고해 주세요 http://kr2.php.net/manual/en/ref.sockets.php ";
	else echo "다음 URL을 참고해 주세요 http://www.php.net/manual/en/install.windows.extensions.php";

}
if (function_exists('mcrypt_cbc')) {
    echo "<font color=blue>MCRYPT functions are available.</font><br />\n";
} else {
    echo "<font color=red>MCRYPT functions are not available.</font><br />\n";
	if( $OS == "UNIX" ) echo "다음 URL을 참고해 주세요 http://kr2.php.net/manual/en/ref.mcrypt.php ";
	else echo "다음 URL을 참고해 주세요 http://www.php.net/manual/en/install.windows.extensions.php";

}



?> 
