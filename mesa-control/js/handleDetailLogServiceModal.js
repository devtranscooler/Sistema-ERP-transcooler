import { logsServicesNotFoundDetail } from "../../js/components/control-console/LogsServicesNotFoundDetail.js";
import { recordLogsServicesDetail } from "../../js/components/control-console/RecordLogsServicesDetail.js";
import { fetchConsoleControlServiceById } from "./api.js";

const modalLogServiceDetail = new bootstrap.Modal(document.getElementById('serviceLogDetailModal'));

export const handleDetailLogService = async(trigger, elements) => {
    const serviceId = trigger.dataset.itemServiceId;
    const operatorName = trigger.dataset.itemNombreOperador
    const licensePlatesUnit = trigger.dataset.itemPlacasUnidad
    const unitType = trigger.dataset.itemTipoUnidad

    modalLogServiceDetail.show();

    if(!serviceId) return

    elements.textServiceNumberDetail.textContent = `#${serviceId}`
    elements.textServiceLicensePlatesUnitDetail.textContent = licensePlatesUnit
    elements.textServiceUnitTypeDetail.textContent =  unitType
    elements.textServiceOperatorNameDetail.textContent = operatorName

    const detailLogsService = await fetchConsoleControlServiceById(serviceId)

    elements.textServiceTotalLogs.textContent = `${detailLogsService?.length ?? 0 } movimientos `
    elements.containerRecordLogsServices.innerHTML = '';
    
    if (!detailLogsService?.length) {
        elements.containerRecordLogsServices.insertAdjacentHTML('beforeend', logsServicesNotFoundDetail());
        return;
    }

    detailLogsService.forEach(logService => {
        elements.containerRecordLogsServices.insertAdjacentHTML('beforeend', recordLogsServicesDetail(logService));
    });

    return serviceId;
}
