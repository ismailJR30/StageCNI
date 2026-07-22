<?php
  require_once __DIR__ . '/../config/database.php';
  //connection au serveur
  $cnx = getDbConnection();
  //récupération des valeurs des champs:
  //nom:
  $nom     = $_POST["nom"] ;
  //prenom:
  $prenom = $_POST["prenom"] ;
  //adresse:
  $adresse = $_POST["adresse"] ;
  //code postal:
  $cp        = $_POST["codePostal"] ;
  //numéro de téléphone:
  $tel        = $_POST["telephone"] ;
 
  //récupération de l'identifiant de la personne:
  $id         = $_POST["id"] ;
 
  //création de la requête SQL:
  $sql = "UPDATE personnes
            SET nom         = '$nom', 
	          prenom     = '$prenom',
		  adresse    = '$adresse',
		  cp           = '$cp',
		  telephone = '$tel'
           WHERE id = '$id' " ;
 
  //exécution de la requête SQL:
  $requete = mysqli_query($cnx,$sql) or die( mysqli_error($cnx) ) ;
 
 
  //affichage des résultats, pour savoir si la modification a marchée:
  if($requete)
  {
    header("location:modification1.php") ;
  }
  else
  {
    echo("La modification à échouée") ;
  }
?>