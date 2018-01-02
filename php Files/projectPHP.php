<?php
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "sweetwog-db", "nNGL00vWPPsZWoub", "sweetwog-db");
if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_errno;
}
?>

<!DOCTYPE html>
<html>
  <head>
  	<meta charset="UTF-8">
	<title>Sweetwood CS 340 Project</title>
  </head>
  <body>
  <h1 style="text-align: center">Welcome to the DC Universe Character Database</h1>
  <div>
	<table>
		<tr>
			<td><strong>DC Universe People</strong></td>
		</tr>
		<tr>
			<td><strong>Name</strong></td>
			<td><strong>Gender</strong></td>
			<td><strong>Origin</strong></td>
			<td><strong>Morality</strong></td>
		</tr>
<!-- Display all the characters in a table -->	
<?php
if(!($stmt = $mysqli->prepare("SELECT People.name, People.gender, Origin.city, Morality.moral_tendancy FROM People 
INNER JOIN Origin ON People.fk_origin_id = Origin.id
INNER JOIN Morality ON People.fk_moral_id = Morality.id;"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
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
?>
	</table>
</div>
  
  <!-- Filter the results by morality, goes to new page -->	
  <div>
	<form method="post" action="filterMorality.php">
			</br><legend>Filter By Morality</legend>
				<select name="filterMorality">
					<?php
					if(!($stmt = $mysqli->prepare("SELECT id, moral_tendancy FROM Morality"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}

					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($id, $moral)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo '<option value=" '. $id . ' "> ' . $moral . '</option>\n';
					}
					$stmt->close();
					?>
				</select>
		<input type="submit" value="Filter" />
	</form>
</div>

<!-- Display all the character's abilities in list form, goes to new page -->	
<div>
	<form method="post" action="displayAbilities.php">
			</br><legend>Show a Character's Abilities</legend>
			<select name="displayAbilities">
<?php
if(!($stmt = $mysqli->prepare("SELECT id, name FROM People"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($id, $pname)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
	echo '<option value=" '. $id . ' "> ' . $pname . '</option>\n';
}
$stmt->close();
?>
			</select>
		<p><input type="submit"value="Display Abilities"/></p>
	</form>
</div>
  
  <!-- Adds a character to the People table -->	
  <div>
	<h3>ADD A CHARACTER</h3>
	<form method="post" action="addCharacter.php"> 

		<fieldset>
			<legend>Name</legend>
			<p>Character Name: <input type="text" name="CharName" /></p>
		</fieldset>

		<fieldset>
			<legend>Gender</legend>
			<p>Gender: <input type="text" name="Gender" /></p>
		</fieldset>
		<p>Don't see the correct options?  Add it in the forms below first.</p>
		<fieldset>
			<legend>Origin</legend>
			
<!-- Dropdown for autopopulated origins -->				
			<select name="Origin">
<?php
if(!($stmt = $mysqli->prepare("SELECT id, city FROM Origin"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($id, $pname)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
	echo '<option value=" '. $id . ' "> ' . $pname . '</option>\n';
}
$stmt->close();
?>
			</select>
		</fieldset>
<!-- Dropdown for autopopulated moralities -->			
		<fieldset>
			<legend>Morality</legend>
			<select name="moralSel">
<?php
if(!($stmt = $mysqli->prepare("SELECT id, moral_tendancy FROM Morality"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($id, $moral)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
	echo '<option value=" '. $id . ' "> ' . $moral . '</option>\n';
}
$stmt->close();
?>
			</select>
		</fieldset>
		<p><input type="submit"value="Add Character"/></p>
	</form>
</div>
  
  	  <div>
	  
<!-- Allows user to add abilities to a character, one a at time.  Restricts user to dropdown selection of characters
	and abilities already in the tables.  -->		  
	<h3>GIVE A CHARACTER AN ABILITY</h3>
	<form method="post" action="addAbilToChar.php"> 

		<fieldset>
			<label>Choose a character:</label>
			<select name="charSel">
<?php
if(!($stmt = $mysqli->prepare("SELECT id, name FROM People"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($id, $pname)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
	echo '<option value=" '. $id . ' "> ' . $pname . '</option>\n';
}
$stmt->close();
?>
			</select>
			<label>Choose an ability to add:</label>
			<select name="abilSel">
<?php
if(!($stmt = $mysqli->prepare("SELECT id, name FROM Abilities"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($id, $pname)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
	echo '<option value=" '. $id . ' "> ' . $pname . '</option>\n';
}
$stmt->close();
?>
			</select>
		</fieldset>
		<p><input type="submit"value="Add Ability to Character"/></p>
	</form>
</div>
  
  </div>
  
  <!-- Allows user to create a new origin and add to the Origin table -->	
	<div>
		<h3>ADD AN ORIGIN</h3>
		<form method="post" action="addOrigin.php"> 

			<fieldset>
				<p>Planet Name: <input type="text" name="planet" /></p>
				<p>Country: <input type="text" name="country" /></p>
				<p>City: <input type="text" name="city" /></p>
			</fieldset>
			<p><input type="submit"value="Add Origin"/></p>
		</form>
	
	
	</div>

<!-- Allows user to create a new ability and add to the Abilities table -->		
	 <div>
		<h3>ADD AN ABILITY</h3>
		<form method="post" action="addAbility.php"> 

			<fieldset>
				<p>Ability: <input type="text" name="ability" /></p>
				<p>Supernatural?:
				<select name="supernatural">
					<option value="Yes">Yes</option>
					<option value="No">No</option>
				</select></p>
			</fieldset>
			<p><input type="submit"value="Add Ability"/></p>
		</form>
	
	
</div>

<!-- Allows user to create a new morality and add to the Morality table -->	
<div>
		<h3>ADD A MORALITY</h3>
		<form method="post" action="addMorality.php"> 

			<fieldset>
				<p>Morality: <input type="text" name="morality" /></p>
				<p>Affiliated with: <input type="text" name="affiliation" /></p>
			</fieldset>
			<p><input type="submit"value="Add Affiliation"/></p>
		</form>
	
	
	</div>
	


	  <div>
<!-- Allows user delete one character from the People table -->		  
		<h3>DELETE A CHARACTER </h3>
		<form method="post" action="deleteChar.php"> 

			<fieldset>
				<label>Choose a character:</label>
				<select name="charDel">
<?php
if(!($stmt = $mysqli->prepare("SELECT id, name FROM People"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($id, $pname)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
	echo '<option value=" '. $id . ' "> ' . $pname . '</option>\n';
}
$stmt->close();
?>
				</select>
			</fieldset>
			<p><input type="submit"value="Delete Character"/></p>
		</form>
	</div>
  </body>
</html>