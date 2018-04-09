<?php
 
require "vendor/autoload.php";
function connect_to_db()
{
	$servername = "localhost";
	$username = "root";
	$password = "";
	try {
		    $conn = new PDO("mysql:host=$servername;dbname=events", $username, $password);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    return($conn);
	    }
	catch(PDOException $e)
	    {
	    	echo $sql . "<br>" . $e->getMessage();
	    	echo "Connexion impossible";
	    }
}

$app = new Slim\App();

$app->get("/api/events",function(){
$conn = connect_to_db();
$req = $conn->query("SELECT * FROM events");
foreach($req as $ligne)
{
	echo json_encode($ligne['name']);
}
});

$app->post("/api/events",function($request){
$conn = connect_to_db();
$body = $request->getBody();
$req = json_decode($body);
$res = $conn->query("INSERT INTO events (name, referrer) VALUES ('$req->name', '$req->referrer');");
});

$app->get('/api/events/{id}',function($request, $response, $args){
$conn = connect_to_db();
$tmp = $args['id'];
$req = $conn->query("SELECT * FROM events WHERE cookie_id = $tmp;");
foreach($req as $ligne)
{
	echo json_encode($ligne['name'].$ligne['referrer'].$ligne['createdAt']);
}
});

$app->get("/api/dashboard",function(){
$conn = connect_to_db();
$req = $conn->query("SELECT * FROM events ORDER BY createdAt");
foreach($req as $ligne)
{
	echo json_encode($ligne['name'].$ligne['referrer'].$ligne['createdAt']);
}
});

$app->run();

?>