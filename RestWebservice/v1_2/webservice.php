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
	//Insert new contact into database
	$query1 = "INSERT INTO `fellows` (`Name`) VALUES ('".$mName."')";

	//Execute query1
	$stmt = $mysqli->query($query1);
	
	if (!$stmt)
	{	//The query failed
		echo 'Query 1 Failed';	
	}

	//Get new contact info from database
	$query2 = "SELECT ID,Name FROM `fellows` WHERE `Name`='".$mName."'";

	//Execute query2
	$result = $mysqli->query($query2);
	
	if (!$result)
	{	//The query failed
		echo 'Query 2 Failed';
	}
	
	//Return new Fellow and ID
	$row = $result->fetch_assoc();
	$jsonpost = json_encode($row);
	file_put_contents('php://output',$jsonpost);
}

function DBChangeFellow($mID, $mName, $mysqli)
{
	// Change the Name of this Fellow
	$query1 = "UPDATE `fellows` SET `Name`='".$mName."' WHERE `ID`='".$mID."'";
	
	//Execute query1
	$stmt = $mysqli->query($query1);
	
	if (!$stmt)
	{	//The query failed
		echo 'Query 1 Failed';	
	}
	
	//Get new contact info from database
	$query2 = "SELECT ID,Name FROM `fellows` WHERE `Name`='".$mName."'";

	//Execute query2
	$result = $mysqli->query($query2);
	
	if (!$result)
	{	//The query failed
		echo 'Query 2 Failed';	
	}
	
	//Return new Fellow and ID
	$row = $result->fetch_assoc();
	$jsonpost = json_encode($row);
	file_put_contents('php://output',$jsonpost);
}

function DBRemoveFellow($mID, $mysqli)
{
	//Insert new game into database
	$query1 = "SET @PID = (SELECT ID FROM `fellows` WHERE `ID`='".$mID."');";
	
	//Execute query
	$stmt = $mysqli->query($query1);

	if (!$stmt)
	{	//The query failed
		echo 'Query 1 Failed';	
	}
	
	$query2 = "DELETE FROM `games` WHERE `PlayerID`=@PID;";
	//Execute query
	$stmt = $mysqli->query($query2);

	if (!$stmt)
	{	//The query failed
		echo 'Query 2 Failed';	
	}
	
	$query3 = "DELETE FROM `fellows` WHERE `ID`=@PID;";
	//Execute query
	$stmt = $mysqli->query($query3);

	if (!$stmt)
	{	//The query failed
		echo 'Query 3 Failed';	
	}
}

function DBGetGames($mysqli)
{
	//The query succeeded, now echo back the new contact ID
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
	//The query succeeded, now echo back the new contact ID
	$query = "SELECT * FROM `games` WHERE `PlayerID`='" .$mPID. "'; ";
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

function DBAddGame($mPlayerID, $mScore, $mysqli)
{
	$query1 = "INSERT INTO `games` (`PlayerID`, `Score`) VALUES ('".$mPlayerID."','".$mScore."');";
	//Execute query
	$stmt = $mysqli->query($query1);

	if (!$stmt)
	{	//The query failed
		echo 'Query 1 Failed';	
	}
}

function DBRemoveGame($mID, $mysqli)
{
	//Insert new game into database
	$query1 = "DELETE FROM `games` WHERE `ID`='" .$mID. "';";
	
	//Execute query
	$stmt = $mysqli->query($query1);
	
	if (!$stmt)
	{	//The query failed
		echo 'Query 1 Failed';
	}
}

function DBGetScoringTable($mysqli)
{
	//Insert new game into database
	$query1 = "SELECT * FROM `fellows`;";
	//Execute query
	$result1 = $mysqli->query($query1);

	if (!$result1)
	{	//The query failed
		echo 'Query 1 Failed';	
	}
	else{
		file_put_contents('php://output',"[");
		if($row1 = $result1->fetch_assoc()) {

			$query2 = "SELECT * FROM `games` WHERE `PlayerID`='" . $row1['ID'] . "' AND `Date` >= TIMESTAMPADD(HOUR,-8,CURRENT_TIMESTAMP);";
			//Execute query
			$result2 = $mysqli->query($query2);
		
			if (!$result2)
			{	//The query failed
				echo 'Query 2 Failed';	
			}
			else
			{
				$sum = 0;
				$count = 0;
				while($row2 = $result2->fetch_assoc())
				{
					$sum += $row2['Score'];
					$count += 1;
				}
			}
			$row1['Score'] = $sum;
			$row1['Games'] = $count;
			
			$jsonpost = json_encode($row1);
			file_put_contents('php://output', $jsonpost);
		}
		while ($row1 = $result1->fetch_assoc()) {

			$query2 = "SELECT * FROM `games` WHERE `PlayerID`='" . $row1['ID'] . "' AND `Date` >= TIMESTAMPADD(HOUR,-8,CURRENT_TIMESTAMP);";
			//Execute query
			$result2 = $mysqli->query($query2);
		
			if (!$result2)
			{	//The query failed
				echo 'Query 2 Failed';	
			}
			else
			{
				$sum = 0;
				$count = 0;
				while($row2 = $result2->fetch_assoc())
				{
					$sum += $row2['Score'];
					$count += 1;
				}
			}
			$row1['Score'] = $sum;
			$row1['Games'] = $count;
			$row1['Median'] = 0;
			
			$jsonpost = json_encode($row1);
			file_put_contents('php://output',"," . $jsonpost);
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