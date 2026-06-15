export const getElements = () => {

    const elements = {
        inptSearchFile: document.getElementById('inpt-search-file'),
        btnSearchFile: document.getElementById('btnSearchFile'),
        btnListView: document.getElementById('btnListView'),
        listView: document.getElementById('listView'),
        uploadBtn: document.getElementById('openDialogFilesBtn'),
        fileInput: document.getElementById('fileInput'),
        containerFilesPreview: document.getElementById('container-files-preview'),
        selectRequestPermissionUser: document.getElementById('usuario_aprobador_id'),
        filterFileExtension: document.getElementById("dropdownFileExtensionFilter"),
        uploadModal: document.getElementById('uploadModal'),
        requestPermissionModal: document.getElementById('requestPermissionDownload'),
        deleteModal: document.getElementById('fileDeleteModal'),
        containerFileDetail: document.getElementById('container-file-details'),
        containerFileDelete: document.getElementById('container-file-delete'),
        btnSendFiles: document.getElementById('send-images'),
        btnRequestPermission: document.getElementById('btn-request-permission'),
        btnConfirmDelete: document.getElementById('btn-delete-file'),
        authUserId: document.getElementById('auth_user_id'),
        btnResetSearch: document.getElementById('btn-reset-search'),
        paginationContainer: document.getElementById('pagination-container')
    };

    return elements;
}