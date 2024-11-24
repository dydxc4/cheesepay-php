<?php

require __DIR__ . '/query_response.php';
require __DIR__ . '/../models/student.php';
require __DIR__ . '/../functions/helpers.php';

// establece el tipo de respuesta como archivo JSON
header('Content-Type: text/json');
// declara un objeto para almacenar la respuesta
$response = null;

// verifica que el método de la petición sea POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // verifica si los campos requeridos están definidos y no son nulos
    if (isset($_POST['student_id'], $_POST['ssn'])) {
        // obtiene los valores de los campos y los limpia
        $student_id = satinize($_POST['student_id']);
        $ssn = satinize($_POST['ssn']);
        
        // crea un objeto alumno
        $student = new Student($student_id);
        // llama a la función para actualizar el valor y almacena el resultado
        $success = $student->update_ssn($ssn);
        // crea un objeto de respuesta según sea el caso
        $response = $success ? 
            new QueryResponse(QueryResponse::OK) :
            QueryResponse::create_error('Error updating registry');
    } else {
        // crea un objeto de respuesta en caso de recibirse una petición sin los
        // parámetros requeridos
        $response = QueryResponse::create_error('Malformed request');
    }
} else {
    // crea un objeto de respuesta en caso de recibirse un método de petición 
    // erroneo
    $response = QueryResponse::create_error('Invalid request method');
}

// despliega el objeto de respuesta en formato JSON
echo $response->to_json_string();
