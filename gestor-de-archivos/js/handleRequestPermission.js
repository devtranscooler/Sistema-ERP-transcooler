import { fetchUsersAdmin } from "./api.js";
import { optionPermissionUser } from "../../js/components/file-manager/OptionPermissionUser.js";
import { stateVars } from "./state.js";

const modalRequestPermissionToDownload = new bootstrap.Modal(
    document.getElementById('requestPermissionDownload')
);

export const handleRequestPermission = async (trigger, elements) => {

    stateVars.selectedFileToRequestPermission  = trigger.dataset.mediaId;

    modalRequestPermissionToDownload.show();

    elements.selectRequestPermissionUser.innerHTML = '';

    const users = await fetchUsersAdmin();

    elements.selectRequestPermissionUser.innerHTML = `<option value="0"> Selecciona </option>`

    users.forEach(user => {

        elements.selectRequestPermissionUser.insertAdjacentHTML(
            'beforeend',
            optionPermissionUser(user)
        );

    });

    return stateVars.selectedFileToRequestPermission;
};
