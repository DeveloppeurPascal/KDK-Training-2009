<?php
	// ma-soluce.fr
	//
	// (c) Patrick Prémartin 18/04/2009
	// (c) Olf Software 2009
	//
	// Dernières modifications :
	//	18/04/2009 : création de ce fichier pour KT.01
	//	19/04/2009 : modification du nombre de grilles générées à partir de KT.02
	
		require_once(dirname(__FILE__)."/jeu.inc.php");
?><html>
	<head>
		<link rel="stylesheet" type="text/css" media="all" href="jeu.css" />
		<link rel="stylesheet" type="text/css" media="all" href="kdkt.css" />
		<title>Titre du ebook</title>
	</head>
	<body>
		<center>
		<h1>Couverture du ebook</h1>
		<h1>Copyright & licence d'utilisation / distribution du ebook</h1>
		<h1>Règles du jeu, avec grille exemple</h1>
<?php
		if (jeu_creer(_JEU_KDKT,3))
		{
			jeu_afficher(_AFFICHAGE_HTML);
			print ("<br />");
			jeu_afficher(_AFFICHAGE_HTML_SOLUTION);
		}
?>
		<h1>Les grilles</h1>
<?php
		// 8 grilles en 3x3, à raison de 4 par page pour faire 2 pages
		for ($i = 0; $i < 8; $i++)
		{
			if (jeu_creer(_JEU_KDKT,3))
			{
				jeu_sauver();
				jeu_afficher(_AFFICHAGE_HTML);
				print ("<br />");
			}
		}
		// 8 grilles en 4x4, à raison de 2 par page pour faire 4 pages
		for ($i = 0; $i < 8; $i++)
		{
			if (jeu_creer(_JEU_KDKT,4))
			{
				jeu_sauver();
				jeu_afficher(_AFFICHAGE_HTML);
				print ("<br />");
			}
		}
		// 8 grilles en 5x5, à raison de 2 par page pour faire 4 pages
		for ($i = 0; $i < 8; $i++)
		{
			if (jeu_creer(_JEU_KDKT,5))
			{
				jeu_sauver();
				jeu_afficher(_AFFICHAGE_HTML);
				print ("<br />");
			}
		}
		// 2 grille en 6x6, à raison de 1 par page pour faire 2 pages
		for ($i = 0; $i < 2; $i++)
		{
			if (jeu_creer(_JEU_KDKT,6))
			{
				jeu_sauver();
				jeu_afficher(_AFFICHAGE_HTML);
				print ("<br />");
			}
		}
		// 2 grille en 7x7, à raison de 1 par page pour faire 2 pages
		for ($i = 0; $i < 2; $i++)
		{
			if (jeu_creer(_JEU_KDKT,7))
			{
				jeu_sauver();
				jeu_afficher(_AFFICHAGE_HTML);
				print ("<br />");
			}
		}
?>
		<h1>Page de fin, liens vers sites de VPC & co</h1>
		</center>
	</body>
</html>
