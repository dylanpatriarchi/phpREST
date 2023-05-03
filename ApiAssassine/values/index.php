<?php
$method = $_SERVER['REQUEST_METHOD'];
include 'conn.php';

if ($method === 'GET') {
	$sql = "SELECT * FROM (value JOIN beehives ON fk_id_bhv=id_bhv)JOIN esp ON fk_id_esp=id_esp";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$dato = array();
	  	while($row = $result->fetch_assoc()) {
			$dato[] = array(
				"id"=>$row["id_val"],
				"weight" => $row["weight"],
				"temperature"=>$row["temperature"],
				"humidity"=>$row["humidity"],
				"noise_level"=>$row["noise_level"],
				"beehive"=>$array(
					"name_beehive"=>$row["nome_bhv"],
					"esp_code" => array(
						"esp_name"=>$row["id_esp"],
						"href"=>"../esp/".$row["id_esp"]
					)
				),
				"timestamp"=>$row["timestamp"]
			);
		}
		header('Content-Type: application/json');
		echo json_encode(array("dato" => $dato));
	}	
} elseif ($method === 'POST') {
	// Leggi il payload JSON inviato nella richiesta
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	$weight = $data->weight;
	$temperature = $data->temperature;
	$humidity = $data->humidity;
	$noise_level = $data->noise_level;
	$fk_id_bhv = $data->fk_id_bhv;
	$timestamp = $data->timestamp;
	

	$sql = "INSERT INTO value (weight, temperature,humidity,noise_level,fk_id_bhv,timestamp) VALUES ($weight, $temperature,$humidity,$noise_level,$fk_id_bhv,'$timestamp')";
	if ($conn->query($sql) === TRUE) {
		// Restituisci una risposta di successo
		header('Content-Type: application/json');
		echo json_encode(array("message" => "Dati inseriti con successo."));
	} else {
		// Restituisci una risposta di errore
		var_dump(http_response_code(500));
	}
} elseif ($method === 'PUT') {
	// Leggi il payload JSON inviato nella richiesta
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);

	$weight = $data->weight;
	$temperature = $data->temperature;
	$humidity = $data->humidity;
	$noise_level = $data->noise_level;
	$fk_id_bhv = $data->fk_id_bhv;
	$timestamp = $data->timestamp;

	$sql = "UPDATE value SET weight=$weight, temperature=$temperature,humidity=$humidity,noise_level=$noise_level,fk_id_bhv=$fk_id_bhv ,timestamp='$timestamp' WHERE id=$id";
	if ($conn->query($sql) === TRUE) {
		// Restituisci una risposta di successo
		header('Content-Type: application/json');
		echo json_encode(array("message" => "Dati aggiornati con successo."));
	} else {
		// Restituisci una risposta di errore
		var_dump(http_response_code(500));
	}
} elseif ($method === 'DELETE') {
	$id = $_REQUEST["id"];

	$sql = "DELETE FROM value WHERE id_val=$id";
	if ($conn->query($sql) === TRUE) {
		// Restituisci una risposta di successo
		header('Content-Type: application/json');
		echo json_encode(array("message" => "Dati cancellati con successo."));
	} else {
		// Restituisci una risposta di errore
		var_dump(http_response_code(500));
	}
} else {
// Metodo HTTP non riconosciuto
var_dump(http_response_code(501));
}
?>
