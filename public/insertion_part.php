 <html>
 <head>
 <meta charset="UTF-8">
 <link rel="stylesheet" href="../assets/css/style.css">
</head>
<?php

  require_once __DIR__ . '/../config/database.php';
  $cnx = getDbConnection();
  $nom_prenom     =  $_POST["nom_prenom"] ;
  $cin     =  $_POST["cin"] ;
  $entreprise = $_POST["entreprise"] ;
  $tel_fix = $_POST["tel_fix"] ;

  $fax        = $_POST["fax"] ;
  
  $tel_port       = $_POST["tel_port"] ;
  $mail       = $_POST["mail"] ;

  $theme=$_POST["theme"] ;
  $num_salle=$_POST["num_salle"] ;
  $date_debut=$_POST["date_debut"] ;
  
  $sql = "INSERT INTO participant (nom_prenom, cin, entreprise, tel_fix, fax, tel_port, mail, theme_part, num_salle, date_debut)
            VALUES ('$nom_prenom', '$cin', '$entreprise', '$tel_fix', '$fax', '$tel_port', '$mail', '$theme', '$num_salle', '$date_debut')";
  
  $requete = mysqli_query($cnx, $sql) or die(mysqli_error($cnx));
if($requete)

    {
      echo("تم تسجيلك");
    }
  
else
    {
    echo("L'insertion à échouée") ;
    }

?>
<br>
<a href="index1.php">رجوع</a>
</html>                