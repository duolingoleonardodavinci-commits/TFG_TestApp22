<?php
    session_start();
    
    // Borramos el orden de las preguntas al volver al inicio
    unset($_SESSION['preguntas_mezcladas']);
    unset($_SESSION['orden_internos']);
    
    // Temporal ----------------------
        $_SESSION['Nombre'] = 'Pepe';
        $_SESSION['Apellidos'] = 'Malho';
        $_SESSION['Ciclo'] = 'DAM';
    // -------------------------------
    include 'funciones/funciones.php';
    include 'funciones/fun-index.php';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TestsApp - Panel de Control</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="container" style="max-width: 800px;"> 
            
            <input id="ciclo" type="hidden" value="<?php echo $_SESSION['Ciclo']; ?>">

            <h1>TestsApp</h1>
            <p class="subtitle">Panel de Gestión y Evaluación</p>

            <div class="nav-menu">
                <button class="nav-btn" onclick="showSection('sec-crear-pregunta', this)">Preguntas</button>
                <button class="nav-btn" onclick="showSection('sec-crear-test', this)">Tests</button>
                <button class="nav-btn active" onclick="showSection('sec-probar-test', this)">Probar Test</button>
            </div>

            <div id="sec-crear-pregunta" class="section-content">
                <h2>Gestor de Preguntas</h2>
                
                <div style="background-color: #FAFAFA; padding: 20px; border-radius: 8px; border: 1px solid var(--border);">
                    <div class="form-group">
                        <label style="font-weight: 600;">Selecciona el Módulo:</label>
                        <select id="gp_select_modulo" required>
                            <option value="">Selecciona un módulo...</option>
                            <?php
                                try { desplegableModulos($_SESSION['Ciclo']); }
                                catch(PDOException $e) { echo error($e); }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600;">Buscar y Editar Pregunta Existente:</label>
                        <input type="text" id="gp_buscar_pregunta" placeholder="🔍 Escribe para filtrar preguntas..." class="input-text" style="margin-bottom: 8px; width: 100%;" disabled>
                        
                        <div style="display: flex; gap: 10px;">
                            <select id="gp_select_pregunta" size="6" style="margin: 0; flex-grow: 1; height: 140px; padding: 8px; border: 1px solid var(--border); border-radius: 6px;" disabled>
                                <option value="" disabled>Primero selecciona un módulo superior</option>
                            </select>
                            
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <a id="gp_btn_editar" href="#" style="display: flex; align-items: center; justify-content: center; padding: 10px 15px; background-color: var(--primary); color: white; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; opacity: 0.4; pointer-events: none;">✏️ Editar</a>
                                <a id="gp_btn_eliminar" href="#" onclick="return confirm('¿Seguro que quieres eliminar esta pregunta de la base de datos? Esto la borrará de todos los tests que la contengan.')" style="display: flex; align-items: center; justify-content: center; padding: 10px 15px; background-color: #EF4444; color: white; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; opacity: 0.4; pointer-events: none;">🗑️ Borrar</a>
                            </div>
                        </div>
                    </div>
                    
                    <div style="text-align: center; margin: 15px 0; color: var(--text-muted); font-weight: bold;">O</div>

                    <div class="form-group" style="text-align: center;">
                        <a id="gp_btn_crear" href="#" style="display: inline-flex; align-items: center; padding: 12px 24px; background-color: #10B981; color: white; border-radius: 6px; text-decoration: none; font-size: 1rem; font-weight: 600; opacity: 0.4; pointer-events: none;">➕ Crear Nueva Pregunta</a>
                    </div>
                </div>
            </div>

            <div id="sec-crear-test" class="section-content">
                <h2>Gestor de Tests</h2>
                <form action="gestionar_test.php" method="POST" style="background-color: #FAFAFA; padding: 20px; border-radius: 8px; border: 1px solid var(--border);">
                    <div class="form-group">
                        <label style="font-weight: 600;">Selecciona el Módulo:</label>
                        <select id="ct_select_modulo" name="modulo" required>
                            <option value="">Selecciona un módulo...</option>
                            <?php try { desplegableModulos($_SESSION['Ciclo']); } catch(PDOException $e) { echo error($e); } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600;">Editar o Eliminar Test Existente:</label>
                        <div style="display: flex; gap: 10px;">
                            <select id="ct_select_test" name="test_existente" disabled style="margin: 0; flex-grow: 1;">
                                <option value="">Primero selecciona un módulo</option>
                            </select>
                            <a id="gt_btn_editar" href="#" style="display: flex; align-items: center; padding: 0 15px; background-color: var(--primary); color: white; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; opacity: 0.4; pointer-events: none;">✏️</a>
                            <a id="gt_btn_eliminar" href="#" onclick="return confirm('¿Seguro que quieres eliminar este test?')" style="display: flex; align-items: center; padding: 0 15px; background-color: #EF4444; color: white; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; opacity: 0.4; pointer-events: none;">🗑️</a>
                        </div>
                    </div>
                    
                    <div style="text-align: center; margin: 15px 0; color: var(--text-muted); font-weight: bold;">O</div>

                    <div class="form-group">
                        <label style="font-weight: 600;">Crear Nuevo Test:</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" name="nuevo_test" id="nuevo_test_input" placeholder="Nombre del nuevo test" class="input-text" style="margin: 0; flex-grow: 1; width: auto;">
                            <a id="gt_btn_crear" href="#" style="display: flex; align-items: center; padding: 0 18px; background-color: #10B981; color: white; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; opacity: 0.4; pointer-events: none; white-space: nowrap;">➕ Crear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div id="sec-probar-test" class="section-content active">
                <h2>Realizar un Test</h2>
                <form action="test.php" method="POST">
                    <div class="form-group">
                        <select id="select_modulo" name="modulo" required>
                            <option value="">Selecciona un módulo</option>
                            <?php try { desplegableModulos($_SESSION['Ciclo']); } catch(PDOException $e) { echo error($e); } ?>
                        </select>
                        <select id="select_test" name="test" disabled required>
                            <option value="">Primero selecciona un módulo</option>
                        </select>
                    </div>
                    <input type="submit" value="Comenzar Test" name="iniciar_test" class="btn">
                </form>
            </div>
        </div>

        <script>
            // --- GESTIÓN DE PESTAÑAS ---
            function showSection(sectionId, btnElement) {
                const sections = document.querySelectorAll('.section-content');
                sections.forEach(sec => sec.classList.remove('active'));
                const buttons = document.querySelectorAll('.nav-btn');
                buttons.forEach(btn => btn.classList.remove('active'));
                document.getElementById(sectionId).classList.add('active');
                btnElement.classList.add('active');
            }

            document.addEventListener('DOMContentLoaded', function() {
                const inputCiclo = document.getElementById('ciclo');
                const ciclo = inputCiclo ? inputCiclo.value : 'DAM';

                // =========================================================
                // FUNCIÓN PARA SOLICITAR TESTS (Reutilizada)
                // =========================================================
                function solicitarTests(moduloSeleccionado, idSelectDestino) {
                    const selectTest = document.getElementById(idSelectDestino);
                    if (!selectTest) return;

                    if (moduloSeleccionado === "") {
                        selectTest.innerHTML = '<option value="">Primero selecciona un módulo</option>';
                        selectTest.disabled = true;
                        return;
                    }

                    fetch(`obtener_tests.php?ciclo=${encodeURIComponent(ciclo)}&modulo=${encodeURIComponent(moduloSeleccionado)}`)
                        .then(r => r.json())
                        .then(datos => {
                            selectTest.innerHTML = ''; 
                            if (!datos.error) {
                                if (datos === 'vacio') {
                                    selectTest.innerHTML = '<option value="">No hay tests disponibles</option>';
                                    selectTest.disabled = true;
                                } else {
                                    selectTest.innerHTML = '<option value="">Selecciona un test existente...</option>';
                                    datos.forEach(test => {
                                        const opcion = document.createElement('option');
                                        opcion.value = test.id_test;
                                        opcion.textContent = test.nombre;
                                        selectTest.appendChild(opcion);
                                    });
                                    selectTest.disabled = false;
                                }
                            }
                        }).catch(e => console.error("Error Fetch Tests:", e));
                }

                // =========================================================
                // FUNCIÓN PARA SOLICITAR PREGUNTAS (NUEVO)
                // =========================================================
                function solicitarPreguntas(moduloSeleccionado) {
                    const selectPregunta = document.getElementById('gp_select_pregunta');
                    const inputBuscar = document.getElementById('gp_buscar_pregunta');
                    const btnCrear = document.getElementById('gp_btn_crear');

                    // Reset botones edición
                    document.getElementById('gp_btn_editar').style.pointerEvents = 'none';
                    document.getElementById('gp_btn_editar').style.opacity = '0.4';
                    document.getElementById('gp_btn_eliminar').style.pointerEvents = 'none';
                    document.getElementById('gp_btn_eliminar').style.opacity = '0.4';

                    if (moduloSeleccionado === "") {
                        selectPregunta.innerHTML = '<option value="" disabled>Primero selecciona un módulo</option>';
                        selectPregunta.disabled = true;
                        inputBuscar.disabled = true;
                        inputBuscar.value = '';
                        btnCrear.style.pointerEvents = 'none';
                        btnCrear.style.opacity = '0.4';
                        return;
                    }

                    // Activamos botón de crear (le pasamos el módulo por URL)
                    btnCrear.href = `gestionar_pregunta.php?accion=nuevo&modulo=${encodeURIComponent(moduloSeleccionado)}`;
                    btnCrear.style.pointerEvents = 'auto';
                    btnCrear.style.opacity = '1';

                    fetch(`obtener_preguntas.php?ciclo=${encodeURIComponent(ciclo)}&modulo=${encodeURIComponent(moduloSeleccionado)}`)
                        .then(r => r.json())
                        .then(datos => {
                            selectPregunta.innerHTML = '';
                            if (!datos.error) {
                                if (datos === 'vacio') {
                                    selectPregunta.innerHTML = '<option value="" disabled>No hay preguntas creadas</option>';
                                    selectPregunta.disabled = true;
                                    inputBuscar.disabled = true;
                                } else {
                                    // Rellenamos la lista
                                    datos.forEach(p => {
                                        const op = document.createElement('option');
                                        op.value = p.id_pregunta;
                                        // Mostramos el tipo [TEST] o [TF] seguido del título
                                        op.textContent = `[${p.tipo}] ${p.titulo}`; 
                                        selectPregunta.appendChild(op);
                                    });
                                    selectPregunta.disabled = false;
                                    inputBuscar.disabled = false;
                                    inputBuscar.value = ''; // Limpiar buscador
                                }
                            }
                        }).catch(e => console.error("Error Fetch Preguntas:", e));
                }

                // =========================================================
                // DELEGACIÓN DE EVENTOS PRINCIPAL
                // =========================================================
                document.addEventListener('change', function(event) {
                    
                    // --- GESTIÓN DE PREGUNTAS ---
                    if (event.target.id === 'gp_select_modulo') {
                        solicitarPreguntas(event.target.value);
                    }

                    if (event.target.id === 'gp_select_pregunta') {
                        const idPregunta = event.target.value;
                        const btnEditar = document.getElementById('gp_btn_editar');
                        const btnEliminar = document.getElementById('gp_btn_eliminar');

                        if (idPregunta) {
                            btnEditar.href = `gestionar_pregunta.php?accion=editar&id_pregunta=${idPregunta}`;
                            btnEliminar.href = `gestionar_pregunta.php?accion=eliminar&id_pregunta=${idPregunta}`;
                            [btnEditar, btnEliminar].forEach(b => {
                                b.style.pointerEvents = 'auto';
                                b.style.opacity = '1';
                            });
                        }
                    }

                    // --- GESTIÓN DE TESTS ---
                    if (event.target.id === 'ct_select_test') {
                        const btnEditar = document.getElementById('gt_btn_editar');
                        const btnEliminar = document.getElementById('gt_btn_eliminar');
                        const idTest = event.target.value;
                        if (idTest !== "") {
                            btnEditar.href = `gestionar_test.php?accion=editar&id_test=${idTest}`;
                            btnEliminar.href = `gestionar_test.php?accion=eliminar&id_test=${idTest}`;
                            [btnEditar, btnEliminar].forEach(b => {
                                b.style.pointerEvents = 'auto';
                                b.style.opacity = '1';
                            });
                        } else {
                            [btnEditar, btnEliminar].forEach(b => {
                                b.style.pointerEvents = 'none';
                                b.style.opacity = '0.4';
                            });
                        }
                    }
                    
                    // --- SELECTORES DE MÓDULO PARA TESTS ---
                    if (event.target.id === 'select_modulo') solicitarTests(event.target.value, 'select_test');
                    if (event.target.id === 'ct_select_modulo') solicitarTests(event.target.value, 'ct_select_test');
                });

                // =========================================================
                // EVENTOS DE ESCRITURA (Inputs)
                // =========================================================
                
                // 1. Buscador de Preguntas (Filtro en tiempo real)
                document.getElementById('gp_buscar_pregunta').addEventListener('input', function(e) {
                    const filtro = e.target.value.toLowerCase();
                    const opciones = document.querySelectorAll('#gp_select_pregunta option');
                    
                    opciones.forEach(opcion => {
                        if (opcion.disabled) return; // Ignorar la opción por defecto
                        const texto = opcion.textContent.toLowerCase();
                        // Oculta o muestra la opción según si coincide con la búsqueda
                        opcion.style.display = texto.includes(filtro) ? '' : 'none';
                    });
                });

                // 2. Input de Crear Nuevo Test
                document.getElementById('nuevo_test_input').addEventListener('input', function() {
                    const btnCrear = document.getElementById('gt_btn_crear');
                    const modulo = document.getElementById('ct_select_modulo').value;
                    const nombre = this.value.trim();

                    if (nombre !== "" && modulo !== "") {
                        btnCrear.href = `gestionar_test.php?accion=nuevo&modulo=${encodeURIComponent(modulo)}&nombre=${encodeURIComponent(nombre)}`;
                        btnCrear.style.pointerEvents = 'auto';
                        btnCrear.style.opacity = '1';
                    } else {
                        btnCrear.style.pointerEvents = 'none';
                        btnCrear.style.opacity = '0.4';
                    }
                });

            });
        </script>
    </body>
</html>