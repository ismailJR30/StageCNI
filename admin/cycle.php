<html lang="ar" dir="rtl">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>ajout cycle</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <h2>المركز الوطني للإعلامية </h2>
    <h3>وحدة التكوين و الرسكلة</h3>
    <h1><center>إضافة دورة تكوينية</center></h1>
  </head>
<body>
<?php
require_once __DIR__ . '/../config/database.php';
?>
<div class="form-container">
<form name="cycle_form" action="insertion_cycle.php" method="POST">
 
     <h4>رقم العملية </h4>
      <input type="text" name="num_act">
    
      <h4>االدورة التكوينية </h4>
      <input type="text" name="theme">
    
      <h4>تاريخ البداية</h4>
      <input type="date" name="date_deb">
   
      <h4>تاريخ النهاية</h4>
      <input type="date" name="date_fin">
<br>
      <h4>مكون عددد1</h4>
      <select name="form_1" class="wide-select">
        <option>إضافة مكون</option>
        <?php
        $cnx = getDbConnection();
        $reponse = mysqli_query($cnx,"SELECT nom_prenom FROM formateur");
        while ($donnees =  mysqli_fetch_array($reponse))
          {
            ?>
         <option value="<?php echo $donnees['nom_prenom'] ?>"><?php echo $donnees['nom_prenom'];?></option>
            <?php
         }
            ?>
      </select>
<br>
 <h4>مكون عددد2</h4>
      <select name="form_2" class="wide-select">
         <option>إضافة مكون</option>
        <?php
        $cnx = getDbConnection();
        $sql="SELECT nom_prenom FROM formateur";
        $reponse = mysqli_query($cnx,$sql);
        while ($donnees =  mysqli_fetch_array($reponse))
          {
            ?>
         <option value="<?php echo $donnees['nom_prenom'] ?>"><?php echo $donnees['nom_prenom'];?></option>
            <?php
         }
            ?>
      </select>
<br>
 <h4>مكون عددد3</h4>
      <select name="form_3" class="wide-select">
         <option>إضافة مكون</option>
        <?php
        $cnx = getDbConnection();
        $reponse = mysqli_query($cnx,"SELECT nom_prenom FROM formateur");
        while ($donnees =  mysqli_fetch_array($reponse))
          {
            ?>
         <option value="<?php echo $donnees['nom_prenom'] ?>"><?php echo $donnees['nom_prenom'];?></option>
            <?php
         }
            ?>
      </select>
<br>
     <select name="num_salle">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
            <option>6</option>
            <option>7</option>
      </select>
 <br>


       <input type="submit" value="تسجيل">
 
</form>
</div>
</body>
</html>