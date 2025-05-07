<?php
	// ma-soluce.fr
	//
	// (c) Patrick Prémartin 18/04/2009
	// (c) Olf Software 2009
	//
	// Dernières modifications :
	//	18/04/2009 : création de ce fichier
	
	require_once(dirname(__FILE__)."/jeu-param.inc.php");
	unset($jeu_en_cours);
	jeu_initialiser();

	function jeu_nettoyer_reference($reference)
	{
		$txt = strtolower(trim($reference));
        $ch = "";
        for ($i = 0; $i < strlen ($txt); $i++) {
          $c = substr ($txt, $i, 1);
          if ((($c >= "a") && ($c <= "z")) || (($c >= "0") && ($c <= "9"))) {
            $ch .= $c;
          }
        }
		return $ch;
	}
	
	function jeu_nouvelle_reference()
	{
		$longueur = 10;
		$id = "";
		for ($j = 0; $j < $longueur/5; $j++)
		{
			$num = mt_rand (0,99999);
			for ($i = 0; $i < 5; $i++) {
				$id = ($num % 10).$id;
				$num = floor ($num / 10);
			}
		}
		return (substr ($id, 0, $longueur));
	}
	
	function jeu_initialiser()
	{
		global $jeu_en_cours;
		$jeu_en_cours->type_de_jeu = _JEU_AUCUN;
		$jeu_en_cours->reference = "";
	}
	
	function jeu_creer($type_de_jeu,$taille=3)
	{
		global $jeu_en_cours;
		jeu_effacer();
		switch ($type_de_jeu)
		{
			case _JEU_KDKT:
				$jeu_en_cours->type_de_jeu = _JEU_KDKT;
				return kdkt_creer($taille);
				break;
			default :
				return false;
		}
	}
	
	function jeu_charger($reference)
	{
		global $jeu_en_cours;
		$result = false;
		$reference = jeu_nettoyer_reference($reference);
		if (file_exists(dirname(__FILE__)."/jeux/".$reference.".dat"))
		{
			if (false !== ($ch = file_get_contents(dirname(__FILE__)."/jeux/".$reference.".dat")))
			{
				$donnees = unserialize($ch);
				$jeu_en_cours->type_de_jeu = $donnees->type_de_jeu;
				$jeu_en_cours->reference = $donnees->reference;
				switch ($jeu_en_cours->type_de_jeu)
				{
					case _JEU_KDKT:
						$result = kdkt_charger($donnees);
						break;
				}
			}
		}
		return $result;
	}
	
	function jeu_sauver()
	{
		global $jeu_en_cours;
		$result = false;
		if (_JEU_AUCUN != $jeu_en_cours->type_de_jeu)
		{
			if ("" == $jeu_en_cours->reference)
			{
				$ok = false;
				while (!$ok)
				{
					$jeu_en_cours->reference = jeu_nouvelle_reference();
					$ok = (! file_exists(dirname(__FILE__)."/jeux/".$jeu_en_cours->reference.".dat"));
				}
			}
			unset($donnees);
			$donnees->type_de_jeu = $jeu_en_cours->type_de_jeu;
			$donnees->reference = $jeu_en_cours->reference;
			switch ($jeu_en_cours->type_de_jeu)
			{
				case _JEU_KDKT:
					$result = kdkt_sauver($donnees);
					break;
			}
			if ($result)
			{
				if ($f = fopen(dirname(__FILE__)."/jeux/".$jeu_en_cours->reference.".dat","w"))
				{
					fwrite($f,serialize($donnees));
					fclose($f);
				}
				else
				{
					$result = false;
				}
			}
		}
		return $result;
	}
	
	function jeu_afficher($mode)
	{
		global $jeu_en_cours;
		print ("<div class=\"zone_de_jeu\">");
		switch ($jeu_en_cours->type_de_jeu)
		{
			case _JEU_KDKT:
				$result = kdkt_afficher($mode);
				$titre = "Grille de jeu";
				break;
			default :
				$result =  false;
		}
		if (($result) && ("" != $jeu_en_cours->reference))
		{
			print($titre." n° ".strtoupper($jeu_en_cours->reference));
			if (_AFFICHAGE_HTML_SOLUTION != $mode)
			{
				print("<br />Solution disponible sur http://ma-soluce.fr/");
			}
			else
			{
				print("<br />Solution fournie par http://ma-soluce.fr/");
			}
		}
		print("</div>");
		return $result;
	}
	
	function jeu_effacer()
	{
		global $jeu_en_cours;
		switch ($jeu_en_cours->type_de_jeu)
		{
			case _JEU_KDKT:
				kdkt_effacer();
				break;
		}
		return jeu_initialiser();
	}
