<?php
/** create XML file */
$mysqli = new mysqli("localhost", "root", "", "guestbook");
//$yhteys = mysqli_connect("localhost", "root", "", "guestbook");

/* check connection */
if ($mysqli->connect_errno) {
    echo "Connect failed ".$mysqli->connect_error;
    exit();
}
$query = "SELECT id, name, email, message, time, date FROM guestbook";
$guestsArray = array();
if ($result = $mysqli->query($query)) {
    /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        array_push($guestsArray, $row);
    }
    
    if(count($guestsArray)){
        createXMLfile($guestsArray);
    }
    /* free result set */
    $result->free();
}
/* close connection */
$mysqli->close();
function createXMLfile($guestsArray){
    
    $filePath = 'guest.xml';
    $dom     = new DOMDocument('1.0', 'utf-8');
    $root      = $dom->createElement('guests');
    for($i=0; $i<count($guestsArray); $i++){
        
        $guestId        =  $guestsArray[$i]['id'];
        $guestName = htmlspecialchars($guestsArray[$i]['name']);
        $guestEmail    =  $guestsArray[$i]['email'];
        $guestMessage     =  $guestsArray[$i]['message'];
        $guestTime      =  $guestsArray[$i]['time'];
        $guestDate  =  $guestsArray[$i]['date'];
        $guest = $dom->createElement('guest');
        $guest->setAttribute('id', $guestId);
        $name     = $dom->createElement('name', $guestName);
        $guest->appendChild($name);
        $email   = $dom->createElement('email', $guestEmail);
        $guest->appendChild($email);
        $message    = $dom->createElement('message', $guestMessage);
        $guest->appendChild($message);
        $time    = $dom->createElement('time', $guestTime);
        $guest->appendChild($time);
        $date = $dom->createElement('date', $guestDate);
        $guest->appendChild($date);
        
        $root->appendChild($guest);
    }
    $dom->appendChild($root);
    $dom->save($filePath);
} 
?>