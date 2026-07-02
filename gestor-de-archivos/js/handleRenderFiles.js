import { filesNotFound } from "../../js/components/file-manager/FilesNotFound.js";
import { fileItemCard } from "../../js/components/file-manager/FileItemCard.js";
import { paginationComponent } from "../../js/components/file-manager/PaginationContainer.js";
import { fetchMediaFileManager } from "./api.js";
import { stateVars } from "./state.js";

export const handleRenderFiles = (files, pagination, elements) => {

    const { listView, paginationContainer } = elements;

    listView.innerHTML = '';

    if (!files?.length) {
        listView.insertAdjacentHTML('beforeend', filesNotFound());
        paginationContainer.innerHTML = '';
        return;
    }

    files.forEach(file => {
        listView.insertAdjacentHTML('beforeend', fileItemCard(file));
    });

    paginationContainer.innerHTML = paginationComponent(pagination.page, pagination.total_pages);
}

export const loadFiles = async(elements) => {

    const response = await fetchMediaFileManager(
        stateVars.currentPage,
        stateVars.perPage,
        stateVars.extensionFilter,
        stateVars.searchFileName
    );

    handleRenderFiles(
        response.data,
        response.pagination,
        elements
    );
};