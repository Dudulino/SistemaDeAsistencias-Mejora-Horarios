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
        const especialistaId = input.id.split('-')[2]; // Para los campos de actualización
        counterId = `cel-counter-update-${especialistaId}`;
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
        const especialistaId = inputUpdate.id.split('-')[2];  // Obtiene el ID del candidato
        const counterUpdate = document.getElementById(`cel-counter-update-${especialistaId}`);

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
