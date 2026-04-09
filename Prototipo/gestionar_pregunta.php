<?php
    session_start();
    include 'funciones/funciones.php';

    $es_post = $_SERVER["REQUEST_METHOD"] == "POST";
    
    try {
        $conn = conexionBD();

        // 1. ELIMINAR PREGUNTA (Por GET desde el index o POST)
        if (($es_post && isset($_POST['eliminar_pregunta'])) || (isset($_GET['accion']) && $_GET['accion'] === 'eliminar')) {
            $id_pregunta = $es_post ? $_POST['id_pregunta'] : $_GET['id_pregunta'];
            
            // Primero borrar de la tabla intermedia para evitar error de clave foránea
            $stmt = $conn->prepare("DELETE FROM preguntas_tests WHERE id_pregunta = :id_pregunta");
            $stmt->execute([':id_pregunta' => $id_pregunta]);
            
            $stmt = $conn->prepare("DELETE FROM preguntas WHERE id_pregunta = :id_pregunta");
            $stmt->execute([':id_pregunta' => $id_pregunta]);

            echo "<script>alert('🗑️ Pregunta eliminada correctamente.'); window.location.href = 'index.php';</script>";
            exit();
        }

        // 2. GUARDAR / ACTUALIZAR PREGUNTA
        if ($es_post && isset($_POST['guardar_pregunta'])) {
            $accion = $_POST['accion'];
            $ciclo = $_SESSION['Ciclo'];
            $modulo = test_input($_POST['modulo']);
            $tipo = test_input($_POST['tipo']);
            $enunciado = test_input($_POST['enunciado']);
            $contenido = [];

            if ($tipo === 'test') {
                $opciones = [];
                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'opcion_') === 0) {
                        $letra = str_replace('opcion_', '', $key);
                        $opciones[$letra] = test_input($value);
                    }
                }
                $contenido = [
                    "pregunta" => $enunciado,
                    "opciones" => $opciones,
                    "respuesta" => test_input($_POST['respuesta_test'])
                ];
            } elseif ($tipo === 'tf') {
                $contenido = [
                    "pregunta" => $enunciado,
                    "tipo" => "tf",
                    "respuesta" => test_input($_POST['respuesta_tf'])
                ];
            }

            $json_contenido = json_encode($contenido, JSON_UNESCAPED_UNICODE);

            if ($accion === 'nuevo') {
                $stmt = $conn->prepare("INSERT INTO preguntas (contenido, ciclo, modulo) VALUES (:contenido, :ciclo, :modulo)");
                $stmt->execute([':contenido' => $json_contenido, ':ciclo' => $ciclo, ':modulo' => $modulo]);
            } elseif ($accion === 'editar') {
                $id_pregunta = $_POST['id_pregunta'];
                $stmt = $conn->prepare("UPDATE preguntas SET contenido = :contenido WHERE id_pregunta = :id_pregunta");
                $stmt->execute([':contenido' => $json_contenido, ':id_pregunta' => $id_pregunta]);
            }

            echo "<script>alert('✅ Pregunta guardada con éxito.'); window.location.href = 'index.php';</script>";
            exit();
        }

        // 3. CARGAR DATOS PARA EL FORMULARIO
        $accion = $_GET['accion'] ?? '';
        $ciclo = $_SESSION['Ciclo'];
        
        $modulo = $_GET['modulo'] ?? '';
        $id_pregunta = '';
        $tipo_pregunta = 'test';
        $enunciado = '';
        $opciones_test = ['a' => '', 'b' => '', 'c' => ''];
        $respuesta_correcta = 'a';

        if ($accion === 'editar') {
            $id_pregunta = $_GET['id_pregunta'];
            $stmt = $conn->prepare("SELECT modulo, contenido FROM preguntas WHERE id_pregunta = :id_pregunta");
            $stmt->execute([':id_pregunta' => $id_pregunta]);
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $modulo = $datos['modulo'];
            $json_data = json_decode($datos['contenido'], true);
            
            $enunciado = $json_data['pregunta'] ?? $json_data['enunciado'] ?? '';
            $tipo_pregunta = $json_data['tipo'] ?? 'test';
            $respuesta_correcta = $json_data['respuesta'] ?? '';

            if ($tipo_pregunta === 'test' && isset($json_data['opciones'])) {
                $opciones_test = $json_data['opciones'];
            }
        }

    } catch (PDOException $e) { die(error($e)); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor de Preguntas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container" style="max-width: 800px;">
        <h2><?php echo $accion === 'nuevo' ? '✨ Nueva Pregunta' : '✏️ Editar Pregunta'; ?></h2>
        
        <form action="gestionar_pregunta.php" method="POST" style="background-color: #FAFAFA; padding: 20px; border-radius: 8px; border: 1px solid var(--border);">
            <input type="hidden" name="accion" value="<?php echo htmlspecialchars($accion); ?>">
            <input type="hidden" name="modulo" value="<?php echo htmlspecialchars($modulo); ?>">
            <?php if($accion === 'editar') echo '<input type="hidden" name="id_pregunta" value="'.htmlspecialchars($id_pregunta).'">'; ?>

            <div class="form-group">
                <label style="font-weight: 600;">Tipo de Pregunta:</label>
                <select id="tipo_pregunta" name="tipo" required onchange="cambiarFormularioPregunta()" <?php echo $accion === 'editar' ? 'style="pointer-events:none; background-color:#e9ecef;"' : ''; ?>>
                    <option value="test" <?php if($tipo_pregunta==='test') echo 'selected'; ?>>Tipo Test (Opciones a, b, c...)</option>
                    <option value="tf" <?php if($tipo_pregunta==='tf') echo 'selected'; ?>>Verdadero / Falso</option>
                </select>
                <?php if($accion === 'editar'): ?>
                    <small style="color:var(--text-muted);">* No se puede cambiar el tipo al editar. Crea una nueva si es necesario.</small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label style="font-weight: 600;">Enunciado de la pregunta:</label>
                <textarea name="enunciado" required class="input-text" style="width: 100%; height: 80px; resize: vertical;"><?php echo htmlspecialchars($enunciado); ?></textarea>
            </div>

            <div id="zona-test" class="form-group" style="<?php echo $tipo_pregunta === 'test' ? 'display:block;' : 'display:none;'; ?>">
                <label style="font-weight: 600;">Opciones (Mínimo 3):</label>
                <div id="contenedor-opciones" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 10px;">
                    </div>
                
                <button type="button" onclick="agregarOpcionTest()" style="padding: 6px 12px; font-size: 0.85rem; font-weight: 500; background-color: transparent; color: #4F46E5; border: 1px dashed #4F46E5; border-radius: 6px; cursor: pointer; margin-bottom: 15px; width: auto; align-self: flex-start; transition: all 0.2s ease;">
                    ➕ Añadir Opción
                </button>
                
                <br><br><label style="font-weight: 600;">Respuesta Correcta:</label>
                <select name="respuesta_test" id="select_respuesta_test" required></select>
            </div>

            <div id="zona-tf" class="form-group" style="<?php echo $tipo_pregunta === 'tf' ? 'display:block;' : 'display:none;'; ?>">
                <label style="font-weight: 600;">Respuesta Correcta:</label>
                <select name="respuesta_tf" id="select_respuesta_tf">
                    <option value="True" <?php if($respuesta_correcta==='True') echo 'selected'; ?>>Verdadero (True)</option>
                    <option value="False" <?php if($respuesta_correcta==='False') echo 'selected'; ?>>Falso (False)</option>
                </select>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <a href="index.php" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancelar</a>
                <button type="submit" name="guardar_pregunta" class="btn" style="flex: 2;">💾 Guardar Pregunta</button>
            </div>
        </form>
    </div>

    <script>
        // Inyectamos las opciones existentes desde PHP al array de JS
        let opcionesCargadas = <?php echo json_encode($opciones_test); ?>;
        let respuestaCorrectaCargada = '<?php echo $respuesta_correcta; ?>';

        document.addEventListener('DOMContentLoaded', function() {
            // Generar las opciones iniciales
            Object.keys(opcionesCargadas).forEach(letra => {
                agregarOpcionTest(letra, opcionesCargadas[letra]);
            });
            
            // Forzar la selección de la respuesta correcta en el select
            if (document.getElementById('select_respuesta_test')) {
                document.getElementById('select_respuesta_test').value = respuestaCorrectaCargada;
            }
            cambiarFormularioPregunta();
        });

        function agregarOpcionTest(letraForzada = null, valorForzado = '') {
            const contenedor = document.getElementById('contenedor-opciones');
            const divOpcion = document.createElement('div');
            divOpcion.className = 'opcion-item';
            divOpcion.style.display = 'flex';
            divOpcion.style.gap = '8px';
            divOpcion.style.alignItems = 'center';

            // NUEVO: Etiqueta para mostrar la letra indicadora (a, b, c...)
            const spanLetra = document.createElement('span');
            spanLetra.className = 'letra-indicador';
            spanLetra.style.fontWeight = 'bold';
            spanLetra.style.color = 'var(--text-main)';
            spanLetra.style.minWidth = '20px'; // Asegura que todas midan igual y se alineen bien

            const nuevoInput = document.createElement('input');
            nuevoInput.type = 'text';
            nuevoInput.className = 'input-text';
            nuevoInput.style.flexGrow = '1';
            nuevoInput.value = valorForzado;
            nuevoInput.required = true;

            // BOTÓN ELIMINAR MODIFICADO
            const btnEliminar = document.createElement('button');
            btnEliminar.type = 'button';
            btnEliminar.innerHTML = '&times;';
            btnEliminar.style.width = '24px';
            btnEliminar.style.height = '24px';
            btnEliminar.style.padding = '0';
            btnEliminar.style.fontSize = '1.2rem';
            btnEliminar.style.lineHeight = '22px';
            btnEliminar.style.backgroundColor = 'transparent';
            btnEliminar.style.color = '#EF4444';
            btnEliminar.style.border = 'none';
            btnEliminar.style.borderRadius = '50%';
            btnEliminar.style.fontWeight = 'bold';
            btnEliminar.style.cursor = 'pointer';
            btnEliminar.style.transition = 'all 0.2s ease';
            btnEliminar.style.opacity = '0.6';

            // Efecto hover sutil
            btnEliminar.onmouseover = function() { 
                if (!this.disabled) {
                    this.style.backgroundColor = '#FEE2E2'; 
                    this.style.opacity = '1'; 
                }
            };
            btnEliminar.onmouseout = function() { 
                if (!this.disabled) {
                    this.style.backgroundColor = 'transparent'; 
                    this.style.opacity = '0.6'; 
                }
            };

            btnEliminar.onclick = function() { eliminarOpcion(this); };

            // Añadimos el spanLetra primero para que salga a la izquierda del input
            divOpcion.appendChild(spanLetra);
            divOpcion.appendChild(nuevoInput);
            divOpcion.appendChild(btnEliminar);
            contenedor.appendChild(divOpcion);
            
            renumerarOpciones();
        }

        function eliminarOpcion(btn) {
            btn.parentElement.remove();
            renumerarOpciones();
        }

        function renumerarOpciones() {
    const contenedor = document.getElementById('contenedor-opciones');
    const items = contenedor.querySelectorAll('.opcion-item');
    const selectRespuesta = document.getElementById('select_respuesta_test');
    
    // Validamos si respuestaCorrectaCargada existe (dependiendo de en qué archivo estés)
    const respuestaPrevia = selectRespuesta.value || (typeof respuestaCorrectaCargada !== 'undefined' ? respuestaCorrectaCargada : '');
    selectRespuesta.innerHTML = ''; 

    items.forEach((item, index) => {
        const letra = String.fromCharCode(97 + index); // 97 es la 'a' en ASCII
        const input = item.querySelector('input');
        const btnBorrar = item.querySelector('button');
        const spanLetra = item.querySelector('.letra-indicador'); // Buscamos nuestro span
        
        // Actualizamos el texto del span visible
        if (spanLetra) {
            spanLetra.textContent = letra + ')'; 
        }

        input.name = 'opcion_' + letra;
        input.placeholder = 'Escribe la opción...'; // Quitamos la letra del placeholder porque ya está fuera

        // Gestión de la opacidad cuando está deshabilitado
        if (items.length <= 3) {
            btnBorrar.disabled = true;
            btnBorrar.style.opacity = '0.2';
            btnBorrar.style.cursor = 'not-allowed';
            btnBorrar.style.backgroundColor = 'transparent';
        } else {
            btnBorrar.disabled = false;
            btnBorrar.style.opacity = '0.6';
            btnBorrar.style.cursor = 'pointer';
        }

        const nuevaOpcion = document.createElement('option');
        nuevaOpcion.value = letra;
        nuevaOpcion.textContent = 'Opción ' + letra.toUpperCase();
        selectRespuesta.appendChild(nuevaOpcion);
    });

    if (respuestaPrevia) selectRespuesta.value = respuestaPrevia;
}

        function cambiarFormularioPregunta() {
            const tipo = document.getElementById('tipo_pregunta').value;
            const zonaTest = document.getElementById('zona-test');
            const zonaTf = document.getElementById('zona-tf');
            const inputsTest = document.querySelectorAll('#contenedor-opciones input');
            const selectTest = document.getElementById('select_respuesta_test');
            const selectTf = document.getElementById('select_respuesta_tf');

            if (tipo === 'test') {
                zonaTest.style.display = 'block';
                zonaTf.style.display = 'none';
                inputsTest.forEach(input => input.required = true);
                if(selectTest) selectTest.required = true;
                if(selectTf) selectTf.required = false;
            } else {
                zonaTest.style.display = 'none';
                zonaTf.style.display = 'block';
                inputsTest.forEach(input => input.required = false);
                if(selectTest) selectTest.required = false;
                if(selectTf) selectTf.required = true;
            }
        }
    </script>
</body>
</html>