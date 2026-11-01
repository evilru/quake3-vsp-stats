<?php
// percent-encode the characters that could break out of the attribute or the script string (same URL for the browser)
$query = strtr($_SERVER['QUERY_STRING'] ?? '', array('"' => '%22', "'" => '%27', '<' => '%3C', '>' => '%3E', '\\' => '%5C', ' ' => '%20'));
?>
<HTML>
<HEAD>
<meta http-equiv="refresh" content="0;url=<?php print "pub/themes/bismarck/index.php"."?".$query;?>">
</HEAD>
<BODY>
<SCRIPT>
window.location="<?php print "pub/themes/bismarck/index.php"."?".$query;?>"
</SCRIPT>
</BODY>
</HTML>
