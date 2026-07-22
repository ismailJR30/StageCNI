 <html>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <head>
    <title>modification de données en PHP :: partie2</title>
  </head>
<body>
  <?php
  require_once __DIR__ . '/../config/database.php';
  //connection au serveur:
  $cnx = getDbConnection();
  //récupération de la variable d'URL,
  //qui va nous permettre de savoir quel enregistrement modifier
  $id  = $_GET["idParticipant"] ;
 
  //requête SQL:
  $sql = "SELECT *
            FROM participant
	    WHERE id = ".$id ;
 
  //exécution de la requête:
  $requete = mysqli_query( $cnx, $sql ) ;
 
  //affichage des données:
  if( $result = mysqli_fetch_object( $requete ) )
  {
  ?>
<form name="insertion" action="modification3.php" method="POST">
  <input type="hidden" name="id" value="<?php echo($id) ;?>">
  <table border="0" align="center" cellspacing="2" cellpadding="2">
    <tr align="center">
      <td>nom et prenom</td>
      <td><input type="text" name="nom_prenom" value="<?php echo($result->nom_prenom) ;?>"></td>
    </tr>
    <tr align="center">
      <td>CIN</td>
      <td><input type="text" name="cin" value="<?php echo($result->cin) ;?>"></td>
    </tr>
    <tr align="center">
      <td>entreprise</td>
      <td><input type="text" name="entreprise" value="<?php echo($result->entreprise) ;?>"></td>
    </tr>
    <tr align="center">
      <td>téléphone fixe</td>
      <td><input type="text" name="tel_fix" value="<?php echo($result->tel_fix) ;?>"></td>
    </tr>
    <tr align="center">
      <td>FAX</td>
      <td><input type="text" name="fax" value="<?php echo($result->fax) ;?>"></td>
    </tr>
    <tr align="center">
      <td colspan="2"><input type="submit" value="modifier"></td>
    </tr>
  </table>
</form>
  <?php
  }//fin if 
  ?>
</body>
</html>