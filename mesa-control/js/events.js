import { stateVars } from "./state.js";
import { sendEmailReminder } from './api.js';
import { updateCustomerTypeDropdownText } from "./helper.js";
import { loadServices } from "./handleRenderServices.js";

export function initializeEvents(elements) {

    let filterCustomerTypeTxt = ""

    const {
        authUserIdInpt,
        filterCustomerType,
        serviceReminderEmailModal,
        selectUserReminderReceptor,
        btnSendReminderEmail,
        textAreaReminderComments
    } = elements

    filterCustomerType.addEventListener("click", async(e) => {
    
        e.preventDefault();

        const item = e.target.closest(".dropdown-item-customer-type");
        if (!item) return;
        
        filterCustomerTypeTxt = item.dataset.customerType;
        updateCustomerTypeDropdownText(item);

        stateVars.extensionFilterCustomerType = item.dataset.customerType === "all" ? "" : item.dataset.customerType;
        stateVars.currentPage = 1;

        updateCustomerTypeDropdownText(item);

        await loadServices(elements);
    });

    selectUserReminderReceptor.addEventListener('change', (event) => {
        stateVars.userReminderReceptor = event.target.value
    });

    btnSendReminderEmail.addEventListener('click', async(e) => {
    
        e.preventDefault()

        if(!stateVars.serviceSelectedReminderEmail) {
            return alert("Hubo un error con el servicio seleccionado");
        }

        if(!stateVars.userReminderReceptor) {
            return alert('Debes seleccionar un usuario'); 
        }

        btnSendReminderEmail.setAttribute('disabled', '')
        btnSendReminderEmail.textContent = ''
        btnSendReminderEmail.textContent = 'Enviando...'

        try {
            
            const formData = new FormData();
            
            formData.append("service_id", parseInt(stateVars.serviceSelectedReminderEmail))
            formData.append("send_user_id", parseInt(authUserIdInpt.value))
            formData.append("recipient_user_id", parseInt(stateVars.userReminderReceptor))
            formData.append("comments", textAreaReminderComments.value.length <= 0 ? null : textAreaReminderComments.value)

            await sendEmailReminder(formData)

            btnSendReminderEmail.removeAttribute('disabled');
            btnSendReminderEmail.textContent = 'Enviar'
            textAreaReminderComments.value = ''
            const modalInstance = bootstrap.Modal.getInstance(elements.serviceReminderEmailModal);
            modalInstance?.hide();

            stateVars.serviceSelectedReminderEmail = null
            stateVars.userReminderReceptor = null

            await Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "Recordatorio enviado con éxito",
                timer: 2000,
                showConfirmButton: false
            });

        } catch (error) {
            console.error(error)
        }
    })
}