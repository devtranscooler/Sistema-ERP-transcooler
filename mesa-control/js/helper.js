export const updateCustomerTypeDropdownText = (item) => {

    const btn = document.getElementById("btnFilterCustomerType");

    btn.innerHTML = `<i class="bi bi-funnel"></i> ${item.textContent.trim()}`;

    document.querySelectorAll("#filterCustomerType .dropdown-item-customer-type").forEach(el => el.classList.remove("active"));

    item.classList.add("active");
}