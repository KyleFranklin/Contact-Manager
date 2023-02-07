
<?php
$inData = getRequestInfo();
	
$id = 0;
$login = "";
$password = "";
$firstName = "";
$lastName = "";
$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
if( $conn->connect_error )
{
    returnWithError( $conn->connect_error );
}
 else
 {
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Login=?");
	$stmt->bind_param("s", $inData["login"]);
	$stmt->execute();

	echo $inData["login"] . " " . $inData["password"] . " " . $inData["firstName"] . " " . $inData["lastName"];



	$result = $stmt->get_result();

	if( $row = $result->fetch_assoc()  )
	{
		echo "ALREADY EXISTS";
	}
	else
	{
		echo "does not exist";
		$stmt = $conn->prepare("INSERT INTO Users (Login,Password,FirstName,LastName) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $inData["login"], $inData["password"],$inData["firstName"],$inData["lastName"]);
		$stmt->execute();
		echo "YOU MADE IT";
	}
	$stmt->close();
	$conn->close();
 }


 function getRequestInfo()
{
    return json_decode(file_get_contents('php://input'), true);
}

function sendResultInfoAsJson( $obj )
{
    header('Content-type: application/json');
    echo $obj;
}

function returnWithError( $err )
{
    $retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
    sendResultInfoAsJson( $retValue );
}

function returnWithInfo( $firstName, $lastName, $id )
{
    $retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
    sendResultInfoAsJson( $retValue );
}
?>
