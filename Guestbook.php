<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>vieraskirja</title>
</head>
<body>

<h2> Post your message! </h2>
<form action='vittusaatana.php' method='post'>
<table>
<tr>
    <td> Name: </td>
    <td><input type='text' name='name' style='width: 200px;' /></td>
</tr>
<tr>
    <td> Email: </td>
    <td><input type='text' name='email' style='width: 200px;' /></td>
</tr>
<tr>
    <td> Message: </td>
    <td><textarea name='message' style='width: 200px; height: 100px;'></textarea></td>
</tr>
<tr>
    <td></td>
    <td><input type='submit' name='postbtn' value='Post' /></td>
</tr>
</table>
</form>

<?php
if (isset($_POST["name"])){
    $name=$_POST["name"];
}
else{
    $name="";
}

if (isset($_POST["email"])){
    $email=$_POST["email"];
}
else{
    $email="";
}

if (isset($_POST["message"])){
    $message=$_POST["message"];
}
else{
    $message="";
}

$yhteys = mysqli_connect("localhost", "root");

// Check connection
if (!$yhteys) {
    die("Yhteyden muodostaminen epäonnistui: " . mysqli_connect_error());
}
echo ""; // debuggia

$tietokanta=mysqli_select_db($yhteys, "guestbook");
if (!$tietokanta) {
    die("Tietokannan valinta epäonnistui: " . mysqli_connect_error());
}
echo ""; // debuggia


if (isset($_POST['postbtn'])) {
    $name = strip_tags($_POST['name']);
    $email = strip_tags($_POST['email']);
    $message = strip_tags($_POST['message']);
    
    if ($name && $email && $message) {
        
        $date = date("F d, Y");
        $time = date("H:i");
        
        //add to the db
        
        $sql=("INSERT INTO guestbook(name, email, message, time, date) values(?, ?, ?, ?, ?)");
        $stmt=mysqli_prepare($yhteys, $sql);
        mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $message, $time, $date);
        mysqli_stmt_execute($stmt);
        
        echo "Your post has been added.";
        
    }
    else
        echo "You didn't enter in all the required information.";
        
}

$tulos=mysqli_query($yhteys, "SELECT * FROM guestbook ORDER BY id DESC");

echo "<h2> Current Posts </h2>";
echo "<hr/>";
while ($rivi=mysqli_fetch_assoc($tulos)){
    $id =$rivi['id'];
    $name =$rivi['name'];
    $email =$rivi['email'];
    $message =$rivi['message'];
    $time =$rivi['time'];
    $date =$rivi['date'];
    
    $message = nl2br($message);
    
    echo "<div>
                by <b>$name</b> - at <b>$time</b> on <b>$date</b><br/>
                $message
                </div> <hr/";
}

mysqli_close($yhteys);
?>
</body>
</html>