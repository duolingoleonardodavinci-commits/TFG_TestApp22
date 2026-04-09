<?php
    function decode_tf($id_pregunta, $contenido, $estado = null)  {
        $disabled = $estado ? 'disabled' : '';
        
        foreach (['True', 'False'] as $val) {
            $class = 'option-label';
            $checked = '';
            
            if ($estado) {
                if ($estado['usuario'] === $val) $checked = 'checked';
                if ($estado['correcta'] === $val) {
                    $class .= ' correct-bg';
                } elseif ($estado['usuario'] === $val) {
                    $class .= ' incorrect-bg';
                }
            }
            
            echo '<label class="'.$class.'"><input type="radio" name="respuestas['.$id_pregunta.']" value="'.$val.'" '.$disabled.' '.$checked.' '.(!$estado?'required':'').'> '.$val.'</label>';
        }
    }

    function decode_conecta($id_pregunta, $contenido, $estado = null)  {
        $disabled = $estado ? 'disabled' : '';

        if (!$estado) {
            $keys1 = array_keys($contenido['div-1']);
            $keys2 = array_keys($contenido['div-2']);
            $claves_select = array_keys($contenido['div-2']);

            if (ACTIVAR_ALEATORIEDAD) {
                shuffle($keys1); 
                shuffle($keys2); 
                shuffle($claves_select);
            }

            $_SESSION['orden_internos'][$id_pregunta] = [
                'keys1' => $keys1, 
                'keys2' => $keys2,
                'claves_select' => $claves_select
            ];
        } else {
            $orden = $_SESSION['orden_internos'][$id_pregunta] ?? null;
            if ($orden) {
                $keys1 = $orden['keys1'];
                $keys2 = $orden['keys2'];
                $claves_select = $orden['claves_select'];
            } else {
                $keys1 = array_keys($contenido['div-1']);
                $keys2 = array_keys($contenido['div-2']);
                $claves_select = array_keys($contenido['div-2']);
            }
        }
        
        $map_numeros = [];
        foreach ($keys1 as $index => $orig_num) {
            $map_numeros[$orig_num] = $index + 1;
        }

        $map_letras = [];
        $display_to_original = [];
        foreach ($keys2 as $index => $orig_letra) {
            $disp_letra = chr(97 + $index); 
            $map_letras[$orig_letra] = $disp_letra;
            $display_to_original[$disp_letra] = $orig_letra;
        }
        
        ksort($display_to_original);

        $max_rows = max(count($keys1), count($keys2));

        echo '<div class="conecta-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: center; margin-bottom: 10px;">'; 

        for ($i = 0; $i < $max_rows; $i++) {
            
            // 1. COLUMNA IZQUIERDA
            echo '<div class="conecta-item-izq" style="display: flex; align-items: center; gap: 15px; width: 100%; position: relative;">';
            if (isset($keys1[$i])) {
                $number = $keys1[$i];
                $texto_opcion = $contenido['div-1'][$number];
                $num_visual = $map_numeros[$number]; 
                
                $classSelect = 'conecta-grupo-' . $id_pregunta; 
                $user_ans = $estado ? ($estado['usuario'][$number] ?? '') : '';
                
                if ($estado) {
                    $correct_ans = $estado['correcta'][$number] ?? '';
                    if ($user_ans === $correct_ans) {
                        $classSelect .= ' correct-bg'; 
                    } else {
                        $classSelect .= ' incorrect-bg'; 
                    }
                }

                // CONTENEDOR DEL DESPLEGABLE Y SU RESPUESTA CORRECTA
                echo '<div style="position: relative; display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">';

                // DESPLEGABLE CON LOS ESTILOS SOLICITADOS
                echo '<select name="respuestas[' . $id_pregunta . '][' . $number . ']" class="' . $classSelect . '" style="width: 70px; padding: 0.4rem 1.2rem 0.4rem 0.8rem; font-size: 0.95rem; background-position: right 0.3rem center; text-align: center; cursor: pointer;" ' . $disabled . ' ' . (!$estado?'required':'') . ' onchange="actualizarSelectsConecta(' . $id_pregunta . ')">';
                echo '<option value="">-</option>';
                
                foreach ($display_to_original as $disp_letter => $orig_key)  {
                    $selected = ($user_ans === (string)$orig_key) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($orig_key) . '" ' . $selected . '>' . htmlspecialchars($disp_letter) . '</option>';
                }
                echo '</select>';

                // AVISO DE RESPUESTA CORRECTA DEBAJO (Absoluto para no desplazar)
                if ($estado && $user_ans !== $correct_ans) {
                    $letra_correcta_visual = isset($map_letras[$correct_ans]) ? $map_letras[$correct_ans] : $correct_ans;
                    echo '<div class="correct-text" style="position: absolute; top: 100%; margin-top: 4px; font-size: 0.75rem; font-weight: bold; color: #EF4444; white-space: nowrap;">Correcta: ' . htmlspecialchars($letra_correcta_visual) . '</div>';
                }

                echo '</div>'; // Fin del contenedor del desplegable

                echo '<span style="flex-grow: 1;"><strong>' . htmlspecialchars($num_visual) . '.</strong> ' . htmlspecialchars($texto_opcion) . '</span>';
            }
            echo '</div>';

            // 2. COLUMNA DERECHA
            echo '<div class="conecta-item-der" style="display: flex; align-items: center; width: 100%;">';
            if (isset($keys2[$i])) {
                $letra = $keys2[$i];
                $texto_opcion = $contenido['div-2'][$letra];
                $letra_visual = $map_letras[$letra]; 
                echo '<span><strong>' . htmlspecialchars($letra_visual) . '.</strong> ' . htmlspecialchars($texto_opcion) . '</span>';
            }
            echo '</div>';
        }

        echo '</div>'; 

        // =========================================================================
        // SCRIPT JAVASCRIPT PARA OCULTAR OPCIONES
        // =========================================================================
        static $script_conecta_impreso = false;
        if (!$script_conecta_impreso) {
            echo '
            <script>
                function actualizarSelectsConecta(idPregunta) {
                    const selects = document.querySelectorAll(".conecta-grupo-" + idPregunta);
                    const valoresSeleccionados = Array.from(selects)
                        .map(s => s.value)
                        .filter(v => v !== "");

                    selects.forEach(select => {
                        const valorActual = select.value;
                        Array.from(select.options).forEach(opcion => {
                            if (opcion.value === "") return; 
                            
                            if (valoresSeleccionados.includes(opcion.value) && opcion.value !== valorActual) {
                                opcion.style.display = "none";
                                opcion.disabled = true;       
                            } else {
                                opcion.style.display = "";     
                                opcion.disabled = false;       
                            }
                        });
                    });
                }
            </script>';
            $script_conecta_impreso = true;
        }

        if (!$estado) {
            echo '<script>actualizarSelectsConecta(' . $id_pregunta . ');</script>';
        }
    }

    function decode_texto($id_pregunta, $contenido, $estado = null)  {  
        $disabled = $estado ? 'disabled' : '';
        $ejercicios = $contenido['ejercicios'] ?? [];
        $es_lista = count($ejercicios) > 1;

        // --- NUEVA LÓGICA DE ALEATORIEDAD PARA SUB-PREGUNTAS ---
        if (!$estado) {
            $keys_ejercicios = array_keys($ejercicios);
            if (ACTIVAR_ALEATORIEDAD && $es_lista) {
                shuffle($keys_ejercicios);
            }
            $_SESSION['orden_internos'][$id_pregunta] = $keys_ejercicios;
        } else {
            $keys_ejercicios = $_SESSION['orden_internos'][$id_pregunta] ?? array_keys($ejercicios);
        }

        // Iteramos sobre los ejercicios usando el orden (aleatorio o guardado)
        foreach ($keys_ejercicios as $i_visual => $i_ejercicio) {
            $ejercicio = $ejercicios[$i_ejercicio];
            $cadenas = $ejercicio['cadenas'];
            $respuestas_correctas = $ejercicio['respuestas'];

            echo '<div style="margin-bottom: 15px; display:flex; align-items:center; flex-wrap:wrap; gap:8px;">';
            
            // Usamos $i_visual + 1 para que visualmente siempre sea 1., 2., 3... independientemente del orden interno
            if ($es_lista) {
                echo '<strong>' . ($i_visual + 1) . '.</strong> ';
            }

            foreach ($cadenas as $i_cadena => $texto) {
                echo '<span>' . htmlspecialchars($texto) . '</span>';

                if ($i_cadena < count($cadenas) - 1) {
                    $user_ans = $estado ? ($estado['usuario'][$i_ejercicio][$i_cadena] ?? '') : '';
                    $correct_ans = $respuestas_correctas[$i_cadena] ?? '';
                    
                    $class = 'input-text input-texto-inline';
                    if ($estado) {
                        if (normalizarRespuesta($user_ans) === normalizarRespuesta($correct_ans)) {
                            $class .= ' correct-bg';
                        } else {
                            $class .= ' incorrect-bg';
                        }
                    }

                    // El name usa el $i_ejercicio original para que la validación lo encuentre perfectamente
                    echo '<input type="text" class="' . $class . '" name="respuestas[' . $id_pregunta . '][' . $i_ejercicio . '][' . $i_cadena . ']" value="' . htmlspecialchars($user_ans) . '" ' . $disabled . ' ' . (!$estado?'required':'') . ' autocomplete="off">';
                    
                    if ($estado && normalizarRespuesta($user_ans) !== normalizarRespuesta($correct_ans)) {
                        echo '<span class="correct-text" style="font-size: 0.85rem;">(' . htmlspecialchars($correct_ans) . ')</span>';
                    }
                }
            }
            echo '</div>';
        }
    }

    function decode_number($id_pregunta, $contenido, $estado = null)  {
        $disabled = $estado ? 'disabled' : '';
        $user_ans = $estado ? htmlspecialchars($estado['usuario']) : '';
        
        $class = 'input-number';
        if ($estado) {
            if ((string)$estado['usuario'] === (string)$estado['correcta']) {
                $class .= ' correct-bg';
            } else {
                $class .= ' incorrect-bg';
            }
        }

        echo 'Escribe tu respuesta: <br>';
        echo '<input type="number" class="' . $class . '" name="respuestas[' . $id_pregunta . ']" value="' . $user_ans . '" ' . $disabled . ' ' . (!$estado?'required':'') . ' step="0.01">';
        
        if ($estado && (string)$estado['usuario'] !== (string)$estado['correcta']) {
            echo '<div class="correct-text" style="margin-top: 5px;">Respuesta correcta: ' . htmlspecialchars($estado['correcta']) . '</div>';
        }
    }
?>