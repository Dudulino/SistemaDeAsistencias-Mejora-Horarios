
         // limiteCel
         function limitCel(input) {
            // Asegura que solo se permitan 8 caracteres
            if (input.value.length > 9) {
                input.value = input.value.slice(0, 9); // Limita a 8 caracteres
            }

            // Obtener el ID del candidato para el contador correspondiente
            let counterId;
            if (input.id.includes('store')) {
                counterId = 'cel-counter-store'; // Para el campo de creación
            } else {
                const candidateId = input.id.split('-')[2]; // Para los campos de actualización
                counterId = `cel-counter-update-${candidateId}`;
            }

            const counter = document.getElementById(counterId);

            // Actualiza el contador de caracteres
            counter.textContent = `${input.value.length}/9`;

            // Cambia el color del borde según el número de caracteres
            if (input.value.length >= 1 && input.value.length < 9) {
                input.style.borderColor = 'red'; // Rojo cuando llega a 1-7 caracteres
            } else if (input.value.length === 9) {
                input.style.borderColor = 'green'; // Verde cuando llega a 8 caracteres
            } else {
                input.style.borderColor = ''; // Restablece el borde si no está en el rango
            }
        }

        // Inicializa el contador y el borde al cargar la página
        document.addEventListener("DOMContentLoaded", function() {
            // Para Store (Crear)
            const inputStore = document.getElementById('cel-store');
            const counterStore = document.getElementById('cel-counter-store');
            if (inputStore) {
                const initialValueStore = inputStore.value || '';
                counterStore.textContent = `${initialValueStore.length}/9`;

                if (initialValueStore.length >= 1 && initialValueStore.length < 9) {
                    inputStore.style.borderColor = 'red';
                } else if (initialValueStore.length === 9) {
                    inputStore.style.borderColor = 'green';
                }

                inputStore.addEventListener('input', function() {
                    limitCel(inputStore);
                });
            }

            // Para Update (Actualizar)
            const inputsUpdate = document.querySelectorAll('[id^="cel-update-"]');

            inputsUpdate.forEach(inputUpdate => {
                const candidateId = inputUpdate.id.split('-')[2];  // Obtiene el ID del candidato
                const counterUpdate = document.getElementById(`cel-counter-update-${candidateId}`);

                // Inicializa el contador y el borde según el valor inicial
                const initialValueUpdate = inputUpdate.value || '';
                counterUpdate.textContent = `${initialValueUpdate.length}/9`;

                if (initialValueUpdate.length >= 1 && initialValueUpdate.length < 9) {
                    inputUpdate.style.borderColor = 'red';
                } else if (initialValueUpdate.length === 9) {
                    inputUpdate.style.borderColor = 'green';
                }

                // Agregar event listener al input
                inputUpdate.addEventListener('input', function() {
                    limitCel(inputUpdate);
                });
            });
        });



        // limiteDNI
        function limitDNI(input) {
            // Asegura que solo se permitan 8 caracteres
            if (input.value.length > 8) {
                input.value = input.value.slice(0, 8); // Limita a 8 caracteres
            }

            // Obtener el ID del candidato para el contador correspondiente
            let counterId;
            if (input.id.includes('store')) {
                counterId = 'dni-counter-store'; // Para el campo de creación
            } else {
                const candidateId = input.id.split('-')[2]; // Para los campos de actualización
                counterId = `dni-counter-update-${candidateId}`;
            }

            const counter = document.getElementById(counterId);

            // Actualiza el contador de caracteres
            counter.textContent = `${input.value.length}/8`;

            // Cambia el color del borde según el número de caracteres
            if (input.value.length >= 1 && input.value.length < 8) {
                input.style.borderColor = 'red'; // Rojo cuando llega a 1-7 caracteres
            } else if (input.value.length === 8) {
                input.style.borderColor = 'green'; // Verde cuando llega a 8 caracteres
            } else {
                input.style.borderColor = ''; // Restablece el borde si no está en el rango
            }
        }

        // Inicializa el contador y el borde al cargar la página
        document.addEventListener("DOMContentLoaded", function() {
            // Para Store (Crear)
            const inputStore = document.getElementById('dni-store');
            const counterStore = document.getElementById('dni-counter-store');
            if (inputStore) {
                const initialValueStore = inputStore.value || '';
                counterStore.textContent = `${initialValueStore.length}/8`;

                if (initialValueStore.length >= 1 && initialValueStore.length < 8) {
                    inputStore.style.borderColor = 'red';
                } else if (initialValueStore.length === 8) {
                    inputStore.style.borderColor = 'green';
                }

                inputStore.addEventListener('input', function() {
                    limitDNI(inputStore);
                });
            }

            // Para Update (Actualizar)
            const inputsUpdate = document.querySelectorAll('[id^="dni-update-"]');

            inputsUpdate.forEach(inputUpdate => {
                const candidateId = inputUpdate.id.split('-')[2];  // Obtiene el ID del candidato
                const counterUpdate = document.getElementById(`dni-counter-update-${candidateId}`);

                // Inicializa el contador y el borde según el valor inicial
                const initialValueUpdate = inputUpdate.value || '';
                counterUpdate.textContent = `${initialValueUpdate.length}/8`;

                if (initialValueUpdate.length >= 1 && initialValueUpdate.length < 8) {
                    inputUpdate.style.borderColor = 'red';
                } else if (initialValueUpdate.length === 8) {
                    inputUpdate.style.borderColor = 'green';
                }

                // Agregar event listener al input
                inputUpdate.addEventListener('input', function() {
                    limitDNI(inputUpdate);
                });
            });
        });
        const deleteAlertError = () => {
            let alertError = document.getElementById('alert-error');
            if (alertError) {
                alertError.remove();
            } else{
                console.error("Elemento con ID 'alert-error' no encontrado.");
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const personal = document.getElementById('personalCont');
            if (personal) {
                personal.classList.add('active');
            } else {
                console.error("El elemento con el id 'personalCont' no se encontró en el DOM.");
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const candidato = document.getElementById('candidatos');
            if (candidato) {
                candidato.classList.add('active');
            } else {
                console.error("El elemento con el id 'candidato' no se encontró en el DOM.");
            }
        });

        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('show');
                modal.style.display = 'block'; // Asegúrate de que el modal se muestre
            }
        }

        function hideModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('show');
                modal.style.display = 'none'; // Asegúrate de que el modal se oculte
            }
        }

        function abrirModalCreacion(index) {
            ocultarTodosLosModales();
            showModal('modal-create-form-' + index);
        }


        function abrirModalEdicion(id) {
            hideModal('modal-form-view' + id);
            showModal('modal-form-update' + id);
        }
        function updateSelectAll(checkboxGroup, selectAllId) {
            const selectAllCheckbox = document.getElementById(selectAllId);
            const checkboxes = document.querySelectorAll(checkboxGroup);
            selectAllCheckbox.checked = Array.from(checkboxes).every(checkbox => checkbox.checked);
        }

        document.getElementById('select-all-estados').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[id^="checkbox-estados-"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });

        document.getElementById('select-all-carreras').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[id^="checkbox-carreras-"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });

        document.getElementById('select-all-instituciones').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[id^="checkbox-institucion-"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });

        // sedes
        document.getElementById('select-all-sedes').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[id^="checkbox-sedes-"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });



        document.querySelectorAll('input[id^="checkbox-estados-"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateSelectAll('input[id^="checkbox-estados-"]', 'select-all-estados');
            });
        });

        document.querySelectorAll('input[id^="checkbox-carreras-"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateSelectAll('input[id^="checkbox-carreras-"]', 'select-all-carreras');
            });
        });

        document.querySelectorAll('input[id^="checkbox-institucion-"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateSelectAll('input[id^="checkbox-institucion-"]', 'select-all-instituciones');
            });
        });


        // sedes
        document.querySelectorAll('input[id^="checkbox-sedes-"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateSelectAll('input[id^="checkbox-sedes-"]', 'select-all-sedes');
            });
        })



    document.getElementById('select-all-estados').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.estado-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('select-all-carreras').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.carrera-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('select-all-instituciones').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.institucion-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('select-all-ciclos').addEventListener('change', function () {
        let checkboxes = document.querySelectorAll('.ciclo-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    //sedes
    document.getElementById('select-all-sedes').addEventListener('change', function () {
        let checkboxes = document.querySelectorAll('.sede-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        });

