<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>授权</title>
</head>
<?php
	require_once dirname(__FILE__).'/config.php';
	require_once dirname(__FILE__).'/service/AlipayOauthService.php';

	$aop = new AlipayOauthService($config);

	$url = $aop->oauth();
	Header("Location: $url");
?>
</body>
</html>