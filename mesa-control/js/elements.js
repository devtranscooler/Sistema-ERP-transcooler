export const getElements = () => {

    const elements = {
        authUserIdInpt: document.getElementById('auth_user_id'),
        btnFilterCustomerType: document.getElementById('btnFilterCustomerType'),
        filterCustomerType: document.getElementById('filterCustomerType'),
        btnFiltroEstatusMesaControl: document.getElementById('btnFiltroEstatusMesaControl'),
        dropdownFiltroEstatusMesaControl: document.getElementById('dropdownFiltroEstatusMesaControl'),
        containerServicesTable: document.getElementById('container-services'),
        paginationContainer: document.getElementById('pagination-container'),
        serviceLogDetailModa: document.getElementById('serviceLogDetailModal'),
        serviceReminderEmailModal: document.getElementById('serviceReminderEmailModal'),
        titleItemDetailLogService: document.getElementById('titleItemDetailLogService'),
        containerDetailLogService: document.getElementById('container-detail-log-service'),
        containerRecordLogsServices: document.getElementById('container-record-logs-services'),
        textServiceNumberDetail: document.getElementById('service-number-detail'),
        textServiceLicensePlatesUnitDetail: document.getElementById('service-license-plates-unit-detail'),
        textServiceUnitTypeDetail: document.getElementById('service-unit-type-detail'),
        textServiceOperatorNameDetail: document.getElementById('service-operator-name-detail'),
        textServiceTotalLogs: document.getElementById('service-total-logs-detail'),
        selectUserReminderReceptor: document.getElementById('recipient_user_id'),
        btnSendReminderEmail: document.getElementById('btn-send-reminder-email'),
        textAreaReminderComments: document.getElementById('comentario_reminder'),
        selectUpdateEtapaServicio: document.getElementById('update_etapa_servicio_id'),
        selectUpdateStatusEtapaServicio: document.getElementById('update_status_etapa_servicio_id'),
        textAreaUpdateStageComments: document.getElementById('update_comments_etapa_servicio'),
        btnUpdateStageStatusService: document.getElementById('btn-update-stage-status-service')
    };

    return elements;
}