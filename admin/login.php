<html>
 <head>
 <meta charset="UTF-8">
</head>
<body>
 <?php
  require_once __DIR__ . '/../config/database.php';
  $nom     = $_POST["username"] ;
  $passe = $_POST["password"] ;
  $cnx = getDbConnection();

   $sql = "SELECT *
        FROM admin
        where `login`='$nom' and pass='$passe'" ;
 
    //exécution de la requête:
    $requete = mysqli_query( $cnx,$sql ) ;

    if(mysqli_fetch_object( $requete ))
    {
    header("Location: menu.php");
    }
    else
    {

      echo("accés refuse");
     /* header("Location: login.php");*/
    }
 

?>
</body>
</html>