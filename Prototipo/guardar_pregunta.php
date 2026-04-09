<?php
    session_start();
    include 'funciones/funciones.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_pregunta'])) {
        try {
            $conn = conexionBD();
            
            // 1. Recogemos los datos básicos comunes a todas las preguntas
            $ciclo = $_SESSION['Ciclo'];
            $modulo = test_input($_POST['modulo']);
            $tipo = test_input($_POST['tipo']);
            $enunciado = test_input($_POST['enunciado']);

            $contenido = []; // Aquí construiremos nuestro array antes de pasarlo a JSON

            // 2. Lógica para construir el JSON según el tipo de pregunta
            if ($tipo === 'test') {
                $opciones = [];
                
                // Recorremos todo lo que ha llegado por POST buscando las opciones dinámicas
                foreach ($_POST as $key => $value) {
                    // Si el nombre del campo empieza por "opcion_" (ej: opcion_a, opcion_d...)
                    if (strpos($key, 'opcion_') === 0) {
                        // Extraemos solo la letra (quitamos "opcion_")
                        $letra = str_replace('opcion_', '', $key);
                        // Limpiamos el texto y lo metemos en nuestro array de opciones
                        $opciones[$letra] = test_input($value);
                    }
                }

                $contenido = [
                    "pregunta" => $enunciado,
                    "opciones" => $opciones, // Metemos el array de tamaño dinámico
                    "respuesta" => test_input($_POST['respuesta_test'])
                ];
                
            } elseif ($tipo === 'tf') {
                $contenido = [
                    "pregunta" => $enunciado,
                    "tipo" => "tf",
                    "respuesta" => test_input($_POST['respuesta_tf'])
                ];
            }

            // 3. Convertimos el array a un string JSON 
            // JSON_UNESCAPED_UNICODE es crucial para que las tildes y eñes se guarden bien y no como \u00e1
            $json_contenido = json_encode($contenido, JSON_UNESCAPED_UNICODE);

            // 4. Insertamos en la Base de Datos usando consultas preparadas para mayor seguridad
            $stmt = $conn->prepare("INSERT INTO preguntas (contenido, ciclo, modulo) VALUES (:contenido, :ciclo, :modulo)");
            $stmt->bindParam(':contenido', $json_contenido);
            $stmt->bindParam(':ciclo', $ciclo);
            $stmt->bindParam(':modulo', $modulo);
            $stmt->execute();

            // 5. Volvemos al index con un mensaje de éxito
            echo "<script>
                    alert('✅ Pregunta guardada con éxito en la base de datos.');
                    window.location.href = 'index.php';
                  </script>";

        } catch(PDOException $e) {
            echo "<div style='padding:20px; color:red; font-family:sans-serif;'>";
            echo "<h2>Error al guardar la pregunta</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "<a href='index.php'>Volver al inicio</a>";
            echo "</div>";
            
        } finally {
            if ($conn !== null) { $conn = null; }
        }
    } else {
        // Si alguien intenta entrar a este archivo directamente por la URL, lo echamos al index
        header("Location: index.php");
        exit();
    }
?>