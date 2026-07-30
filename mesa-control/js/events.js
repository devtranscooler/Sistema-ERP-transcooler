import { stateVars } from "./state.js";
import { sendEmailReminder, updateStatusService } from './api.js';
import { updateCustomerTypeDropdownText, updateUnitTypeDropdownText } from "./helper.js";
import { loadServices } from "./handleRenderServices.js";

export function initializeEvents(elements) {

    let filterCustomerTypeTxt = ""

    const {
        authUserIdInpt,
        filterCustomerType,
        filtroEstatusMesaControl,
        dropdownFiltroEstatusMesaControl,
        serviceReminderEmailModal,
        selectUserReminderReceptor,
        btnSendReminderEmail,
        textAreaReminderComments,
        btnUpdateStageStatusService,
        selectUpdateEtapaServicio,
        selectUpdateStatusEtapaServicio,
        textAreaUpdateStageComments
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

    //select del filtro por unidad
    dropdownFiltroEstatusMesaControl.addEventListener("click", async(e) => {
        e.preventDefault();

        const item = e.target.closest("[data-unit-type]");
        if (!item) return;

        stateVars.extensionFilterUnitType = item.dataset.unitType;
        stateVars.currentPage = 1;

        updateUnitTypeDropdownText(item);
        console.log(item);

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
    });

    btnUpdateStageStatusService.addEventListener('click', async(e) => {
        e.preventDefault();

        const stageServiceId = selectUpdateEtapaServicio.value
        const statusServiceStatusId = selectUpdateStatusEtapaServicio.value
        const comments = textAreaUpdateStageComments.value

        if (!stateVars.stageServiceSelectedId) {
            return alert("Hubo un error con el servicio seleccionado");
        }

        if (stageServiceId === "0" || statusServiceStatusId === "0") {
            return alert("Debes seleccionar una etapa y un estatus");
        }

        btnUpdateStageStatusService.setAttribute('disabled', '')
        btnUpdateStageStatusService.textContent = 'Actualizando...'

        try {
            const formData = new FormData()
            formData.append('userId', parseInt(authUserIdInpt.value))
            formData.append('serviceId', parseInt(stateVars.stageServiceSelectedId))
            formData.append('stageServiceId', parseInt(stageServiceId))
            formData.append('statusServiceStatusId', parseInt(statusServiceStatusId))
            formData.append('comments', comments)

            await updateStatusService(formData);

            btnUpdateStageStatusService.removeAttribute('disabled');
            btnUpdateStageStatusService.textContent = 'Actualizar estatus'
            textAreaUpdateStageComments.value = ''

            const modalInstance = bootstrap.Modal.getInstance(document.getElementById('stageServicestatusUpdateModal'));
            modalInstance?.hide();

            stateVars.stageServiceSelectedId = null

            await loadServices(elements);

            await Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "Estatus actualizado con éxito",
                timer: 2000,
                showConfirmButton: false
            });

        } catch (error) {
            btnUpdateStageStatusService.removeAttribute('disabled');
            btnUpdateStageStatusService.textContent = 'Actualizar estatus'
            console.error(error)
            alert("No se pudo actualizar el estatus del servicio");
        }
    });
}