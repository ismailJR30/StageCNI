 <html>
 <head>
 <meta charset="UTF-8">
 <link rel="stylesheet" href="../assets/css/style.css">
</head>
<?php

  require_once __DIR__ . '/../config/database.php';
  $cnx = getDbConnection();
  $nom_prenom     =  $_POST["nom_prenom"] ;
  $specialite     =  $_POST["specialite"] ;
  $direction     =  $_POST["direction"] ;
  $entreprise     = $_POST["entreprise"] ;

  $sql = "INSERT  INTO formateur (nom_prenom,specialite,direction,entreprise)
            VALUES ( '$nom_prenom', '$specialite', '$direction', '$entreprise') " ;
 
 
  $requete = mysqli_query($cnx,$sql ) or die( mysqli_error( ) ) ;
 

  if($requete)
  {
    echo("تمت العملية بنجاح ") ;
  }
  else
  {
    echo("L'insertion à échouée") ;
  }
?>
<br>
<a href="formateur.php">رجوع</a>
</html>                                  