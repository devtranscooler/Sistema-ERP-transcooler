import { fileItemService } from "../../js/components/control-console/ServiceRow.js";
import { servicesNotFound } from "../../js/components/control-console/ServicesNotFound.js"
import { fetchConsoleControlServices } from "./api.js";
import { stateVars } from "./state.js";
import { paginationComponent } from "../../js/components/file-manager/PaginationContainer.js";

export const handleRenderServices = (services, pagination, elements) => {

    const { containerServicesTable, paginationContainer } = elements;

    containerServicesTable.innerHTML = '';
    
    if (!services?.length) {
        containerServicesTable.insertAdjacentHTML('beforeend', servicesNotFound());
        paginationContainer.innerHTML = '';
        return;
    }

    services.forEach(service => {
        containerServicesTable.insertAdjacentHTML('beforeend', fileItemService(service));
    });

    paginationContainer.innerHTML = paginationComponent(pagination.page, pagination.total_pages);

}

export const loadServices = async(elements) => {

    const response = await fetchConsoleControlServices(
        stateVars.currentPage,
        stateVars.perPage,
        stateVars.extensionFilterCustomerType,
        stateVars.extensionFilterUnitType
    );

    handleRenderServices(
        response.data,
        response.pagination,
        elements
    );
};