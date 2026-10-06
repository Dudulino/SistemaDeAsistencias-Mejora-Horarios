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

let cacheEmail = '';
let cacheNombre = '';
let cacheApellido = ''
let cacheColabId = null;
let cacheAreas = [];

// Inicializar Choices.js para permitir escribir en el select
document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.getElementById('colaboradorSelectedId');

    // Crear un nuevo objeto Choices para hacer el select escribible y filtrable
    const choices = new Choices(selectElement, {
        searchEnabled: true,
        itemSelectText: '',
        noResultsText: 'No se encontraron colaboradores',
    });
});






const handleTypeChange = () => {
    const selectType = document.getElementById('selectUserType');
    const btnColabs = document.getElementById('btnModalColaboradores');
    const selectedValue = selectType.value;

    let email = document.getElementById('email');
    let name = document.getElementById('name');
    let apellido = document.getElementById('apellido');

    if(selectedValue == 1){
        btnColabs.disabled = true;
        email.value = '';
        name.value = '';
        apellido.value = '';
        destroyAreas();
        destroyColabInput();
    } else if(selectedValue == 2){
        btnColabs.disabled = false;
        email.value = cacheEmail;
        name.value = cacheNombre;
        apellido.value = cacheApellido;
        renderAreas();
        if(cacheAreas.length > 0){
            let selectAreas = document.getElementById('selectAreas');
            Array.from(selectAreas.options).forEach(option => {
                option.selected = false;
            });

            Array.from(selectAreas.options).forEach(option => {
                //Verificar que sea el mismo area del colaborador
                cacheAreas.forEach(areaJefe => {
                    if(areaJefe.id == option.value) option.selected = true;
                });
            });

            $('.multiple_areas_select').trigger('change');

        }
        if(cacheColabId != null) renderColabInput(cacheColabId);
    }

    verifyCorrectInputs();

}


const verifySamePassword = () => {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const errorMessage = document.getElementById('errorMessage')

    if(password.length < 8){
        errorMessage.hidden = false;
        errorMessage.innerText = 'La contraseña debe ser igual o mayor a 8 caracteres'
    } else{
        if (password !== confirmPassword) {
        errorMessage.hidden = false;
        errorMessage.innerText = 'Las contraseñas no coinciden'
        } else{
            errorMessage.hidden = true
            errorMessage.innerText = '';
        }
    }

    verifyCorrectInputs();
}

verifyCorrectInputs = () => {
    const submitButton = document.getElementById('submitButton');
    const email = document.getElementById('email').value;
    const name = document.getElementById('name').value;
    const apellido = document.getElementById('apellido').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const selectAreas = document.getElementById('selectAreas');

    if(email != '' && name != '' && apellido != '' && password != '' && confirmPassword != '' && password == confirmPassword && password.length >= 8 && confirmPassword.length >= 8) {
        if(selectAreas != null){
            //verificar que no este vacio
            const selectedOptions = Array.from(selectAreas.selectedOptions)
            if(selectedOptions.length > 0) {
                submitButton.disabled = false
            } else{
                submitButton.disabled = true
            }
        } else{
            submitButton.disabled = false
        }
    } else{
        submitButton.disabled = true
    }

}
