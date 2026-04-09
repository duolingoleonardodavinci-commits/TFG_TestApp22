<?php
    // obtener_historial.php
    session_start();
    include 'funciones/funciones.php';

    header('Content-Type: application/json; charset=utf-8');

    $modulo = $_GET['modulo'] ?? '';
    // Rescatamos el ID del alumno de la sesión (por ahora 'al01@edu.es' que definimos temporalmente)
    $id_alumno = $_SESSION['id_alumno'] ?? ''; 

    if (empty($id_alumno)) {
        echo json_encode(["error" => "No se ha identificado al alumno en la sesión."]);
        exit;
    }

    try {
        $conn = conexionBD();
        
        // Hacemos un JOIN entre puntuacion y tests para traernos el "nombre" del test, la fecha y la puntuación
        $sql = "SELECT t.nombre, p.fecha, p.puntuacion 
                FROM puntuacion p
                INNER JOIN tests t ON p.id_test = t.id_test
                WHERE t.modulo = :modulo AND p.id_alumno = :id_alumno
                ORDER BY p.fecha DESC"; // Los ordenamos para que los más recientes salgan primero
                
        $stmt = $conn->prepare($sql);
        $stmt->execute([':modulo' => $modulo, ':id_alumno' => $id_alumno]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($resultados)) {
            echo json_encode('vacio');
        } else {
            echo json_encode($resultados);
        }

    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);

    } finally {
        $conn = null;
    }
?>