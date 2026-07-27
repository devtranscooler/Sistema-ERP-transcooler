export const getElements = () => {

    const elements = {
        authUserIdInpt: document.getElementById('auth_user_id'),
        btnFilterCustomerType: document.getElementById('btnFilterCustomerType'),
        filterCustomerType: document.getElementById('filterCustomerType'),
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
        textAreaReminderComments: document.getElementById('comentario_reminder')
    };

    return elements;
}