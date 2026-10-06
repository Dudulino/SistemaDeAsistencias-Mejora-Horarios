




const showHidePassword = (user_id) => {
    let showPassword = document.getElementById(`showPassword-${user_id}`);
    let hidePassword = document.getElementById(`hidePassword-${user_id}`);
    let iconEyeTable = document.getElementById(`iconEyeTable-${user_id}`);

    if(showPassword.hidden){
        showPassword.hidden = false;
        hidePassword.hidden = true;
        iconEyeTable.className = 'fa fa-eye-slash'
    } else{
        showPassword.hidden = true;
        hidePassword.hidden = false;
        iconEyeTable.className = 'fa fa-eye'

    }

}
const toogleInput = (inputId, iconId) => {
    let input = document.getElementById(inputId);
    let icon = document.getElementById(iconId);

    if(input.type == "password"){
        input.type = "text";
        icon.className = 'fa fa-eye-slash'
    } else {
        input.type = "password";
        icon.className = 'fa fa-eye'
    }
}

const deleteAlert = (id) => {
    let alertError = document.getElementById(id);
    if (alertError) {
        alertError.remove();
    } else{
        console.error(`Elemento con ID '${id}' no encontrado.`);
    }
}

$(document).ready(function(){
    $('.dataTables-example').DataTable({
        pageLength: 10,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            { extend: 'copy', exportOptions: { columns: ':not(.oculto)' }},
            { extend: 'csv', exportOptions: { columns: ':not(.oculto)' }},
            { extend: 'excel', title: 'USUARIOS', exportOptions: { columns: ':not(.oculto)' }},
            { extend: 'pdf',
            title: 'USUARIOS',
            exportOptions: { columns: ':not(.oculto)' },
            customize: function(doc) {
                // tamaño fuente
                doc.defaultStyle.fontSize = 10;

                // Ajustar el ancho de las columnas para ocupar todo el espacio disponible
                var columnCount = doc.content[1].table.body[0].length;
                var columnWidths = [];
                if (columnCount <= 6) {
                    columnWidths = Array(columnCount).fill('*');
                } else {
                    columnWidths = Array(columnCount).fill('auto');
                }
                doc.content[1].table.widths = columnWidths;

                // Estilo de la cabecera
                doc.styles.tableHeader = {
                    fillColor: '#4682B4',
                    color: 'white',
                    alignment: 'center',
                    bold: true,
                    fontSize: 12
                };

                // Ajustar los márgenes de la página
                doc.pageMargins = [20, 20, 20, 20]; }},
            { extend: 'print',
              customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table')
                        .addClass('compact');
                    $(win.document.body).find('thead th.oculto').css('display', 'none');
                    $(win.document.body).find('tbody td.oculto').css('display', 'none');
              },
              exportOptions: { columns: ':not(.oculto)' }
            }
        ],
        language: {
        url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    }
    });
});






