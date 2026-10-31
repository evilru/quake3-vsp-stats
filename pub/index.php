<?php // TODO(tech-debt): QUERY_STRING is absent without a query (PHP built-in server); an empty string is kept. Intended: build the redirect target once. ?>
<HTML>
<HEAD>
<meta http-equiv="refresh" content="0;url=<?php print "themes/bismarck/index.php"."?".($_SERVER['QUERY_STRING'] ?? '');?>">
</HEAD>
<BODY>
<SCRIPT>
window.location="<?php print "themes/bismarck/index.php"."?".($_SERVER['QUERY_STRING'] ?? '');?>"
</SCRIPT>
</BODY>
</HTML>
