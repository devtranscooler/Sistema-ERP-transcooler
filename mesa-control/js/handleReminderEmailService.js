import { optionUserReminderReceptor } from "../../js/components/control-console/OptionUserReminderReceptor.js";
import { fetchOperativeAdminUsers } from "./api.js";
import { stateVars } from './state.js'

const modalReminderEmail = new bootstrap.Modal(document.getElementById('serviceReminderEmailModal'));


export const handleReminderEmailService = async(trigger, elements) => {
    stateVars.serviceSelectedReminderEmail = trigger.dataset.itemServiceAlertId;
    modalReminderEmail.show();

    elements.selectUserReminderReceptor.innerHTML = ''

    const users = await fetchOperativeAdminUsers()

    elements.selectUserReminderReceptor.innerHTML = `<option value="0"> Selecciona un usuario </option>`

    users.forEach(user => {
        elements.selectUserReminderReceptor.insertAdjacentHTML('beforeend', optionUserReminderReceptor(user));
    });

    return stateVars.serviceSelectedReminderEmail
}
