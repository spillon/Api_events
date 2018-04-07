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
	    }
}

$app = new Slim\App();

$app->get("/api/events",function(){
$conn = connect_to_db();
$req = $conn->query("SELECT * FROM events");
echo "Liste des événements: ";
foreach($req as $ligne)
{
	echo "<br>".$ligne['name'];
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
	echo "Nom de l'événement n° $tmp: ".$ligne['name']."<br>"." Référence de l'événement: ".$ligne['referrer']."<br>"." Date de création: ".$ligne['createdAt'];
}
});

$app->get("/api/dashboard",function(){
$conn = connect_to_db();
setlocale(LC_TIME, 'fra_fra');
date_default_timezone_set("Europe/Brussels");
echo strftime('Date: %A %d %B %Y, Heure: %H:%M').'<br>'.'<br>';
$req = $conn->query("SELECT * FROM events ORDER BY createdAt");
foreach($req as $ligne)
{
	echo "Nom de l'événement: ".$ligne['name']."<br>"." Référence de l'événement: ".$ligne['referrer']."<br>"." Date de création: ".$ligne['createdAt']."<br>"."<br>";
}
});

$app->run();

?>