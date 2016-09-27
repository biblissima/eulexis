<?php
/****************************************************************
 **                 fonctions                                  **
 ****************************************************************/
function cherche($lem, $deb, $fin) {
  global $f_index;
  $milieu = floor(($deb + $fin) / 2);
  fseek($f_index, $milieu);
  $toto = fgets($f_index);
  // Je dois l'ignorer, car je suis arrivé au milieu d'une ligne
  $milieu = ftell($f_index);
  // Je garde la trace de l'endroit où je suis.
  $toto = fgets($f_index);
  $eclats = explode(':', $toto);
  //            echo $lem.' '.$toto.' '.$eclats[0].'<br />';
  //            if ($lem==$eclats[0]) return $toto;
  if ($lem == $eclats[0]) {
    // return $milieu;
    // Je suis arrivé sur le bon mot par hasard :
    // je dois vérifier que je suis sur le premier homonyme
    $recule = 100;
    // En moyenne : 20 par entrée dans ind_gr_ana.csv et 65 dans index_com.csv
    while ($lem == $eclats[0]) {
      fseek($f_index, $milieu - $recule);
      $toto = fgets($f_index);
      // Je remonte dans le fichier
      $milieu = ftell($f_index);
      $toto = fgets($f_index);
      $eclats = explode(':', $toto);
      $recule = 2 * $recule;
      // Je double le pas en arrière à chaque tentative infructueuse.
    } // Quand je sors, je sais que $lem>$eclats[0]
  } else if ($fin - $milieu > 100) {
    if ($lem > $eclats[0])
      return cherche($lem, $milieu, $fin);
    else
      return cherche($lem, $deb, $milieu);
  }
  // else est inutile
  if ($lem < $eclats[0])
    fseek($f_index, $deb);
  // Le lemme recherché est entre $deb et $milieu
  // $deb est toujours au début d'une ligne
  $milieu = ftell($f_index);
  $toto = fgets($f_index);
  $eclats = explode(':', $toto);
  while ($lem > $eclats[0] && !feof($f_index)) {
    $milieu = ftell($f_index);
    $toto = fgets($f_index);
    $eclats = explode(':', $toto);
  }
  return $milieu;
  // Maintenant, la fonction retourne le position dans l'index
  //        return $toto;
}

function latin2greek($mot) {
  if (strrpos($mot, 's') == (strlen($mot) - 1) && strlen($mot) != 1)
    $mot = substr($mot, 0, strlen($mot) - 1) . 'ς';
  $lat = array('a', 'b', 'g', 'd', 'e', 'z', 'h', 'q', 'i', 'k', 'l', 'm', 'n', 'c', 'o', 'p', 'r', 's', 't', 'u', 'f', 'x', 'y', 'w', 'v');
  $grec = array('α', 'β', 'γ', 'δ', 'ε', 'ζ', 'η', 'θ', 'ι', 'κ', 'λ', 'μ', 'ν', 'ξ', 'ο', 'π', 'ρ', 'σ', 'τ', 'υ', 'φ', 'χ', 'ψ', 'ω', 'ϝ');
  return str_ireplace($lat, $grec, $mot);
}

function greek2latin($mot) {
  $lat = array('a', 'b', 'g', 'd', 'e', 'z', 'h', 'q', 'i', 'k', 'l', 'm', 'n', 'c', 'o', 'p', 'r', 's', 't', 'u', 'f', 'x', 'y', 'w', 's');
  $grec = array('α', 'β', 'γ', 'δ', 'ε', 'ζ', 'η', 'θ', 'ι', 'κ', 'λ', 'μ', 'ν', 'ξ', 'ο', 'π', 'ρ', 'σ', 'τ', 'υ', 'φ', 'χ', 'ψ', 'ω', 'ς');
  return str_replace($grec, $lat, $mot);
}

function nettoie($mot) {
  $lettres = array('̀', '́', '̂', '̃', '̅', '̆', '̈', '̓', '̔', '̀', '́', '͂', '̈́', 'ͅ', '᾽', '᾿', '῍', '῎', '῏', '῝', '῞', '῟', '῭', '΅', '`', '´', '῾');
  $mot = str_replace($lettres, '', $mot);
  // suppression des combining trucs
  $lettres = array('ά', 'ἀ', 'ἁ', 'ὰ', 'ά', 'ὰ', 'ᾰ', 'ᾱ', 'ᾶ', 'ᾳ', 'ἂ', 'ἃ', 'ἄ', 'ἅ', 'ἆ', 'ἇ', 'ᾲ', 'ᾴ', 'ᾷ', 'ᾀ', 'ᾁ', 'ᾂ', 'ᾃ', 'ᾄ', 'ᾅ', 'ᾆ', 'ᾇ');
  $mot = str_replace($lettres, 'α', $mot);
  $lettres = array('Ά', 'Ἀ', 'Ἁ', 'Ὰ', 'Ᾰ', 'Ᾱ', 'ᾼ', 'Ἂ', 'Ἃ', 'Ἄ', 'Ἅ', 'Ἆ', 'Ἇ', 'ᾈ', 'ᾉ', 'ᾊ', 'ᾋ', 'ᾌ', 'ᾍ', 'ᾎ', 'ᾏ');
  $mot = str_replace($lettres, 'α', $mot);
  $lettres = array('Έ', 'Ἐ', 'Ἑ', 'Ὲ', 'Ἒ', 'Ἓ', 'Ἔ', 'Ἕ');
  $mot = str_replace($lettres, 'ε', $mot);
  $lettres = array('ὲ', 'έ', 'έ', 'ἐ', 'ἑ', 'ὲ', 'ἒ', 'ἓ', 'ἔ', 'ἕ');
  $mot = str_replace($lettres, 'ε', $mot);
  $lettres = array('Ή', 'Ἠ', 'Ἡ', 'Ὴ', 'ῌ', 'Ἢ', 'Ἣ', 'Ἤ', 'Ἥ', 'Ἦ', 'Ἧ', 'ᾘ', 'ᾙ', 'ᾚ', 'ᾛ', 'ᾜ', 'ᾝ', 'ᾞ', 'ᾟ');
  $mot = str_replace($lettres, 'η', $mot);
  $lettres = array('ὴ', 'ή', 'ή', 'ἠ', 'ἡ', 'ὴ', 'ῆ', 'ῃ', 'ἢ', 'ἣ', 'ἤ', 'ἥ', 'ἦ', 'ἧ', 'ᾐ', 'ᾑ', 'ῂ', 'ῄ', 'ῇ', 'ᾒ', 'ᾓ', 'ᾔ', 'ᾕ', 'ᾖ', 'ᾗ');
  $mot = str_replace($lettres, 'η', $mot);
  $lettres = array('Ί', 'Ἰ', 'Ἱ', 'Ὶ', 'Ῐ', 'Ῑ', 'Ϊ', 'Ἲ', 'Ἳ', 'Ἴ', 'Ἵ', 'Ἶ', 'Ἷ');
  $mot = str_replace($lettres, 'ι', $mot);
  $lettres = array('ὶ', 'ί', 'ί', 'ἰ', 'ἱ', 'ὶ', 'ῐ', 'ῑ', 'ῖ', 'ϊ', 'ΐ', 'ἲ', 'ἳ', 'ἴ', 'ἵ', 'ἶ', 'ἷ', 'ῒ', 'ΐ', 'ΐ');
  $mot = str_replace($lettres, 'ι', $mot);
  $lettres = array('Ό', 'Ὀ', 'Ὁ', 'Ὸ', 'Ὂ', 'Ὃ', 'Ὄ', 'Ὅ');
  $mot = str_replace($lettres, 'ο', $mot);
  $lettres = array('ὸ', 'ό', 'ό', 'ὀ', 'ὁ', 'ὸ', 'ὂ', 'ὃ', 'ὄ', 'ὅ');
  $mot = str_replace($lettres, 'ο', $mot);
  $lettres = array('Ῥ', 'ῤ', 'ῥ');
  $mot = str_replace($lettres, 'ρ', $mot);
  $lettres = array('Ύ', 'Ϋ', 'Ὑ', 'Ὺ', 'Ῠ', 'Ῡ', 'Ὓ', 'Ὕ', 'Ὗ');
  $mot = str_replace($lettres, 'υ', $mot);
  $lettres = array('ὺ', 'ύ', 'ύ', 'ὐ', 'ὑ', 'ὺ', 'ῠ', 'ῡ', 'ῦ', 'ϋ', 'ΰ', 'ὒ', 'ὓ', 'ὔ', 'ὕ', 'ὖ', 'ὗ', 'ῢ', 'ΰ', 'ῧ', 'ΰ');
  $mot = str_replace($lettres, 'υ', $mot);
  $lettres = array('Ώ', 'Ὠ', 'Ὡ', 'Ὼ', 'ῼ', 'Ὢ', 'Ὣ', 'Ὤ', 'Ὥ', 'Ὦ', 'Ὧ', 'ᾨ', 'ᾩ', 'ᾪ', 'ᾫ', 'ᾬ', 'ᾭ', 'ᾮ', 'ᾯ');
  $mot = str_ireplace($lettres, 'ω', $mot);
  $lettres = array('ὼ', 'ώ', 'ώ', 'ώ', 'ὠ', 'ὡ', 'ὼ', 'ῶ', 'ῳ', 'ὢ', 'ὣ', 'ὤ', 'ὥ', 'ὦ', 'ὧ', 'ᾠ', 'ᾡ', 'ῲ', 'ῴ', 'ῷ', 'ᾢ', 'ᾣ', 'ᾤ', 'ᾥ', 'ᾦ', 'ᾧ');
  $mot = str_replace($lettres, 'ω', $mot);
  $lettres = array('Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ζ', 'Η', 'Θ', 'Ι', 'Κ', 'Λ', 'Μ', 'Ν', 'Ξ', 'Ο', 'Π', 'Ρ', 'Σ', 'Τ', 'Υ', 'Φ', 'Χ', 'Ψ', 'Ω');
  $grec = array('α', 'β', 'γ', 'δ', 'ε', 'ζ', 'η', 'θ', 'ι', 'κ', 'λ', 'μ', 'ν', 'ξ', 'ο', 'π', 'ρ', 'σ', 'τ', 'υ', 'φ', 'χ', 'ψ', 'ω');
  $mot = str_replace($lettres, $grec, $mot);

  return $mot;
}

function liste_pos($article, $find, $liste) {
  $decal = strlen($find);
  $ii = strpos($article, $find) + $decal;
  $iii = array_push($liste, $ii);
  while (strpos($article, $find, $ii) > 0) {
    $ii = strpos($article, $find, $ii) + $decal;
    $iii = array_push($liste, $ii);
  }
  return $liste;
}

/****************************************************************
 **                        fin des fonctions                    **
 ****************************************************************/

/**********************************************************
 **                        Initialisation                 **
 **********************************************************/

/*    if (isset($_SESSION['betacode']))
 {
 $betacode=$_SESSION['betacode']);
 $unicode=$_SESSION['unicode']);
 }
 else
 {
 */$file = fopen("data/betunicode_gr.csv", "r");
$x = 0;
while (!feof($file)) {
  $ligne = fgets($file);
  if (!strpos($ligne, "!")) {
    $eclats = explode(chr(9), $ligne);
    $betacode[$x] = $eclats[0];
    $unicode[$x] = $eclats[2];
    $x = $x + 1;
  }
}
fclose($file);

//       $_SESSION['betacode'])=$betacode;
//     $_SESSION['unicode'])=$unicode;
//    }

/*if (!isset($_SESSION['en_colonne']))
  $_SESSION['en_colonne'] = true;*/

if (!isset($_SESSION['dicos']))
  $_SESSION['dicos'] = "les trois dicos";
// Par défaut, on affiche les trois dicos.
if (isset($_POST['dicos']))
  $_SESSION['dicos'] = $_POST['dicos'];
// Un choix a été fait par l'utilisateur : on le conserve.
$dicos = $_SESSION['dicos'];

if (!isset($_SESSION['grec']))
  $_SESSION['grec'] = "";
if (isset($_POST['grec']))
  $_SESSION['grec'] = $_POST['grec'];
$grec = $_SESSION['grec'];
// J'ajoute la possibilité de lemmatiser un texte
$consultation = true;
if (isset($_POST['lemmatisation']))
  $consultation = false;
/*    {
 if ($_POST['consultation']=="false") $consultation=false;
 }
 */
if (!isset($_SESSION['exacte']))
  $_SESSION['exacte'] = false;
$f_exacte = $_SESSION['exacte'];

$pos_ind = -1;

//if (isset($_GET['pos_ind']))
//  $pos_ind = $_GET["pos_ind"];
if (isset($_POST['pos_ind']))
  $pos_ind = $_POST["pos_ind"];
// On arrive ici avec un lien direct qui dit où chercher dans l'index
elseif (isset($_POST['lemme']))
  $lemme = trim($_POST["lemme"]);
// On arrive ici avec la soumission d'un lemme dans la fenêtre
elseif (isset($_GET['lemme']))
  $lemme = trim($_GET["lemme"]);
// On arrive ici avec la soumission d'un lemme via un lien
else
  $lemme = 'a';
// La première fois.

/*********************************************
 **               Traitement                 **
 *********************************************/

$lsj_tot = '';
$pape_tot = '';
$bailly_tot = '';
//    if ($pos_ind==-1 && $lemme=='')
if (!$consultation) {
  /*********
   **              J'ai un texte à traiter
   ************/
  if (isset($_POST['exacte'])) 
    $_SESSION['exacte'] = true;
  else
    $_SESSION['exacte'] = false;
  // La checkbox n'existe que si elle est validée
  $f_exacte = $_SESSION['exacte'];
  $grec = str_replace(chr(10),chr(10)." ",$grec);
  $mots = explode(" ", $grec);
  // $lemmata = $mots;
  $ponct = array('"', ",", ";", ":", ".", "?", chr(13), chr(10));
  $betasignes = array("(", ")", "\\", "/", "=", "+", "|", "_", "^", "*", "1", "2", "3", "4", "5");
  $f_index = fopen("data/ind_gr_ana.csv", "r");
  $file = fopen("data/greek-analyses.txt", "r");
  for ($x = 0; $x < count($mots); $x++) {
    $forme[$x] = "";
    $titre[$x] = "";
    if ($mots[$x] != "") {
      $mot_txt = str_replace($ponct, "", $mots[$x]);
      // Je garde le mot courant avec ses décorations
      $mot = greek2latin(nettoie($mot_txt));
      $mot_beta = str_replace($unicode, $betacode, $mot_txt);
      $toto = cherche($mot, 0, filesize("data/ind_gr_ana.csv"));
      fseek($f_index, $toto);
      // Dans le cas d'homonymes, je pointe sur le premier.
      $titi = fgets($f_index);
      $eclats = explode(":", $titi);
      $reference = $eclats[0];
      if ($mot != $eclats[0]) {
        $forme[$x] = "Non trouvé";
        $titre[$x] = "Non trouvé";
      } else {
        $l_formes = "";
        $nn_f = 0;
        while ($mot == $eclats[0]) {
          fseek($file, (int) $eclats[1]);
          $l_mot = explode("{", fgets($file));
          $mot_tr = $l_mot[0];
          $mot_tr = substr($mot_tr, 0, strlen($mot_tr) - 1);
          // supprime le Tab.
          $mot_tr2 = $mot_tr;
          // Je garde le betacode
          if (strrpos($mot_tr, 's') == (strlen($mot_tr) - 1) && strlen($mot_tr) != 1)
            $mot_tr = substr($mot_tr, 0, strlen($mot_tr) - 1) . 'ς';
          $mot_tr = str_replace($betacode, $unicode, $mot_tr);
          if ($mot_beta == $mot_tr2)
            $l_formes[$nn_f] = "<span style='color:red'><b>" . $mot_tr . "</b></span>";
          //                  $forme[$x] .= "<span style='color:red'><b>" . $mot_tr . "</b></span>";
          else
            $l_formes[$nn_f] = $mot_tr;
          //                  $forme[$x] .= $mot_tr;
          // Quand la forme trouvée coïncide exactement avec celle cherchée, je la mets en rouge gras.
          $bb = "";
          $cc = "";
          for ($aa = 1; $aa < count($l_mot); $aa++) {
            $ecl = explode(chr(9), $l_mot[$aa]);
            // des Tab séparent la forme, la traduction et l'analyse morphologique
            $txt = explode(" ", $ecl[0]);
            // Pour l'instant dans le 1er champ, j'ai deux nombres avant la forme
            $eclat = explode(",", $txt[2]);
            // Dans certains cas, le champ "lemme" est composé de deux mots.
            $lem = str_replace($betasignes, "", $eclat[count($eclat) - 1]);
            // $tutu = "<a href='index.php?lemme=" . $lem . "#haut_de_page'>";
            $tutu = "<a href='#' data-lemme='". $lem . "'>";
            for ($i = 0; $i < count($eclat); $i++) {
              $mot_t = $eclat[$i];
              // Pour le sigma final, chaque mot doit être traité séparément
              if (strrpos($mot_t, 's') == (strlen($mot_t) - 1) && strlen($mot_t) != 1)
                $mot_t = substr($mot_t, 0, strlen($mot_t) - 1) . 'ς';
              $eclat[$i] = str_replace($betacode, $unicode, $mot_t);
            }
            $titi = implode(", ", $eclat);
            $eclat[count($eclat) - 1] = $tutu . $eclat[count($eclat) - 1] . "</a>";
            $tutu = implode(", ", $eclat);
            $ec = explode("}", $ecl[2]);
            // Je supprime ce qui pourrait suivre l'accolade fermante
            $bb[$aa - 1] = $mot_tr . "&nbsp;: " . $titi . " &nbsp; " . $ecl[1] . " &nbsp; " . $ec[0];
            // lemme traduction analyse :
            // le lemme peut redonner la forme avec quantités
            $cc[$aa - 1] = $tutu . " &nbsp; " . $ecl[1] . " &nbsp; " . $ec[0];
          }
          $titre[$x] .= implode("<br/>", $bb) . "\n";
          // pour la bulle d'aide
          $l_formes[$nn_f] .= "<ul><li>" . implode("</li>\n<li>", $cc) . "</li></ul><br />\n";
          $nn_f += 1;
          // pour la lemmatisation
          $titi = fgets($f_index);
          $eclats = explode(":", $titi);
        }
        sort($l_formes);
        // Met les formes exactes au début
        if ($f_exacte && strpos($l_formes[0], "color:red") > 0) {
          $jjj = 0;
          $forme[$x] = "";
          while (strpos($l_formes[$jjj], "color:red") > 0) {
            $forme[$x] .= $l_formes[$jjj];
            $jjj += 1;
          }
        } else
          $forme[$x] = implode("", $l_formes);
      }
    }
  }
  fclose($f_index);
  fclose($file);

} else if (isset($_POST["flexion"])) {
  /*****************
   **          J'ai un lemme à fléchir
   ****************/
  $tableau = "";
  $betasignes = array("(", ")", "\\", "/", "=", "+", "|", "_", "^", "*", "1", "2", "3", "4", "5");
  // Un tableau de valeurs
  $valeur["nom"] = 1;
  $valeur["voc"] = 2;
  $valeur["acc"] = 4;
  $valeur["gen"] = 8;
  $valeur["dat"] = 16;
  $valeur["masc"] = 32;
  $valeur["fem"] = 64;
  $valeur["neut"] = 128;
  $valeur["sg"] = 256;
  $valeur["dual"] = 512;
  $valeur["pl"] = 1024;
  $valeur["comp"] = 2048;
  $valeur["superl"] = 4096;
  // Verbes
  $valeur["1st"] = 1;
  $valeur["2nd"] = 2;
  $valeur["3rd"] = 4;
  // temps
  $valeur["pres"] = 2048;
  $valeur["imperf"] = 4096;
  $valeur["perf"] = 6144;
  $valeur["plup"] = 8192;
  $valeur["fut"] = 10240;
  $valeur["futperf"] = 12288;
  $valeur["aor"] = 14336;
  // modes
  $valeur["ind"] = 16384;
  $valeur["subj"] = 32768;
  $valeur["imperat"] = 49152;
  $valeur["inf"] = 65536;
  $valeur["opt"] = 81920;
  $valeur["part"] = 98304;
  // Voix
  $valeur["act"] = 131072;
  $valeur["mid"] = 262144;
  $valeur["mp"] = 393216;
  $valeur["pass"] = 524288;

  $valeur["a_priv"] = 0;
  $valeur["adverbial"] = 0;
  $valeur["aeolic"] = 0;
  $valeur["attic"] = 0;
  $valeur["contr"] = 0;
  $valeur["doric"] = 0;
  $valeur["enclitic"] = 0;
  $valeur["epic"] = 0;
  $valeur["geog_name"] = 0;
  $valeur["indeclform"] = 0;
  $valeur["ionic"] = 0;
  $valeur["iota_intens"] = 0;
  $valeur["irreg_comp"] = 0;
  $valeur["irreg_superl"] = 0;
  $valeur["nu_movable"] = 0;
  $valeur["particle"] = 0;
  $valeur["poetic"] = 0;
  $valeur["proclitic"] = 0;

  $f_index = fopen("data/ind_gr_lem.csv", "r");
  $file = fopen("data/greek-lemmata.txt", "r");
  $mot_beta = str_replace($unicode, $betacode, $lemme);
  //$mot = str_replace($betasignes, "", $mot_beta);
  $mot = strtolower(str_replace($betasignes, "", $mot_beta));
  $toto = cherche($mot, 0, filesize("data/ind_gr_lem.csv"));
  fseek($f_index, $toto);
  // Dans le cas d'homonymes, je pointe sur le premier.
  $titi = fgets($f_index);
  $eclats = explode(":", $titi);
  if ($mot != $eclats[0]) {
    $tableau = $lemme . " : Non trouvé";
  }
  while ($mot == $eclats[0]) {
    fseek($file, (int) $eclats[1]);
    $ligne = trim(fgets($file));
    $liste = explode(chr(9), $ligne);
    $mot_t = $liste[0];
    if (strrpos($mot_t, 's') == (strlen($mot_t) - 1) && strlen($mot_t) != 1)
      $mot_t = substr($mot_t, 0, strlen($mot_t) - 1) . 'ς';
    $lem = str_replace($betacode, $unicode, $mot_t);
    $tableau .= "<b>" . $lem . "</b><ul>\n";
    $jj = 0;
    for ($i = 2; $i < count($liste); $i++) {
      $element = substr($liste[$i], 0, strlen($liste[$i]) - 1);
      // Supprime la dernière ")"
      $eclats = explode(") (", $element);
      $mot_t = substr($eclats[0], 0, strpos($eclats[0], " ("));
      // Pour le sigma final, chaque mot doit être traité séparément
      if (strrpos($mot_t, 's') == (strlen($mot_t) - 1) && strlen($mot_t) != 1)
        $mot_t = substr($mot_t, 0, strlen($mot_t) - 1) . 'ς';
      $forme = str_replace($betacode, $unicode, $mot_t);
      $eclats[0] = substr($eclats[0], strpos($eclats[0], " (") + 2, strlen($eclats[0]));
      for ($j = 0; $j < count($eclats); $j++) {
        $ana_morph = $eclats[$j];
        $detail = "";
        if (strpos($ana_morph, " (") > 0) {
          $detail = substr($ana_morph, strpos($ana_morph, " ("), strlen($ana_morph));
          $ana_morph = substr($ana_morph, 0, strpos($ana_morph, " ("));
        }
        $total = 1000000;
        $ecl = explode(" ", $ana_morph);
        for ($k = 0; $k < count($ecl); $k++) {
          $ec = explode("/", $ecl[$k]);
          $total += $valeur[$ec[0]];
          // for ($jjj=0;$jjj<count($ec);$jjj++) $total+=$valeur[$ec[$jjj]];
        }
        $tutu[$jj] = $total . "<li>" . $ana_morph . " : " . $forme . "<span style='color:green'>" . $detail . "</span></li>";
        $jj += 1;
      }
    }
    sort($tutu);
    $sousListe = false;
    for ($jj = 0; $jj < count($tutu) - 1; $jj++) {
      $total = substr($tutu[$jj], 0, strpos($tutu[$jj], " : "));
      if ($total == substr($tutu[$jj + 1], 0, strpos($tutu[$jj + 1], " : "))) {
        $tutu[$jj] = "<li>" . substr($tutu[$jj], strpos($tutu[$jj], " : ") + 2, strlen($tutu[$jj]));
        if (!$sousListe)
          $tutu[$jj] = substr($total, 7, strlen($total)) . " :<ul>\n" . $tutu[$jj];
        $sousListe = true;
      } else {
        if ($sousListe)
          $tutu[$jj] = "<li>" . substr($tutu[$jj], strpos($tutu[$jj], " : ") + 2, strlen($tutu[$jj])) . "</ul>";
        else
          $tutu[$jj] = substr($tutu[$jj], 7, strlen($tutu[$jj]));
        $sousListe = false;
      }
    }
    $jj = count($tutu) - 1;
    if ($sousListe)
      $tutu[$jj] = "<li>" . substr($tutu[$jj], strpos($tutu[$jj], " : ") + 2, strlen($tutu[$jj])) . "</ul>";
    else
      $tutu[$jj] = substr($tutu[$jj], 7, strlen($tutu[$jj]));
    //            for ($jj=0;$jj<count($tutu);$jj++)
    //              $tutu[$jj]=substr($tutu[$jj],7,strlen($tutu[$jj]));
    $tableau .= implode("\n", $tutu) . "</ul><br />\n";
    $titi = fgets($f_index);
    $eclats = explode(":", $titi);
  }
  fclose($f_index);
  fclose($file);
} else {
  /*********
   **        J'ai un lemme à chercher dans les dicos
   ************/
  if (isset($_POST['lemme'])) {
    // Si j'arrive ici par soumission d'un lemme, je dois tenir compte du choix "en colonnes".
    if (isset($_POST['en_colonne']))
      $_SESSION['en_colonne'] = true;
    else
      $_SESSION['en_colonne'] = false;
  }
  $aff_dicos = $dicos;
  if ($dicos == "les trois dicos") {
    $le_LSJ = true;
    $le_Pape = true;
    $le_Bailly = true;
  } else {
    $le_LSJ = (strpos($dicos, "LSJ") > 0);
    $le_Pape = (strpos($dicos, "Pape") > 0);
    $le_Bailly = (strpos($dicos, "Bailly") > 0);
  }
  $f_index = fopen("data/index_com.csv", "r");
  if ($pos_ind == -1)// J'ai donc un lemme.
  {
    if (ord($lemme) < 128)
      $lemme = latin2greek(strtolower($lemme));
    else
      $lemme = nettoie($lemme);
    $pos_ind = cherche($lemme, 0, filesize("data/index_com.csv"));
  }
  fseek($f_index, $pos_ind);
  $reponse = trim(fgets($f_index));
  // J'ajoute un trim pour éliminer le \n qui termine la ligne
  if ($pos_ind > 1000) {
    fseek($f_index, $pos_ind - 1000);
    $toto = fgets($f_index);
    // Comme je suis n'importe où, je dois d'abord finir la ligne en cours
  } else
    fseek($f_index, 0);
  $x = 0;
  $y_LSJ = 0;
  $y_Pape = 0;
  $y_Bailly = 0;
  $eclats = explode(':', $reponse);
  //            if (isset($_GET['pos_ind']))
  $lemme = $eclats[0];
  // $reponse contient la ligne de l'index correspondant à la demande
  // $eclats[1] contient la position dans le LSJ de l'entrée demandée
  // $eclats[3] contient celle dans le Pape.
  /*            if ($eclats[1] != '') {
   $pos_lsj[0] = $eclats[1];
   $len_lsj[$y_LSJ] = strlen($eclats[2]);
   $y_LSJ += 1;
   }
   if ($eclats[3] != '') {
   $pos_pape[0] = $eclats[3];
   $len_pape[$y_Pape] = strlen($eclats[4]);
   $y_Pape += 1;
   }
   if ($eclats[5] != '') {
   $pos_bailly[$y_Bailly] = $eclats[5];
   $len_bailly[$y_Bailly] = strlen($eclats[6]);
   $y_Bailly += 1;
   }
   */
  $avant = -1;
  $ici = 0;
  while ((ftell($f_index) < $pos_ind + 1000) && !feof($f_index)) {
    $a[$x] = ftell($f_index);
    // L'array $a contient les positions des entrées dans l'index
    $eclats = explode(':', trim(fgets($f_index)));
    if ($eclats[2] != '')
      $b[$x] = $eclats[2];
    else if ($eclats[4] != '')
      $b[$x] = $eclats[4];
    else
      $b[$x] = $eclats[6];
    // L'array $b contient les entrées de l'index qui correspondent à la position donnée par $a
    //              if ($a[$x] == $pos_ind)
    //                $b[$x] = "<big>➢" . $b[$x] . "</big>";
    //              else if ($eclats[0] == $lemme) {
    if ($eclats[0] == $lemme) {
      $ici = $x;
      if ($eclats[1] != '') {
        $pos_lsj[$y_LSJ] = $eclats[1];
        $len_lsj[$y_LSJ] = strlen($eclats[2]);
        $y_LSJ += 1;
      }
      if ($eclats[3] != '') {
        $pos_pape[$y_Pape] = $eclats[3];
        $len_pape[$y_Pape] = strlen($eclats[4]);
        $y_Pape += 1;
      }
      if ($eclats[5] != '') {
        $pos_bailly[$y_Bailly] = $eclats[5];
        $len_bailly[$y_Bailly] = strlen($eclats[6]);
        $y_Bailly += 1;
      }
    } else if ($eclats[0] < $lemme)
      $avant = $x;
    $x = $x + 1;
  }
  fclose($f_index);

  // Je prépare une ligne de titre avec, au centre, le ou les mots trouvés
  // et, de part et d'autre, le mot d'avant et le mot d'après.
  if ($ici == 0)
    $ici = $avant + 1;
  $lg_titre = "<ul class='pager'><li class='previous'>";
  //$lg_titre .= "<a href='index.php?pos_ind=" . $a[$avant] . "#haut_de_page'>&larr; " . $b[$avant] . "</a>";
  $lg_titre .= "<a href='#' data-pos='" . $a[$avant] . "'><span aria-hidden='true'>&larr;</span> " . $b[$avant] . "</a>";
  $lg_titre .= "</li>";
  $lg_titre .= "<li class='lead'>";
  if ($ici - $avant > 1)
    for ($i = $avant + 1; $i < $ici; $i++)
      $lg_titre .= $b[$i] . ", ";
  $lg_titre .= $b[$ici];
  $lg_titre .= "</strong></li>";
  $lg_titre .= "<li class='next'>";
  //$lg_titre .= "<a href='index.php?pos_ind=" . $a[$ici + 1] . "#haut_de_page'>" . $b[$ici + 1] . " &rarr;</a>";
  $lg_titre .= "<a href='#' data-pos='" . $a[$ici + 1] . "'>" . $b[$ici + 1] . " <span aria-hidden='true'> &rarr;</span></a>";
  $lg_titre .= "</li></ul>";
  $b[$avant + 1] = "<li class='lead'>" . $b[$avant + 1] . "</li>";

  // Je sais maintenant combien d'articles de chaque dicos j'ai à ma disposition
  $quelque_chose = ($y_LSJ != 0 && $le_LSJ) || ($y_Pape != 0 && $le_Pape) || ($y_Bailly != 0 && $le_Bailly);
  // Si $quelque_chose, alors l'affichage ne sera pas vide.
  if (!$quelque_chose) {
    // L'affichage serait vide si je ne fais rien : je bascule donc sur les trois dicos.
    $aff_dicos = "les trois dicos";
    $le_LSJ = true;
    $le_Pape = true;
    $le_Bailly = true;
  }

  if ($y_LSJ != 0 && $le_LSJ) {
    $f1 = fopen("data/LSJ_1940_Ph.txt", "r");
    for ($i = 0; $i < $y_LSJ; $i++) {
      fseek($f1, $pos_lsj[$i]);
      $lsj = fgets($f1);
      $lsj = substr_replace($lsj, "</b></span>", $len_lsj[$i], 0);
      $lsj = "<span style='color:red'><b>" . $lsj;
      $l_ii = "";
      $l_ii[0] = 0;
      if (stripos($lsj, " v. ") > 0)
        $l_ii = liste_pos($lsj, " v. ", $l_ii);
      if (stripos($lsj, " = ") > 0)
        $l_ii = liste_pos($lsj, " = ", $l_ii);

      // J'ai constitué une liste de tous les renvois possibles.
      $iii = count($l_ii) - 1;
      if ($iii > 0) {
        sort($l_ii);
        while ($iii > 0) {
          $ii = $l_ii[$iii];
          // Il y a probablement un renvoi.
          if (ord(substr($lsj, $ii, 1)) > 191) {
            // ça se confirme
            $debut = $ii;
            $max = strlen($lsj);
            while ((ord(substr($lsj, $ii, 1)) > 127) && ($ii < $max))
              $ii += 1;
            $fin = $ii;
            $renvoi_lsj = substr($lsj, $debut, $fin - $debut);
            $lsj = substr_replace($lsj, "</a>", $fin, 0);
            //$lsj = substr_replace($lsj, "<a href='index.php?lemme=" . $renvoi_lsj . "#haut_de_page'>", $debut, 0);
            $lsj = substr_replace($lsj, "<a href='#' data-lemme='" . $renvoi_lsj . "'>", $debut, 0);
          }
          $iii = $iii - 1;
        }
      }
      $lsj_tot .= $lsj . "<br /><br />\n"; // $lsj_tot contient tous les articles du LSJ concernant le lemme demandé
    }
    fclose($f1);
  }
  if ($y_Pape != 0 && $le_Pape) {
    $f1 = fopen("data/Pape_Ph.txt", "r");
    for ($i = 0; $i < $y_Pape; $i++) {
      fseek($f1, $pos_pape[$i]);
      $pape = fgets($f1);
      $pape = substr_replace($pape, "</b></span>", $len_pape[$i], 0);
      $pape = "<span style='color:red'><b>" . $pape;
      $l_ii = "";
      $l_ii[0] = 0;
      if (strpos($pape, " = <i>") > 0)
        $l_ii = liste_pos($pape, " = <i>", $l_ii);
      if (strpos($pape, " <i>= ") > 0)
        $l_ii = liste_pos($pape, " <i>= ", $l_ii);
      if (stripos($pape, " s. <i>") > 0)
        $l_ii = liste_pos($pape, " s. <i>", $l_ii);
      if (stripos($pape, "(s. <i>") > 0)
        $l_ii = liste_pos($pape, "(s. <i>", $l_ii);
      if (stripos($pape, "(vgl. <i>") > 0)
        $l_ii = liste_pos($pape, "(vgl. <i>", $l_ii);
      if (stripos($pape, " s.<i> ") > 0)
        $l_ii = liste_pos($pape, " s.<i> ", $l_ii);

      // J'ai constitué une liste de tous les renvois possibles.
      $iii = count($l_ii) - 1;
      if ($iii > 0) {
        sort($l_ii);
        while ($iii > 0) {
          $ii = $l_ii[$iii];
          // Il y a probablement un renvoi.
          if (substr($pape, $ii, 1) == " ")
            $ii += 1;
          if ((ord(substr($pape, $ii, 1)) > 191) || (substr($pape, $ii, 1) == "-")) {
            // ça se confirme
            $debut = $ii;
            $max = strlen($pape);
            while ((ord(substr($pape, $ii, 1)) > 127) && ($ii < $max))
              $ii += 1;
            $fin = $ii;
            $renvoi_pape = str_replace("-", "", substr($pape, $debut, $fin - $debut));
            $pape = substr_replace($pape, "</a>", $fin, 0);
            //$pape = substr_replace($pape, "<a href='index.php?lemme=" . $renvoi_pape . "#haut_de_page'>", $debut, 0);
            $pape = substr_replace($pape, "<a href='#' data-lemme='" . $renvoi_pape . "'>", $debut, 0);
          }
          $iii -= 1;
        }
      }
      $pape_tot .= $pape . "<br /><br />\n";
    }
    fclose($f1);
  }
  if ($y_Bailly != 0 && $le_Bailly) {
    $f1 = fopen("data/XMLBailly351.txt", "r");
    for ($i = 0; $i < $y_Bailly; $i++) {
      fseek($f1, $pos_bailly[$i]);
      $lsj = fgets($f1);
      $lsj = substr_replace($lsj, "</b></span>", $len_bailly[$i], 0);
      $lsj = "<span style='color:red'><b>" . $lsj;
      $l_ii = "";
      $l_ii[0] = 0;
      // Il faut nettoyer la liste (résidus du LSJ et du Pape). Correction du 15 février, Ph.
      $ii = 0;
      if (stripos($lsj, " c.") > 0)
        $l_ii = liste_pos($lsj, " c.", $l_ii);
      if (stripos($lsj, " v.") > 0)
        $l_ii = liste_pos($lsj, " v.", $l_ii);
      if (stripos($lsj, " p.") > 0)
        $l_ii = liste_pos($lsj, " p.", $l_ii);
      if (stripos($lsj, ">v.") > 0)
        $l_ii = liste_pos($lsj, ">v.", $l_ii);
      if (stripos($lsj, ">p.") > 0)
        $l_ii = liste_pos($lsj, ">p.", $l_ii);
      if (stripos($lsj, ">c.") > 0)
        $l_ii = liste_pos($lsj, ">c.", $l_ii);

      // J'ai constitué une liste de tous les renvois possibles.
      $iii = count($l_ii) - 1;
      if ($iii > 0) {
        sort($l_ii);
        while ($iii > 0) {
          $ii = $l_ii[$iii];
          // Il y a probablement un renvoi.
          // Le plus souvent, il commence par </i>
          if (substr($lsj, $ii, 5) == "</i> ")
            $ii = $ii + 5;
          else
            $ii = $ii + 1;
          if (ord(substr($lsj, $ii, 1)) > 191) {
            // ça se confirme : début d'un caractère unicode, supposé grec.
            $debut = $ii;
            $max = strlen($lsj);
            while ((ord(substr($lsj, $ii, 1)) > 127) && ($ii < $max))
              $ii += 1;
            $fin = $ii;
            $renvoi_lsj = substr($lsj, $debut, $fin - $debut);
            $lsj = substr_replace($lsj, "</a>", $fin, 0);
            //$lsj = substr_replace($lsj, "<a href='index.php?lemme=" . $renvoi_lsj . "#haut_de_page'>", $debut, 0);
            $lsj = substr_replace($lsj, "<a href='#' data-lemme='" . $renvoi_lsj . "'>", $debut, 0);
          }
          $iii -= 1;
        }
      }
      $bailly_tot .= $lsj . "<br /><br />\n";
    }
    fclose($f1);
    $bailly_tot = str_replace("\\n<b>", "<br />\n<b>", $bailly_tot);
    // Un peu de mise en forme...
    $bailly_tot = str_replace("\\n  ", "<br />\n  ", $bailly_tot);
    $bailly_tot = str_replace("\\n", "\n", $bailly_tot);
  }
}

/*********************************************
 **               Affichage                  **
 *********************************************/

if ($consultation) {
  if (isset($_POST["flexion"])) {
    echo $tableau . "<br />\n";
  } else {
    //                echo "j'ai trouvé : ".count($l_ii)."<br />";
    echo $lg_titre;
    echo "<div class='table-dicos'>\n";
    echo "<div class='col mots'>\n";
    for ($x = 0; $x < count($a); $x++) {
      //echo "<a href='index.php?pos_ind=" . $a[$x] . "#haut_de_page'>\n";
      echo "<a href='#' data-pos='" . $a[$x] . "'>\n";
      if (strpos($b[$x], "</li>"))
        echo $b[$x] . "</a>\n";
      else
        echo $b[$x] . "</a><br />\n";
      //        La colonne de gauche présente les mots voisins avec des hyperliens
    }
    echo "</div>\n";

    echo "<div class='col dicos'>\n";
    if ($le_LSJ) {
      echo "<div>\n";
      echo "<h3>LSJ 1940</h3>\n";
      echo "<p>" . $lsj_tot . "</p>\n";
      echo "</div>\n";
    }
    if ($le_Pape) {
      echo "<div>\n";
      echo "<h3>Pape 1880</h3>\n";
      echo "<p>" . $pape_tot . "</p>\n";
      echo "</div>\n";
    }
    if ($le_Bailly) {
      echo "<div>\n";
      echo "<h3>Bailly abr. 1919</h3>\n";
      echo "<p>" . $bailly_tot . "</p>\n";
      echo "</div>\n";
    }
    echo "</div>\n";
    echo "</div>\n";
    echo $lg_titre;
  }
} else {
  // Affichage du texte traité
  echo "<h3>Aide à la lecture</h3>\n";
  
  for ($x = 0; $x < count($mots); $x++) {
    //echo "<a href='#mot" . $x . "'><span class='info-lemme' data-toggle='tooltip' title='" . $titre[$x] . "'>" . $mots[$x] . "</span></a> \n";
    echo "<a href='#mot" . $x . "'><span class='info-lemme' data-toggle='tooltip' title=\"" . str_replace("\n","<br />", $titre[$x]) . "\"> " . str_replace(chr(10),"<br />",$mots[$x]) . "</span></a> \n";
  }
  echo "<h3>Lemmatisation avec les formes du texte</h3>\n";
  for ($x = 0; $x < count($mots); $x++) {
    echo "<a name='mot" . $x . "'></a>\n";
    echo "<b>" . str_replace($ponct, "", $mots[$x]) . "</b>&nbsp;: \n";
    if ($forme[$x] != "Non trouvé")
      echo "<br />";
    echo $forme[$x] . "<br />\n";
  }
}
?>
