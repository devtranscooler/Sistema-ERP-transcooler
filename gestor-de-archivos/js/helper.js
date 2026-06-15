export const getFilePreview = (file) => {

    const extension = file.name.split('.').pop().toLowerCase();

    const imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    if (imageExtensions.includes(extension)) {

        return {
            type: 'image'
        };
    }

    const icons = {
        pdf: {
            icon: 'bi-file-earmark-pdf',
            color: 'text-danger'
        },
        xls: {
            icon: 'bi-file-earmark-excel',
            color: 'text-success'
        },
        xlsx: {
            icon: 'bi-file-earmark-excel',
            color: 'text-success'
        },
        csv: {
            icon: 'bi-file-earmark-excel',
            color: 'text-success'
        },
        doc: {
            icon: 'bi-file-earmark-word',
            color: 'text-primary'
        },
        docx: {
            icon: 'bi-file-earmark-word',
            color: 'text-primary'
        }
    };

    return {
        type: 'icon',
        icon: icons[extension]?.icon ?? 'bi-file-earmark',
        color: icons[extension]?.color ?? 'text-secondary'
    };
};

export const getIconExtension = (fileExtension) => {

    const imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    if (imageExtensions.includes(fileExtension)) {

        return {
            type: 'image'
        };
    }

    const icons = {
        pdf: {
            icon: 'bi-file-earmark-pdf',
            color: 'text-danger'
        },
        xls: {
            icon: 'bi-file-earmark-excel',
            color: 'text-success'
        },
        xlsx: {
            icon: 'bi-file-earmark-excel',
            color: 'text-success'
        },
        csv: {
            icon: 'bi-file-earmark-excel',
            color: 'text-success'
        },
        doc: {
            icon: 'bi-file-earmark-word',
            color: 'text-primary'
        },
        docx: {
            icon: 'bi-file-earmark-word',
            color: 'text-primary'
        }
    };

    return {
        type: 'icon',
        icon: icons[fileExtension]?.icon ?? 'bi-file-earmark',
        color: icons[fileExtension]?.color ?? 'text-secondary'
    };
}

export const updateDropdownText = (item) => {

    const btn = document.getElementById("btnFileExtensionFilter");

    btn.innerHTML = `<i class="bi bi-funnel"></i> ${item.textContent.trim()}`;

    document.querySelectorAll("#dropdownFileExtensionFilter .dropdown-item").forEach(el => el.classList.remove("active"));

    item.classList.add("active");
}
