


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
               const colaboradorId = input.id.split('-')[2]; // Para los campos de actualización
               counterId = `cel-counter-update-${colaboradorId}`;
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
               const colaboradorId = inputUpdate.id.split('-')[2];  // Obtiene el ID del candidato
               const counterUpdate = document.getElementById(`cel-counter-update-${colaboradorId}`);

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
               const colaboradorId = input.id.split('-')[2]; // Para los campos de actualización
               counterId = `dni-counter-update-${colaboradorId}`;
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
        //    console.log(input.value);
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
               const colaboradorId = inputUpdate.id.split('-')[2];
               const counterUpdate = document.getElementById(`dni-counter-update-${colaboradorId}`);

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


        const deleteAlert = () => {
            let alert = document.getElementById('alert');
            if (alert) {
                alert.remove();
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
            const colaborador = document.getElementById('colaboradores');
            if (colaborador) {
                colaborador.classList.add('active');
            } else {
                console.error("El elemento con el id 'colaboradores' no se encontró en el DOM.");
            }
        });

        function hideModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('show');

                // Remover el Backdrop
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.parentNode.removeChild(backdrop);
                }
            }
        }

        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('show');
            }
        }

        function abrirModalEdicion(id) {
            hideModal('modal-form-view' + id);
            showModal('modal-form-update' + id);
        }







        $(document).ready(function() {
            $('.select2-distrito').select2({
                placeholder: "Buscar distrito...",
                allowClear: true,
                width: '100%'
            });
        });

        //JQuery para select multiple de areas
        $(document).ready(function() {
            $('.multiple_areas_select').select2();
        });
        $(document).ready(function() {
            $('.multiple_apoyo_select').select2();
        });
        $(document).ready(function() {
            $('.multiple_actividades_select').select2({
                width: '100px'
            });
        });

        document.querySelectorAll('.btn-pago-colab').forEach(button => {
            button.addEventListener('click', function() {
                const colaboradorId = this.id.split('-')[2];

                const newFields = document.createElement('div');
                newFields.classList.add('input-group', 'mb-2', 'gasto-item');

                const uniqueId = `descripcion-${colaboradorId}-${document.querySelectorAll('.gasto-item').length}`;
                const unId = `monto-${colaboradorId}-${document.querySelectorAll('.gasto-item').length}`;

                const descripcionContent = document.createElement('div');
                descripcionContent.classList.add('descripcion-content');

                const descripcionLabel = document.createElement('label');
                descripcionLabel.textContent = 'Descripción';
                descripcionLabel.setAttribute('for', uniqueId);

                const descripcionInput = document.createElement('input');
                descripcionInput.setAttribute('type', 'text');
                descripcionInput.classList.add('form-control');
                descripcionInput.setAttribute('name', `descripcion[${colaboradorId}][]`);
                descripcionInput.setAttribute('id', uniqueId);

                descripcionContent.appendChild(descripcionLabel);
                descripcionContent.appendChild(descripcionInput);

                const montoContent = document.createElement('div');
                montoContent.classList.add('monto-content');

                const montoLabel = document.createElement('label');
                montoLabel.textContent = 'Monto';
                montoLabel.setAttribute('for', unId);

                const montoInput = document.createElement('input');
                montoInput.setAttribute('type', 'number');
                montoInput.classList.add('form-control');
                montoInput.setAttribute('name', `monto[${colaboradorId}][]`);
                montoInput.setAttribute('id', unId);

                montoContent.appendChild(montoLabel);
                montoContent.appendChild(montoInput);

                const deleteButton = document.createElement('button');
                deleteButton.classList.add('btn', 'btn-danger', 'btn-sm', 'delete-btn', 'btn-pagos');
                deleteButton.innerHTML = 'X';
                deleteButton.addEventListener('click', function() {
                    newFields.remove();
                });

                newFields.appendChild(descripcionContent);
                newFields.appendChild(montoContent);
                newFields.appendChild(deleteButton);

                document.getElementById(`gastos-container-${colaboradorId}`).appendChild(newFields);
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const gastoItem = this.closest('.gasto-item');
                const gastoId = gastoItem.dataset.id;
                const colaboradorId = this.closest('.pagos-content').id.split('-')[2];

                if (gastoId) {
                    let hiddenInput = document.getElementById(`eliminar-gastos-${colaboradorId}`);
                    let valoresActuales = hiddenInput.value ? hiddenInput.value.split(',') : [];
                    valoresActuales.push(gastoId);
                    hiddenInput.value = valoresActuales.join(',');
                }

                gastoItem.remove();
            });
        });

