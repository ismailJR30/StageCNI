<html lang="ar" dir="rtl">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Fiche Incription Arabe</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <h2>المركز الوطني للإعلامية </h2>
    <h3>وحدة التكوين و الرسكلة</h3>
    <h1><center>بطاقة حضور </center></h1>
  </head>
<body>
<div class="form-container">
<form name="insertion" action="insertion_part.php" method="POST">
 
     <h4>الإسم و اللقب  </h4>
      <input type="text" name="nom_prenom">
      <h4>بطاقة التعريف الوطنية</h4>
      <input type="text" name="cin">
      <h4>المؤسسة </h4>
      <input type="text" name="entreprise">
    
      <h4>الهاتف القار</h4>
      <input type="text" name="tel_fix">
   
      <h4>الفاكس</h4>
      <input type="text" name="fax">
    
      <h4>الهاتف الجوال</h4>
      <input type="text" name="tel_port">
    
     <h4> العنوان الإلكتروني</h4>
      <input type="text" name="mail">
<h4>الدورة  التكوينية </h4> <br>
      <select name="theme" >
        <option>الدورة التكوينية</option>
<?php
require_once __DIR__ . '/../config/database.php';
$cnx = getDbConnection();
 $sql3="SELECT theme FROM cycle";
$reponse = mysqli_query($cnx,$sql3);
while ($donnees =  mysqli_fetch_array($reponse))
{
?>
<option value="<?php echo $donnees['theme'] ?>"><?php echo $donnees['theme'];?></option>
   <?php
   }
   ?>
</select>
<br>
      <h4>قاعة عدد </h4>
<br>

      <select name="num_salle">
            <option>عدد القاعة</option>
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
            <option>6</option>
            <option>7</option>
      </select>
 <br>
      <h4>تاريخ بداية الدورة التكوينية</h4>
       <br> <br>

      <input type="date" name="date_debut">

     <br> <br>

       <input type="submit" value="تسجيل">
 
</form>
<form name= "import" action="importexcel.php" method="POST">
  
 <input type="submit" value="importer">
 

</form>
</div>
<?php include '../widget/chat-widget.php'; ?>
</body>
</html>