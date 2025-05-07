<?php
	// ma-soluce.fr
	//
	// (c) Patrick Prémartin 18/04/2009
	// (c) Olf Software 2009
	//
	// Dernières modifications :
	//	18/04/2009 : création de ce fichier

	if ("127.0.0.1" == $_SERVER["REMOTE_ADDR"]) // accès en local (donc à priori pour des tests)
	{
		setcookie("acces_autorise","oui");
	}

	require_once(dirname(__FILE__)."/jeu.inc.php");

	$op = 1*($_POST["op"].$_GET["op"]);
	switch ($op)
	{
		case 1 : // un numéro de référence a été saisi
			$reference = trim(strip_tags(stripslashes($_POST["reference"])));
			if (jeu_charger($reference))
			{
				if ("oui" == $_COOKIE["acces_autorise"])
				{
					include (dirname(__FILE__)."/_solution.html");
				}
				else
				{
					include (dirname(__FILE__)."/_allopass.html");
				}
			}
			else
			{
				include (dirname(__FILE__)."/_jeu_non_trouve.html");
			}
			break;
		case 2 : // accès après paiement Allopass
			$reference = trim(strip_tags(stripslashes($_GET["reference"])));
			if (jeu_charger($reference))
			{
				if ("oui" == $_COOKIE["acces_autorise"])
				{
					include (dirname(__FILE__)."/_solution.html");
				}
				else
				{
					include (dirname(__FILE__)."/_allopass.html");
				}
			}
			else
			{
				include (dirname(__FILE__)."/_jeu_non_trouve.html");
			}
			break;
		default : // affichage de la page d'accueil par défaut
			include (dirname(__FILE__)."/_accueil.html");
	}
