<?php

require_once('./DBConnectionInfo.php');
		
$jsonpost = json_decode(file_get_contents('php://input'));

if(isset($_GET['cmd']))
{
	$cmd = $_GET['cmd'];
	//Set up our connection
	$mysqli = GetConnection();
	
	if (!$mysqli)
	{
		//Connection failed
		echo 'No Connection to MySQL Database';
	}
	else
	{
		$_SERVER['PHP_AUTH_USER'] = 'appadmin';
		switch($cmd)
		{
			case 'getF':
				DBGetFellows($mysqli);
				break;
			case 'addF':
				if($_SERVER['PHP_AUTH_USER'] == 'appadmin')
				{
				    DBAddFellow($jsonpost->Name, $mysqli);
				}
				break;
			case 'chaF':
				if($_SERVER['PHP_AUTH_USER'] == 'appadmin')
				{
				    DBChangeFellow($jsonpost->ID, $jsonpost->Name, $mysqli);
				}
				break;
			case 'remF':
				if($_SERVER['PHP_AUTH_USER'] == 'appadmin')
				{
				    DBRemoveFellow($jsonpost->ID, $mysqli);
				}
				break;
			case 'getG':
				DBGetGames($mysqli);
				break;
			case 'getGoF':
				DBGetGamesOfFellow($jsonpost->ID,$mysqli);
				break;
			case 'addG':
				DBAddGame($jsonpost->PlayerID,$jsonpost->Score,$mysqli);
				break;
			case 'remG':
				if($_SERVER['PHP_AUTH_USER'] == 'appadmin')
				{
				    DBRemoveGame($jsonpost->ID,$mysqli);
				}
				break;
			case 'getST':
				DBGetScoringTable($mysqli);
				break;
			case 'init':
				DBInitTables($mysqli);
		}
	}
}
else
{
	echo 'No command found';
}


/////////////////////////////////////////////////////////////////////
//    Database Interface Functions
/////////////////////////////////////////////////////////////////////

function DBGetFellows($mysqli)
{
	//The query succeeded, now echo back the new contact ID
	$query = "SELECT * FROM `fellows`";
	$result = $mysqli->query($query);
	if (!$result)
	{	//The query failed
		echo 'Query Failed';	
	}
	
	file_put_contents('php://output',"[");
	if($row = $result->fetch_assoc())
	{
		$jsonpost = json_encode($row);
		file_put_contents('php://output',$jsonpost);
	}
	while ($row = $result->fetch_assoc()) {
		$jsonpost = json_encode($row);
		file_put_contents('php://output', "," . $jsonpost);
	}
	file_put_contents('php://output',"]");
}

function DBAddFellow($mName, $mysqli)
{
	//Prepare INSERT
	if (!($stmt = $mysqli->prepare("INSERT INTO `fellows` (`Name`) VALUES (?)")))
	{
		echo "Prepare 1 failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//Bind
	if (!$stmt->bind_param("s", $mName))
	{
		echo "Binding 1 parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if (!$stmt->execute()) {
		echo "Execute 1 failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	
	//Prepare SELECT
	if (!($stmt = $mysqli->prepare("SELECT ID,Name FROM `fellows` WHERE `Name`=?")))
	{
		echo "Prepare 2 failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//Bind
	if (!$stmt->bind_param("s", $mName))
	{
		echo "Binding 2 parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if (!$stmt->execute()) {
		echo "Execute 2 failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	$result = $stmt->get_result();
	
	//Return new Fellow and ID
	$row = $result->fetch_assoc();
	$jsonpost = json_encode($row);
	file_put_contents('php://output',$jsonpost);
}

function DBChangeFellow($mID, $mName, $mysqli)
{
	//Prepare UPDATE
	if (!($stmt = $mysqli->prepare("UPDATE `fellows` SET `Name`=? WHERE  `ID`=?")))
	{
		echo "Prepare 1 failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//Bind
	if (!$stmt->bind_param("si", $mName, $mID))
	{
		echo "Binding 1 parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if (!$stmt->execute()) {
		echo "Execute 1 failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	//Prepare SELECT
	if (!($stmt = $mysqli->prepare("SELECT ID,Name FROM `fellows` WHERE `Name`=?")))
	{
		echo "Prepare 2 failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//Bind
	if (!$stmt->bind_param("s", $mName))
	{
		echo "Binding 2 parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if (!$stmt->execute()) {
		echo "Execute 2 failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	$result = $stmt->get_result();
	
	//Return new Fellow and ID
	$row = $result->fetch_assoc();
	$jsonpost = json_encode($row);
	file_put_contents('php://output',$jsonpost);
}

function DBRemoveFellow($mPID, $mysqli)
{
	//Prepare DELETE
	if (!($stmt = $mysqli->prepare("DELETE FROM `games` WHERE `PlayerID`=?")))
	{
		echo "Prepare 1 failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//Bind
	if (!$stmt->bind_param("i",$mPID))
	{
		echo "Binding 1 parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if (!$stmt->execute()) {
		echo "Execute 1 failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	//Prepare DELETE
	if (!($stmt = $mysqli->prepare("DELETE FROM `fellows` WHERE `ID`=?")))
	{
		echo "Prepare 2 failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//Bind
	if (!$stmt->bind_param("i",$mPID))
	{
		echo "Binding 2 parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if (!$stmt->execute()) {
		echo "Execute 2 failed: (" . $stmt->errno . ") " . $stmt->error;
	}
}

function DBGetGames($mysqli)
{
	
	$query = "SELECT * FROM `games`";
	$result = $mysqli->query($query);
	if (!$result)
	{	//The query failed
		echo 'Query Failed';	
	}
	
	while ($row = $result->fetch_assoc()) {
		$jsonpost = json_encode($row);
		file_put_contents('php://output',$jsonpost);
	}
}

function DBGetGamesOfFellow($mPID,$mysqli)
{
	//Prepare SELECT
	if (!($stmt = $mysqli->prepare("SELECT * FROM `games` WHERE `PlayerID`=?")))
	{
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//Bind
	if (!$stmt->bind_param("i",$mPID))
	{
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	$result = $stmt->get_result();
	
	file_put_contents('php://output',"[");
	if($row = $result->fetch_assoc())
	{
		$jsonpost = json_encode($row);
		file_put_contents('php://output',$jsonpost);
	}
	while ($row = $result->fetch_assoc()) {
		$jsonpost = json_encode($row);
		file_put_contents('php://output', "," . $jsonpost);
	}
	file_put_contents('php://output',"]");
}

function DBAddGame($mPlayerID, $mScore, $mysqli)
{
	//Prepare INSERT
	if (!($stmt = $mysqli->prepare("INSERT INTO `games` (`PlayerID`, `Score`) VALUES (?,?)")))
	{
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	// Bind
	if (!$stmt->bind_param("is", $mPlayerID, $mScore))
	{
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}

}

function DBRemoveGame($mID, $mysqli)
{
	//Prepare DELETE
	if (!($stmt = $mysqli->prepare("DELETE FROM `games` WHERE `ID`=?")))
	{
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//Bind
	if (!$stmt->bind_param("i", $mID))
	{
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
}

function DBGetScoringTable($mysqli)
{
	//Prepare DELETE
	if (!($stmt = $mysqli->prepare("SELECT * FROM `fellows`")))
	{
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//Execute
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	else{
		$result1 = $stmt->get_result();
		$rowsDone = 0;
		
		file_put_contents('php://output',"[");
		while ($rowsDone < $result1->num_rows) {

			$row1 = $result1->fetch_assoc();
			
			//Prepare SELECT
			if (!($stmt = $mysqli->prepare("SELECT * FROM `games` WHERE `PlayerID`=? AND `Date` >= TIMESTAMPADD(HOUR,-8,CURRENT_TIMESTAMP)")))
			{
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			//Bind
			if (!$stmt->bind_param("i", $row1['ID']))
			{
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Execute
			if (!$stmt->execute()) {
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			else
			{
				$result2 = $stmt->get_result();
				$sum = 0;
				$count = 0;
				while($count < $result2->num_rows)
				{
					$row2 = $result2->fetch_assoc();
					$sum += $row2['Score'];
					$count += 1;
				}
			}
			$row1['Score'] = $sum;
			$row1['Games'] = $count;
			$row1['Median'] = 0;
			
			$jsonpost = json_encode($row1);
			file_put_contents('php://output', $jsonpost);
			
			$rowsDone += 1;
			if($rowsDone < $result1->num_rows)
			{
				file_put_contents('php://output',",");
			}
		}
		file_put_contents('php://output',"]");
	}
}

function DBInitTables($mysqli)
{
	// Create Table Fellows
	$query1 = "CREATE TABLE `fellows` ( `ID` INT NOT NULL AUTO_INCREMENT , `Name` VARCHAR(20) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB;";
	//Execute query
	$result1 = $mysqli->query($query1);

	if (!$result1)
	{	//The query failed
		echo 'Query 1 Failed';	
	}
	echo $result1;
	// Create Table Games
	$query2 = "CREATE TABLE `games` ( `ID` INT NOT NULL AUTO_INCREMENT , `Date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `PlayerID` INT NOT NULL , `Score` INT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB;";
	//Execute query
	$result2 = $mysqli->query($query2);

	if (!$result2)
	{	//The query failed
		echo 'Query 2 Failed';	
	}
}
?>