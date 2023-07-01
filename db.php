<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "balance";

try {
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Verificar la conexión
    if (!$conn) {
        // Mensaje personalizado con descripción del error
        $mensajeError = "Error de conexión: " . mysqli_connect_error();

        return $mensajeError;
        exit(); // Termina la ejecución del script en caso de error
    } else {
        return "conexion exitosa";
    }
    // Cerrar conexión
    mysqli_close($conn);
} catch (Exception $e) {
    // Mansaje de error
    echo "Ha Ocurrido un Error: " . $e->getMessage();
}
