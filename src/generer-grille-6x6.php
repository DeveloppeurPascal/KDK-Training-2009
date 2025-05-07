<?php
	// ma-soluce.fr
	//
	// (c) Patrick Prémartin 18/04/2009
	// (c) Olf Software 2009
	//
	// Dernières modifications :
	//	29/04/2009 : création de ce fichier
	
		require_once(dirname(__FILE__)."/jeu.inc.php");
		$largeur = 6;
?><html>
	<head>
		<link rel="stylesheet" type="text/css" media="all" href="jeu.css" />
		<link rel="stylesheet" type="text/css" media="all" href="kdkt.css" />
		<title>Génération d'une grille de <?php print($largeur."x".$largeur); ?></title>
	</head>
	<body>
		<center>
<?php
		if (jeu_creer(_JEU_KDKT,$largeur))
		{
			jeu_sauver();
			jeu_afficher(_AFFICHAGE_HTML);
			print ("<br />");
			jeu_afficher(_AFFICHAGE_HTML_SOLUTION);
		}
?>
		</center>
	</body>
</html>