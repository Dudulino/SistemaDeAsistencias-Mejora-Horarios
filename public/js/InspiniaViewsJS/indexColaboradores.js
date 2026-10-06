const deleteAlertError = () => {
    let alertError = document.getElementById('alert-error');
    if (alertError) {
        alertError.remove();
    } else {
        console.error("Elemento con ID 'alert-error' no encontrado.");
    }
}
document.addEventListener('DOMContentLoaded', function () {
    const personal = document.getElementById('personalCont');
    if (personal) {
        personal.classList.add('active');
    } else {
        console.error("El elemento con el id 'personalCont' no se encontró en el DOM.");
    }
});

document.addEventListener('DOMContentLoaded', function () {
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

function confirmDelete(id, currentURL) {
    alertify.confirm("¿Deseas eliminar este registro? Esta acción es permanente y eliminará todo lo relacionado a este colaborador", function (e) {
        if (e) {
            let form = document.createElement('form')

            form.method = 'POST';
            form.action = `/colaboradores/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;

            if (currentURL != null) {
                let inputHidden = document.createElement('input');
                inputHidden.type = 'hidden';
                inputHidden.name = 'currentURL';
                inputHidden.value = currentURL;
                form.appendChild(inputHidden)
            }

            document.body.appendChild(form)
            form.submit()
        } else {
            return false
        }
    });
}

function prepareSearchActionURL(event) {

    // event.preventDefault();

    let busqueda = document.getElementById('searchInput').value;
    let searchRoute = document.getElementById('searchRoute').value;
    // console.log(searchRoute);
    // return true;
    if (busqueda.trim().length > 0) {
        console.log(busqueda);

        const actionUrl = `${searchRoute}/${busqueda}`
        // let actionUrl = `{{ url('colaboradores/search/${busqueda}') }}`;
        console.log(actionUrl);
        document.querySelector('#searchColaboradores').action = actionUrl;

        return true;
    } else {
        event.preventDefault();
        return false;
    }

}

function prepareFilterActionURL() {
    let estados = Array.from(document.querySelectorAll('.estado-checkbox:checked')).map(cb => cb.value);
    let areas = Array.from(document.querySelectorAll('.area-checkbox:checked')).map(cb => cb.value);
    let carreras = Array.from(document.querySelectorAll('.carrera-checkbox:checked')).map(cb => cb.value);
    let instituciones = Array.from(document.querySelectorAll('.institucion-checkbox:checked')).map(cb => cb.value);

    estados = estados.length ? estados.join(',') : '0,1,2';
    areas = areas.length ? areas.join(',') : '';
    carreras = carreras.length ? carreras.join(',') : '';
    instituciones = instituciones.length ? instituciones.join(',') : '';

    if (estados != null && areas != null && carreras != null && instituciones != null) {
        let actionUrl = `{{ url('colaboradores/filtrar/estados=${estados}/areas=${areas}/carreras=${carreras}/instituciones=${instituciones}') }}`;
        console.log(actionUrl);
        document.querySelector('#filtrarColaboradores').action = actionUrl;

        return true;
    }

}

document.getElementById('select-all-estados').addEventListener('change', function () {
    let checkboxes = document.querySelectorAll('.estado-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

document.getElementById('select-all-areas').addEventListener('change', function () {
    let checkboxes = document.querySelectorAll('.area-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
document.getElementById('select-all-carreras').addEventListener('change', function () {
    let checkboxes = document.querySelectorAll('.carrera-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

document.getElementById('select-all-instituciones').addEventListener('change', function () {
    let checkboxes = document.querySelectorAll('.institucion-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});






function updateSelectAll(checkboxGroup, selectAllId) {
    const selectAllCheckbox = document.getElementById(selectAllId);
    const checkboxes = document.querySelectorAll(checkboxGroup);
    selectAllCheckbox.checked = Array.from(checkboxes).every(checkbox => checkbox.checked);
}

document.getElementById('select-all-areas').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[id^="checkbox-areas-"]');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
});

document.getElementById('select-all-estados').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[id^="checkbox-estados-"]');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
});

document.getElementById('select-all-carreras').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[id^="checkbox-carreras-"]');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
});

document.getElementById('select-all-instituciones').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[id^="checkbox-institucion-"]');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
});

document.querySelectorAll('input[id^="checkbox-areas-"]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        updateSelectAll('input[id^="checkbox-areas-"]', 'select-all-areas');
    });
});

document.querySelectorAll('input[id^="checkbox-estados-"]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        updateSelectAll('input[id^="checkbox-estados-"]', 'select-all-estados');
    });
});

document.querySelectorAll('input[id^="checkbox-carreras-"]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        updateSelectAll('input[id^="checkbox-carreras-"]', 'select-all-carreras');
    });
});

document.querySelectorAll('input[id^="checkbox-institucion-"]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        updateSelectAll('input[id^="checkbox-institucion-"]', 'select-all-instituciones');
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
    $('.multiple_actividades_select').select2();
});








