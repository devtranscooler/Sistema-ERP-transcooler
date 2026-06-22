export const paginationComponent = (currentPage, totalPages) => {

    let html = '';

    html += `
        <button
            class="btn btn-sm btn-outline-secondary mx-1 pagination-btn"
            data-page="${currentPage - 1}"
            ${currentPage === 1 ? 'disabled' : ''}
        >
            Anterior
        </button>
    `;

    for(let page = 1; page <= totalPages; page++) {

        html += `
            <button
                class="btn btn-sm mx-1 pagination-btn
                    ${page === currentPage
                        ? 'btn-primary'
                        : 'btn-outline-primary'}
                "
                data-page="${page}"
            >
                ${page}
            </button>
        `;
    }

    html += `
        <button
            class="btn btn-sm btn-outline-secondary mx-1 pagination-btn"
            data-page="${currentPage + 1}"
            ${currentPage === totalPages ? 'disabled' : ''}
        >
            Siguiente
        </button>
    `;

    return html;
};