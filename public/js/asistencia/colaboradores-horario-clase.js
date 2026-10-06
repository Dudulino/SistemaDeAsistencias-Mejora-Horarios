function agregarFila() {
    var tabla = document.getElementById("tablaHorarios").getElementsByTagName('tbody')[0];
    var nuevaFila = tabla.insertRow(tabla.rows.length);

    // Insertar celdas en la nueva fila
    var celdaDia = nuevaFila.insertCell(0);
    var celdaHoraInicial = nuevaFila.insertCell(1);
    var celdaHoraFinal = nuevaFila.insertCell(2);
    var celdaJustificacion = nuevaFila.insertCell(3);
    var celdaBotonEliminar = nuevaFila.insertCell(4);

    contadorFilas++;

    // Construir el select de horas iniciales y finales
    var selectHoraInicial = construirSelectHora('horarios[' + contadorFilas + '][hora_inicial]');
    var selectHoraFinal = construirSelectHora('horarios[' + contadorFilas + '][hora_final]');
    var selectJustificacion = constuirSelectJustificacion();

    celdaDia.innerHTML = '<div class="form-group row"><label class="col-form-label"></label><div class="col-sm-10"><select class="form-control m-b" name="horarios[' + contadorFilas + '][dia]"><option>Lunes</option><option>Martes</option><option>Miércoles</option><option>Jueves</option><option>Viernes</option><option>Sábado</option><option>Domingo</option></select></div></div>';
    celdaHoraInicial.innerHTML = '<div class="input-group date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>' + selectHoraInicial + '</div>';
    celdaHoraFinal.innerHTML = '<div class="input-group date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>' + selectHoraFinal + '</div>';
    celdaJustificacion.innerHTML = '<div class="input-group">' + selectJustificacion + '</div>';
    celdaBotonEliminar.innerHTML = '<button class="btn btn-danger float-right" type="button" onclick="eliminarFila(this)"><i class="fa fa-trash-o"></i></button>';
}

function construirSelectHora(name) {
    var select = '<select class="form-control" name="' + name + '">';
    for (var i = 0; i < horas.length; i++) {
        select += '<option value="' + horas[i] + '">' + horas[i] + '</option>';
    }
    select += '</select>';
    return select;
}

function eliminarFila(boton) {
    var fila = boton.parentNode.parentNode;
    fila.parentNode.removeChild(fila);
}

const constuirSelectJustificacion = () => {
    let select = `<select class="form-control" name="horarios[${contadorFilas}][justificacion]"><option>Clases</option><option>Trabajo</option></select>`;
    return select;
}
