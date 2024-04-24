<?php 
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
header ("Location: $protocol$_SERVER[SERVER_NAME]");
exit;
?>