<?php
$method = $_SERVER['REQUEST_METHOD'];
include 'conn.php';

if ($method === 'GET') {
	$sql = "SELECT * FROM pesoarnia";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$dato = array();
	  	while($row = $result->fetch_assoc()) {
			$dato[] = array(
				"esp_code" => array(
					"nome"=>$row["esp_type"],
					"href"=>"../esp/".$row["esp_type"]
				),
				"peso" => $row["peso"],
			);
		}
		header('Content-Type: application/json');
		echo json_encode(array("dato" => $dato));
	}	
} elseif ($method === 'POST') {
	// Leggi il payload JSON inviato nella richiesta
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	$esp_code = $data->esp_code;
	$peso = $data->peso;

	$sql = "INSERT INTO pesoarnia (esp_type, peso) VALUES ('$esp_code', $peso)";
	if ($conn->query($sql) === TRUE) {
		// Restituisci una risposta di successo
		header('Content-Type: application/json');
		echo json_encode(array("message" => "Dati inseriti con successo."));
	} else {
		// Restituisci una risposta di errore
		var_dump(http_response_code(500));
		echo "Errore: " . $sql . "<br>" . $conn->error;
	}
} elseif ($method === 'PUT') {
	// Leggi il payload JSON inviato nella richiesta
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);

	// Esegui l'aggiornamento dei dati nel database
	$id = $data->id;
	$esp_code = $data->esp_code;
	$peso = $data->peso;

	$sql = "UPDATE pesoarnia SET peso=$peso, esp_type='$esp_code', WHERE id=$id";
	if ($conn->query($sql) === TRUE) {
		// Restituisci una risposta di successo
		header('Content-Type: application/json');
		echo json_encode(array("message" => "Dati aggiornati con successo."));
	} else {
		// Restituisci una risposta di errore
		var_dump(http_response_code(500));
		echo "Errore: " . $sql . "<br>" . $conn->error;
	}
} elseif ($method === 'DELETE') {
	// Leggi il payload JSON inviato nella richiesta
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);

	// Esegui la cancellazione dei dati dal database
	$id = $data->id;

	$sql = "DELETE FROM pesoarnia WHERE id=$id";
	if ($conn->query($sql) === TRUE) {
		// Restituisci una risposta di successo
		header('Content-Type: application/json');
		echo json_encode(array("message" => "Dati cancellati con successo."));
	} else {
		// Restituisci una risposta di errore
		var_dump(http_response_code(500));
		echo "Errore: " . $sql . "<br>". $conn->error;
	}
} else {
// Metodo HTTP non riconosciuto
var_dump(http_response_code(501));
}
?>
