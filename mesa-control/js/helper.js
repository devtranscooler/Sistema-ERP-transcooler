export const updateCustomerTypeDropdownText = (item) => {

    const btn = document.getElementById("btnFilterCustomerType");

    btn.innerHTML = `<i class="bi bi-funnel"></i> ${item.textContent.trim()}`;

    document.querySelectorAll("#filterCustomerType .dropdown-item-customer-type").forEach(el => el.classList.remove("active"));

    item.classList.add("active");
}

export const updateUnitTypeDropdownText = (item) => {
    
    const btn = document.getElementById("btnFiltroEstatusMesaControl");
    btn.innerHTML = `<i class="bi bi-truck"></i> ${item.textContent.trim()}`;
    document.querySelectorAll("#dropdownFiltroEstatusMesaControl .dropdown-item").forEach(el => el.classList.remove("active"));
    item.classList.add("active");
}

export const renderUnitTypeOptions = (unitTypes) => {
    const container = document.getElementById('dropdownFiltroEstatusMesaControl');

    unitTypes.forEach(type => {
        container.insertAdjacentHTML('beforeend', `
            <li>
                <a class="dropdown-item rounded" href="#" data-unit-type="${type.tipo_unidad}">
                    ${type.tipo_unidad}
                </a>
            </li>
        `);
    });
}

