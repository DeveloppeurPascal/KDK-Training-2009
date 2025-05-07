<?php
	// ma-soluce.fr
	//
	// (c) Patrick Prémartin 18/04/2009
	// (c) Olf Software 2009
	//
	// Dernières modifications :
	//	18/04/2009 : création de ce fichier

	require_once(dirname(__FILE__)."/jeu-param.inc.php");
	unset($kdkt);
	kdkt_initialiser();
	
	function kdkt_nouvelle_valeur($taille,$grille,$x,$y)
	{
		$valeur = mt_rand() % $taille;
		$nb = 0;
		$ok = false;
		while (! $ok)
		{
			$nb++;
			if ($nb > $taille)
			{
				// on a fait un tour complet pour l'attribution d'une valeur, elle ne peut pas l'être, on sort car la grille générée n'est pas correcte
				return 0;
			}
			if ($valeur < $taille)
			{
				$valeur++;
			}
			else
			{
				$valeur=1;
			}
			$ok = true;
			for ($i = 0; $ok && ($i < $taille); $i++)
			{
				$ok = $ok && ($grille[$x][$i]->valeur != $valeur) && ($grille[$i][$y]->valeur != $valeur);
			}
		}
		return $valeur;
	}

	function kdkt_dessin_de_la_grille($taille,$grille,$zone,$mode=_AFFICHAGE_HTML)
	{
		$avec_solution = (_AFFICHAGE_HTML_SOLUTION == $mode);
		$dessin = "<table id=\"grille\">";
		for ($y = 0; $y < $taille; $y++)
		{
			$dessin .= "<tr>";
			for ($x = 0; $x < $taille; $x++)
			{
				$classe = "";
				if ($x+1 < $taille)
				{
					$classe .= (("" != $classe)?" ":"").(($grille[$x+1][$y]->zone == $grille[$x][$y]->zone)?"clairdroite":"foncedroite");
				}
				if ($y+1 < $taille)
				{
					$classe .= (("" != $classe)?" ":"").(($grille[$x][$y+1]->zone == $grille[$x][$y]->zone)?"clairbas":"foncebas");
				}
				$dessin .= "<td class=\"".$classe."\">";
				if (! $zone[$grille[$x][$y]->zone]->affichee)
				{
					$dessin .= $zone[$grille[$x][$y]->zone]->valeur.(("=" != $zone[$grille[$x][$y]->zone]->operateur)?" ".$zone[$grille[$x][$y]->zone]->operateur:"");
					$zone[$grille[$x][$y]->zone]->affichee = true;
				}
				if (($avec_solution) || ("=" == $zone[$grille[$x][$y]->zone]->operateur))
				{
					$dessin .= "<br /><span class=\"solution\">".$grille[$x][$y]->valeur."</span>";
				}
				$dessin .= "</td>";
			}
			$dessin .= "</tr>";
		}
		$dessin .= "</table>";

		return $dessin;
	}

	function kdkt_initialiser()
	{
		global $kdkt;
		$kdkt->taille = 0;
		// $kdkt->grille 
		// $kdkt->zone
		return true;
	}
	
	function kdkt_creer($taille=3)
	{
		global $kdkt;
		kdkt_effacer();

		if (($taille < 3) || ($taille > 10))
		{
			return false;
		}
		else
		{
			$grille_valide = false;
			while (! $grille_valide)
			{
				$grille_valide = true;
				// Initialisation de la grille de jeu
				unset($grille);
				for ($x = 0; $x < $taille; $x++)
				{
					for ($y = 0; $y < $taille; $y++)
					{
						$grille[$x][$y]->zone = -1;
						$grille[$x][$y]->valeur = -1;
					}
				}

				// Remplissage de la grille de jeu
				for ($x = 0; $x < $taille; $x++)
				{
					for ($y = 0; $y < $taille; $y++)
					{
						$grille[$x][$y]->valeur = kdkt_nouvelle_valeur($taille,$grille,$x,$y);
						$grille_valide = $grille_valide && (0 < $grille[$x][$y]->valeur);
					}
				}
			}

			// Création des zones dans lesquelles les chiffres sont regroupés
			unset($zone);
			$nb_cases_maxi = (($taille > 5)?5:(($taille > 4)?4:3)); // de 3 à 5 cases maxi par zone
			$nb_zones = 0;
			for ($y = 0; $y < $taille; $y++)
			{
				for ($x = 0; $x < $taille; $x++)
				{
					if (($x > 0) && ($y > 0) && ($grille[$x-1][$y]->zone == $grille[$x][$y-1]->zone) && ($grille[$x-1][$y]->zone != $grille[$x-1][$y-1]->zone))
					{ // fermeture des zones angulaires avec le pied à gauche, genre L inversé
						$grille[$x][$y]->zone = $grille[$x-1][$y]->zone;
						$zone[$grille[$x][$y]->zone]->nb_case++;
					}
					else
					{
						$dx = 0;
						$dy = 0;
						switch (mt_rand() % 2)
						{
							case 1 :
								switch (mt_rand() % 3)
								{
									case 2 :
										$dx = 1;
										break;
									case 1 :
										$dx = 0;
										break;
									default :
										$dx = -1;
										break;
								}
								break;
							default :
								switch (mt_rand() % 3)
								{
									case 2 :
										$dy = 1;
										break;
									case 1 :
										$dy = 0;
										break;
									default :
										$dy = -1;
										break;
								}
								break;
						}
						if ($x+$dx < 0)
						{
							$dx = 0;
						}
						if ($x+$dx > $taille-1)
						{
							$dx = 0;
						}
						if ($y+$dy < 0)
						{
							$dy = 0;
						}
						if ($y+$dy > $taille-1)
						{
							$dy = 0;
						}
						if ((0 > $grille[$x+$dx][$y+$dy]->zone) || ($nb_cases_maxi <= $zone[$grille[$x+$dx][$y+$dy]->zone]->nb_case))
						{
							$zone[$nb_zones]->operateur = "=";
							$zone[$nb_zones]->valeur = -1;
							$zone[$nb_zones]->nb_case = 1;
							$zone[$grille[$x][$y]->zone]->affichee = false;
							$grille[$x][$y]->zone = $nb_zones;
							$nb_zones++;
						}
						else
						{
							$grille[$x][$y]->zone = $grille[$x+$dx][$y+$dy]->zone;
							$zone[$grille[$x][$y]->zone]->nb_case++;
						}
					}
				}
			}

			// Elimination du surplus de cases unitaires
			for ($y = 0; $y < $taille; $y++)
			{
				for ($x = 0; $x < $taille; $x++)
				{
					if (1 == $zone[$grille[$x][$y]->zone]->nb_case)
					{
						if (($x < $taille-1) && (1 == $zone[$grille[$x+1][$y]->zone]->nb_case))
						{
							$grille[$x+1][$y]->zone = $grille[$x][$y]->zone;
							$zone[$grille[$x][$y]->zone]->nb_case++;
						}
						else if (($y < $taille-1) && (1 == $zone[$grille[$x][$y+1]->zone]->nb_case))
						{
							$grille[$x][$y+1]->zone = $grille[$x][$y]->zone;
							$zone[$grille[$x][$y]->zone]->nb_case++;
						}
					}
				}
			}

			// Affectation des opérations aux différentes zones
			reset($zone);
			while (list($num_zone,$value) = each($zone))
			{
				if (1 == $zone[$num_zone]->nb_case)
				{
					$zone[$num_zone]->operateur = "=";
				}
				else
				{
					$zone[$num_zone]->operateur = (((50 > mt_rand(0,200)) && (2 == $zone[$num_zone]->nb_case))?"/":(((100 > mt_rand(0,200)) && (2 == $zone[$num_zone]->nb_case))?"-":(((100 > mt_rand(0,200)) && (4 > $zone[$num_zone]->nb_case))?"x":"+")));
				}
			}
			
			// Calcul de la valeur des différentes zones
			for ($x = 0; $x < $taille; $x++)
			{
				for ($y = 0; $y < $taille; $y++)
				{
					if (-1 == $zone[$grille[$x][$y]->zone]->valeur)
					{
						$zone[$grille[$x][$y]->zone]->valeur = $grille[$x][$y]->valeur;
					}
					else
					{
						switch ($zone[$grille[$x][$y]->zone]->operateur)
						{
							case "+" :
								$zone[$grille[$x][$y]->zone]->valeur += $grille[$x][$y]->valeur;
								break;
							case "-" :
								if ($grille[$x][$y]->valeur > $zone[$grille[$x][$y]->zone]->valeur)
								{
									$zone[$grille[$x][$y]->zone]->valeur = $grille[$x][$y]->valeur - $zone[$grille[$x][$y]->zone]->valeur;
								}
								else
								{
									$zone[$grille[$x][$y]->zone]->valeur = $zone[$grille[$x][$y]->zone]->valeur - $grille[$x][$y]->valeur;
								}
								break;
							case "x" :
								$zone[$grille[$x][$y]->zone]->valeur *= $grille[$x][$y]->valeur;
								break;
							case "/" :
								if ($grille[$x][$y]->valeur > $zone[$grille[$x][$y]->zone]->valeur)
								{
									$div = $grille[$x][$y]->valeur / $zone[$grille[$x][$y]->zone]->valeur;
								}
								else
								{
									$div = $zone[$grille[$x][$y]->zone]->valeur / $grille[$x][$y]->valeur;
								}
								if (round($div) == $div)
								{
									$zone[$grille[$x][$y]->zone]->valeur = $div;
								}
								else
								{
									$zone[$grille[$x][$y]->zone]->operateur = "+";
									$zone[$grille[$x][$y]->zone]->valeur += $grille[$x][$y]->valeur;
								}
								break;
						}
					}
				}
			}

			$kdkt->taille = $taille;
			$kdkt->grille = $grille;
			$kdkt->zone = $zone;
			return true;
		}
	}
	
	function kdkt_charger($donnees)
	{
		global $kdkt;
		kdkt_effacer();
		kdkt_initialiser();
		$kdkt->taille = $donnees->kdkt_taille;
		$kdkt->grille = $donnees->kdkt_grille;
		$kdkt->zone = $donnees->kdkt_zone;
		return (($kdkt->taille > 0) && ($kdkt->taille < 11));
	}
	
	function kdkt_sauver(&$donnees)
	{
		global $kdkt;
		if (is_object($kdkt) && ($kdkt->taille > 0))
		{
			$donnees->kdkt_taille = $kdkt->taille;
			$donnees->kdkt_grille = $kdkt->grille;
			$donnees->kdkt_zone = $kdkt->zone;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function kdkt_afficher($mode)
	{
		global $kdkt;
		if (is_object($kdkt) && ($kdkt->taille > 0))
		{
			print(kdkt_dessin_de_la_grille($kdkt->taille,$kdkt->grille,$kdkt->zone,$mode));
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function kdkt_effacer()
	{
		global $kdkt;
		return kdkt_initialiser();
	}
