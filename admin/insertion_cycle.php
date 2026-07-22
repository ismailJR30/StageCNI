<html>
 <head>
 <meta charset="UTF-8">
</head>
<?php
  require_once __DIR__ . '/../config/database.php';
  $cnx = getDbConnection();
  $num_act     =  $_POST["num_act"] ;
  $theme = $_POST["theme"] ;
  $date_deb = $_POST["date_deb"] ;

  $date_fin    = $_POST["date_fin"] ;
  
  $form_1       = $_POST["form_1"] ;
  $form_2       = $_POST["form_2"] ;
  $form_3       = $_POST["form_3"] ;

  $num_salle    = $_POST["num_salle"];


  $sql = "INSERT  INTO cycle (num_act,theme, date_deb, date_fin, for1, for2, for3, num_salle)
            VALUES ( '$num_act', '$theme', '$date_deb', '$date_fin', '$form_1','$form_2','$form_3','$num_salle') " ;
 
  //exécution de la requête SQL:
  $requete = mysqli_query($cnx,$sql ) or die( mysqli_error() ) ;
 
  //affichage des résultats, pour savoir si l'insertion a marchée:
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
<a href="menu.php">رجوع</a>
</html>              