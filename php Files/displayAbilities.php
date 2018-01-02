<?php
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "sweetwog-db", "nNGL00vWPPsZWoub", "sweetwog-db");
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

?>
<!-- Two separate queries, the first to get the name and the second to make an unordered list of abilities -->	
<!DOCTYPE html>
<html>
  
  <body>
  <h1 style="text-align: center">Abilities/Powers</h1>
  <div>
		
			<p><strong>Name:</strong>
			
<?php
if(!($stmt2 = $mysqli->prepare("SELECT People.name FROM People WHERE People.id=?;"))){
	echo "Prepare failed: "  . $stmt2->errno . " " . $stmt2->error;
}

if(!($stmt2->bind_param("i",$_POST['displayAbilities']))){
	echo "Bind failed: "  . $stmt2->errno . " " . $stmt2->error;
}

if(!$stmt2->execute()){
	echo "Execute failed: "  . $stmt2->connect_errno . " " . $stmt2->connect_error;
}

if(!$stmt2->bind_result($name)){
	echo "Bind failed: "  . $stmt2->connect_errno . " " . $stmt2->connect_error;
}

while($stmt2->fetch()){
 echo $name . "</p>";
}

$stmt2->close();

if(!($stmt = $mysqli->prepare("SELECT Abilities.name FROM Abilities 
INNER JOIN Char_Abil ON Abilities.id = Char_Abil.aid
INNER JOIN People ON Char_Abil.cid = People.id
WHERE People.id=?;"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!($stmt->bind_param("i",$_POST['displayAbilities']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $stmt->connect_errno . " " . $stmt->connect_error;
}
if(!$stmt->bind_result($ability)){
	echo "Bind failed: "  . $stmt->connect_errno . " " . $stmt->connect_error;
}

echo "<ul>";
while($stmt->fetch()){
 echo "<li>" . $ability . "</li>";
}

$stmt->close();
echo "</br></br>Click here to go back -> <a href='http://web.engr.oregonstate.edu/~sweetwog/projectPHP.php'>POW</a>";
?>
		</ul>
	</div>

	</body>
</html>