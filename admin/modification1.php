<html lang="ar" dir="rtl">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>modification de données en PHP :: partie 1</title>
    <link rel="stylesheet" href="../assets/css/style.css">
  </head>
<body>
  <?php
    require_once __DIR__ . '/../config/database.php';
    //connection au serveur:
    $cnx = getDbConnection();
 
    $sql = "SELECT * FROM participant ORDER BY id" ;
 
    //exécution de la requête:
    $requete = mysqli_query( $cnx,$sql ) ;
 
    //affichage des données:
	echo("<table class=\"data-table\"><tr><td>n°</td><td>nom</td><td>prenom</td><td>adresse</td><td>operation</td>");
    while( $result = mysqli_fetch_object( $requete ) )
    {
      echo("<tr><td>".$result->id."</td><td>".$result->nom_prenom."</td><td>".$result->cin."</td><td>".$result->entreprise."</td><td><a href=\"modification2.php?idParticipant=".$result->id."\">modifier</a></td><td><a href=\"supprimer.php?idParticipant=".$result->id."\" onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?')\">supprimer</a></td></tr>\n"
       ) ;
    }

  ?>
   
</body>
</html>