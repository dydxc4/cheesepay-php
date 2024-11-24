<?php

require __DIR__ . '/../dtos/found_student.php';
require __DIR__ . '/../dtos/found_tutor.php';
require __DIR__ . '/query_response.php';
require __DIR__ . '/../functions/mysql_connection.php';
require __DIR__ . '/../functions/helpers.php';

// establece el tipo de respuesta como archivo JSON
header('Content-Type: text/json');
// declara una variable para almacenar la respuesta
$response = null;

// verifica que el método de la petición sea GET y si los parámetros de tipo y
// término de búsqueda están definidos y si no son  nulos
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['type'], $_GET['q'])) {
    // obtiene el tipo de búsqueda
    $type = $_GET['type'];
    // verifica si el tipo de búsqueda corresponde a alumno o tutor
    if ($type === 'student' || $type === 'tutor') {
        // obtiene y limpia la cadena que contiene el término de búsqueda
        $term = satinize($_GET['q']);
        // declara una variable para almacenar la lista de elementos encontrados
        $results = null;
    
        switch ($_GET['type']) {
            case 'student':
                $results = search_students($term);
                break;
            case 'tutor':
                $results = search_tutors($term);
                break;
            default:
                break;
        }
    
        // crea un objeto de respuesta según sea el caso
        $response = $results !== null ? 
            new QueryResponse(QueryResponse::OK, $results) :
            QueryResponse::create_error('Error during query');
    } else {
        // crea un objeto de respuesta en caso de recibirse un tipo de búsqueda
        // no válido
        $response = QueryResponse::create_error('Invalid specified type');
    }
} else {
    // crea un objeto de respuesta en caso de recibirse una petición erronea
    $response = QueryResponse::create_error('Malformed request');
}

// despliega el objeto de respuesta en formato JSON
echo $response->to_json_string();

/* Funciones */

/**
 * Realiza la búsqueda de alumnos
 * @param string $term Término de búsqueda
 * @return array
 */
function search_students(string $term): array {
    // crea un arreglo vacío
    $list = [];
    // abre una nueva conexión con la base de datos
    $conn = MySqlConnection::open_connection();
    // prepara la sentencia a ejecutar
    $stmt = $conn->prepare('CALL sp_buscar_alumnos(?)');
    // enlaza los parámetros de entrada
    $stmt->bind_param('s', $term);

    // ejecuta el procedimiento verificando que se lleve a cabo exitosamente
    if ($stmt->execute()) {
        // procesa múltiples conjuntos de resultados
        do {
            // verifica si se obtuvo un conjunto de resultados
            if ($result = $stmt->get_result()) {
                // recorre cada fila del conjunto mientras se pueda obtener un 
                // arreglo con sus campos
                while ($row = $result->fetch_assoc()) {
                    // agrega un nuevo objeto en el arreglo
                    array_push(
                        $list, 
                        new FoundStudent(
                            $row['matricula'],
                            $row['nombre_completo'],
                            $row['curp'],
                            $row['estado_inscripcion'],
                            $row['nivel_educativo'],
                            $row['grupo']
                        )
                    );
                }

                // libera los recursos asociados al resultado obtenido
                $result->free();
            }
            // avanza al siguiente conjunto (si es que hay)
        } while ($stmt->more_results() && $stmt->next_result());
    }

    // libera los recursos y cierra la conexión
    $stmt->close();
    $conn->close();

    return $list;
}

/**
 * Realiza la búsqueda de tutores.
 * @param string $term Término de búsqueda
 * @return array
 */
function search_tutors(string $term): array {
    // crea un arreglo vacío
    $list = [];
    // abre una nueva conexión con la base de datos
    $conn = MySqlConnection::open_connection();
    // prepara la sentencia a ejecutar
    $stmt = $conn->prepare('CALL sp_buscar_tutores(?)');
    // enlaza los parámetros de entrada
    $stmt->bind_param('s', $term);

    // ejecuta el procedimiento verificando que se lleve a cabo exitosamente
    if ($stmt->execute()) {
        // procesa múltiples conjuntos de resultados
        do {
            // verifica si se obtuvo un conjunto de resultados
            if ($result = $stmt->get_result()) {
                // recorre cada fila del conjunto mientras se pueda obtener un 
                // arreglo con sus campos
                while ($row = $result->fetch_assoc()) {
                    // agrega un nuevo objeto en el arreglo
                    array_push(
                        $list, 
                        new FoundTutor(
                            $row['numero'],
                            $row['nombre_completo'],
                            $row['rfc'],
                            $row['email'],
                            $row['telefono']
                        )
                    );
                }

                // libera los recursos asociados al resultado obtenido
                $result->free();
            }
            // avanza al siguiente conjunto (si es que hay)
        } while ($stmt->more_results() && $stmt->next_result());
    }

    // libera los recursos y cierra la conexión
    $stmt->close();
    $conn->close();

    return $list;
}
