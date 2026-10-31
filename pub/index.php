<?php
// percent-encode the characters that could break out of the attribute or the script string (same URL for the browser)
$query = strtr($_SERVER['QUERY_STRING'] ?? '', array('"' => '%22', "'" => '%27', '<' => '%3C', '>' => '%3E', '\\' => '%5C', ' ' => '%20'));
// TODO(tech-debt): QUERY_STRING is absent without a query (PHP built-in server); an empty string is kept. Intended: build the redirect target once. ?>
<HTML>
<HEAD>
<meta http-equiv="refresh" content="0;url=<?php print "themes/bismarck/index.php"."?".$query;?>">
</HEAD>
<BODY>
<SCRIPT>
window.location="<?php print "themes/bismarck/index.php"."?".$query;?>"
</SCRIPT>
</BODY>
</HTML>
