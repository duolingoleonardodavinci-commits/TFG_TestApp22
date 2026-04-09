<?php
    // obtener_preguntas.php
    include 'funciones/funciones.php';

    header('Content-Type: application/json; charset=utf-8');

    $ciclo = $_GET['ciclo'] ?? '';
    $modulo = $_GET['modulo'] ?? '';

    try {
        $conn = conexionBD();
        
        // Buscamos las preguntas de ese módulo
        $stmt = $conn->prepare("SELECT id_pregunta, contenido FROM preguntas WHERE ciclo = :ciclo AND modulo = :modulo");
        $stmt->execute([':ciclo' => $ciclo, ':modulo' => $modulo]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($resultados)) {
            echo json_encode('vacio');
        } else {
            $preguntas_formateadas = [];
            
            // Recorremos cada pregunta para "desempaquetar" el JSON de la columna 'contenido'
            foreach ($resultados as $row) {
                $contenido = json_decode($row['contenido'], true);
                
                // 1. Intentamos sacar el título (puede venir como 'enunciado' o 'pregunta')
                $titulo = $contenido['enunciado'] ?? $contenido['pregunta'] ?? 'Pregunta sin título';
                
                // Por si acaso es un formato antiguo donde la pregunta era un array
                if (is_array($titulo)) {
                    $titulo = ($titulo['cadena1'] ?? '') . ' [...] ' . ($titulo['cadena2'] ?? '');
                }

                // 2. Intentamos sacar el tipo (si no tiene, por defecto en tu BD es 'test')
                $tipo = $contenido['tipo'] ?? 'test';

                // Guardamos los datos limpios en un nuevo array
                $preguntas_formateadas[] = [
                    'id_pregunta' => $row['id_pregunta'],
                    'tipo' => strtoupper($tipo), // Lo ponemos en mayúsculas para que quede [TEST] o [TF]
                    'titulo' => $titulo
                ];
            }
            
            // Enviamos el array limpio al JavaScript
            echo json_encode($preguntas_formateadas, JSON_UNESCAPED_UNICODE);
        }

    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);

    } finally {
        $conn = null;
    }
?>