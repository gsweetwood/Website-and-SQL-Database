<?php
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "sweetwog-db", "nNGL00vWPPsZWoub", "sweetwog-db");
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

?>
<!-- Filters the table based on user selected morality and then put into a table-->	
<!DOCTYPE html>
<html>
  
  <body>
  <h1 style="text-align: center">Filtered Results</h1>
  <div>
	<table>
		<tr>
			<td><strong>DC Universe People</td>
		</tr>
		<tr>
			<td><strong>Name</strong></td>
			<td><strong>Gender</strong></td>
			<td><strong>Origin</strong></td>
			<td><strong>Morality</strong></td>
		</tr>
<?php
if(!($stmt = $mysqli->prepare("SELECT People.name, People.gender, Origin.city, Morality.moral_tendancy FROM People 
INNER JOIN Origin ON People.fk_origin_id = Origin.id
INNER JOIN Morality ON People.fk_moral_id = Morality.id
WHERE Morality.id=?;"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!($stmt->bind_param("i",$_POST['filterMorality']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($name, $gender, $origin, $moral)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $name . "\n</td>\n<td>\n" . $gender . "\n</td>\n<td>\n" . $origin . "\n</td>\n<td>\n" . $moral . "\n</td>\n</tr>";
}
$stmt->close();
echo "Click here to go back -> <a href='http://web.engr.oregonstate.edu/~sweetwog/projectPHP.php'>POW</a>";
?>
	</table>
	
</div>

</body>
</html>