<?php
	// ma-soluce.fr
	//
	// (c) Patrick Prémartin 18/04/2009
	// (c) Olf Software 2009
	//
	// Dernières modifications :
	//	18/04/2009 : création de ce fichier

	$allopass = trim(strip_tags(stripslashes($_GET["RECALL"])));
	$auth = "XXXXXXXXXX/XXXXXXXXXX/XXXXXXXXXX"; // disabled - 20250507
	if ("" != $allopass)
	{
		$r = @file("http://payment.allopass.com/api/checkcode.apu?code=".urlencode($allopass)."&auth=".urlencode($auth));
		if(substr($r[0],0,2) == "OK")
		{
			$_GET["op"] = 2;
			setcookie("acces_autorise","oui");
			$_COOKIE["acces_autorise"] = "oui";
			$_GET["reference"] = trim(strip_tags(stripslashes($_GET["DATAS"])));
			include (dirname(__FILE__)."/index.php");
		}
		else
		{
			$reference = trim(strip_tags(stripslashes($_GET["DATAS"])));
			include (dirname(__FILE__)."/_erreur_code.html");
		}
	}
