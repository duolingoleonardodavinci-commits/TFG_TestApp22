<?php
    session_start();
    include 'funciones/funciones.php';

    // Verificamos si estamos entrando para cargar el formulario (GET) o para guardar/eliminar datos
    $es_post = $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_test']);
    $es_eliminar = (isset($_POST['eliminar_test']) || (isset($_GET['accion']) && $_GET['accion'] === 'eliminar'));
    
    try {
        $conn = conexionBD();

        // ====================================================================
        // LÓGICA 1.1: ELIMINAR EL TEST (Desde index.php o desde el botón editar)
        // ====================================================================
        if ($es_eliminar) {
            $id_test_eliminar = isset($_POST['id_test']) ? $_POST['id_test'] : $_GET['id_test'];

            // 1. Borramos las relaciones en preguntas_tests
            $stmt = $conn->prepare("DELETE FROM preguntas_tests WHERE id_test = :id_test");
            $stmt->execute([':id_test' => $id_test_eliminar]);

            // 2. Borramos las notas de los alumnos (puntuacion) para no violar la Foreign Key
            $stmt = $conn->prepare("DELETE FROM puntuacion WHERE id_test = :id_test");
            $stmt->execute([':id_test' => $id_test_eliminar]);

            // 3. Borramos el test finalmente
            $stmt = $conn->prepare("DELETE FROM tests WHERE id_test = :id_test");
            $stmt->execute([':id_test' => $id_test_eliminar]);

            echo "<script>
                    alert('🗑️ Test y datos asociados eliminados correctamente.');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        }

        // ====================================================================
        // LÓGICA 1.2: GUARDAR EN LA BASE DE DATOS (Cuando enviamos el formulario)
        // ====================================================================
        if ($es_post) {
            $accion = $_POST['accion'];
            $ciclo = $_SESSION['Ciclo'];
            $modulo = test_input($_POST['modulo']);
            $nombre_test = test_input($_POST['nombre_test']);
            $descripcion = test_input($_POST['descripcion']); // Capturamos la descripción
            $preguntas_seleccionadas = isset($_POST['preguntas']) ? $_POST['preguntas'] : [];

            if ($accion === 'nuevo') {
                $stmt = $conn->prepare("INSERT INTO tests (nombre, descripcion, modulo, ciclo) VALUES (:nombre, :descripcion, :modulo, :ciclo)");
                $stmt->execute([':nombre' => $nombre_test, ':descripcion' => $descripcion, ':modulo' => $modulo, ':ciclo' => $ciclo]);
                $id_test = $conn->lastInsertId();
                
            } elseif ($accion === 'editar') {
                $id_test = $_POST['id_test'];
                $stmt = $conn->prepare("UPDATE tests SET nombre = :nombre, descripcion = :descripcion WHERE id_test = :id_test");
                $stmt->execute([':nombre' => $nombre_test, ':descripcion' => $descripcion, ':id_test' => $id_test]);
                
                $stmt = $conn->prepare("DELETE FROM preguntas_tests WHERE id_test = :id_test");
                $stmt->execute([':id_test' => $id_test]);
            }

            if (!empty($preguntas_seleccionadas)) {
                $stmt_preg = $conn->prepare("INSERT INTO preguntas_tests (id_test, id_pregunta) VALUES (:id_test, :id_pregunta)");
                foreach ($preguntas_seleccionadas as $id_preg) {
                    $stmt_preg->execute([':id_test' => $id_test, ':id_pregunta' => $id_preg]);
                }
            }

            echo "<script>
                    alert('✅ Test guardado correctamente con " . count($preguntas_seleccionadas) . " preguntas.');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        }

        // ====================================================================
        // LÓGICA 2: CARGAR EL PANEL (Cuando venimos de index.php)
        // ====================================================================
        $accion = isset($_GET['accion']) ? $_GET['accion'] : '';
        $ciclo = $_SESSION['Ciclo'];
        
        $modulo = '';
        $nombre_test = '';
        $descripcion = ''; // Nueva variable
        $id_test = '';
        $preguntas_asignadas = [];

        if ($accion === 'nuevo') {
            $modulo = $_GET['modulo'];
            $nombre_test = $_GET['nombre'];
            
        } elseif ($accion === 'editar') {
            $id_test = $_GET['id_test'];
            
            $stmt = $conn->prepare("SELECT nombre, descripcion, modulo FROM tests WHERE id_test = :id_test");
            $stmt->execute([':id_test' => $id_test]);
            $datos_test = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $nombre_test = $datos_test['nombre'];
            $descripcion = $datos_test['descripcion']; // Cargamos la descripción de la BD
            $modulo = $datos_test['modulo'];

            $stmt_preg = $conn->prepare("SELECT id_pregunta FROM preguntas_tests WHERE id_test = :id_test");
            $stmt_preg->execute([':id_test' => $id_test]);
            $preguntas_asignadas = $stmt_preg->fetchAll(PDO::FETCH_COLUMN);
        } else {
            die("Acción no válida. <a href='index.php'>Volver</a>");
        }

        $stmt_todas = $conn->prepare("SELECT id_pregunta, contenido FROM preguntas WHERE modulo = :modulo AND ciclo = :ciclo");
        $stmt_todas->execute([':modulo' => $modulo, ':ciclo' => $ciclo]);
        $todas_las_preguntas = $stmt_todas->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die(error($e));
    } finally {
        $conn = null;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Tests</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .grid-preguntas { display: grid; grid-template-columns: 1fr; gap: 15px; margin-top: 15px; }
        .tarjeta-pregunta { background: white; border: 2px solid var(--border); border-radius: 8px; padding: 15px; cursor: pointer; transition: all 0.2s; display: flex; align-items: flex-start; gap: 15px; }
        .tarjeta-pregunta:hover { border-color: #A5B4FC; background-color: #EEF2FF; }
        .tarjeta-pregunta:has(input:checked) { border-color: var(--primary); background-color: #EEF2FF; box-shadow: 0 2px 4px rgba(79, 70, 229, 0.1); }
        .checkbox-custom { width: 20px; height: 20px; margin-top: 3px; accent-color: var(--primary); cursor: pointer; }
        .tipo-badge { display: inline-block; background: var(--text-main); color: white; font-size: 0.75rem; padding: 3px 8px; border-radius: 12px; margin-bottom: 8px; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="container" style="max-width: 900px;">
        <h2><?php echo $accion === 'nuevo' ? '✨ Creando Nuevo Test' : '✏️ Editando Test'; ?></h2>
        
        <form method="POST" action="gestionar_test.php">
            <input type="hidden" name="accion" value="<?php echo htmlspecialchars($accion); ?>">
            <input type="hidden" name="modulo" value="<?php echo htmlspecialchars($modulo); ?>">
            <?php if($accion === 'editar') echo '<input type="hidden" name="id_test" value="'.htmlspecialchars($id_test).'">'; ?>

            <div style="background-color: #FAFAFA; padding: 20px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 25px;">
                <div class="form-group">
                    <label style="font-weight: 600;">Nombre del Test:</label>
                    <input type="text" name="nombre_test" class="input-text" value="<?php echo htmlspecialchars($nombre_test); ?>" required>
                </div>
                
                <div class="form-group">
                    <label style="font-weight: 600;">Descripción del Test:</label>
                    <textarea name="descripcion" class="input-text" style="width: 100%; height: 70px; resize: vertical;" placeholder="Ej: Este test evaluará los conocimientos del Tema 1..."><?php echo htmlspecialchars($descripcion); ?></textarea>
                </div>

                <div style="color: var(--text-muted); font-size: 0.9rem;">
                    <strong>Módulo:</strong> <?php echo htmlspecialchars($modulo); ?>
                </div>
            </div>

            <h3>Asignar Preguntas al Test</h3>
            <p style="color: var(--text-muted);">Selecciona las preguntas que deseas incluir en este test.</p>

            <div class="grid-preguntas">
                <?php
                    if (empty($todas_las_preguntas)) {
                        echo "<div style='text-align:center; padding:30px; border:2px dashed var(--border); border-radius:8px;'>No hay preguntas creadas para este módulo todavía.</div>";
                    }

                    foreach ($todas_las_preguntas as $pregunta) {
                        $id_preg = $pregunta['id_pregunta'];
                        $contenido = json_decode($pregunta['contenido'], true);
                        
                        $titulo = $contenido['enunciado'] ?? $contenido['pregunta'] ?? 'Pregunta sin título';
                        if (is_array($titulo)) {
                            $titulo = $titulo['cadena1'] . ' [...] ' . $titulo['cadena2'];
                        }

                        $tipo_visual = $contenido['tipo'] ?? 'Test Normal';
                        $checked = in_array($id_preg, $preguntas_asignadas) ? 'checked' : '';

                        echo '<label class="tarjeta-pregunta">';
                        echo '<input type="checkbox" name="preguntas[]" value="'.$id_preg.'" class="checkbox-custom" '.$checked.'>';
                        echo '<div>';
                        echo '<div class="tipo-badge">' . htmlspecialchars($tipo_visual) . '</div>';
                        echo '<div style="font-weight: 500; font-size: 1.05rem;">' . htmlspecialchars($titulo) . '</div>';
                        echo '</div>';
                        echo '</label>';
                    }
                ?>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <a href="index.php" class="btn btn-secondary" style="flex: 1; text-align: center; margin-top: 0;">Cancelar</a>
                
                <?php if ($accion === 'editar'): ?>
                    <button type="submit" name="eliminar_test" class="btn" style="flex: 1; background-color: #EF4444;" onclick="return confirm('¿Estás seguro de que deseas eliminar este test? Perderás también las notas de los alumnos que lo hayan realizado.');">🗑️ Eliminar</button>
                <?php endif; ?>

                <button type="submit" name="guardar_test" class="btn" style="flex: 2;">💾 Guardar Test</button>
            </div>
        </form>
    </div>
</body>
</html>