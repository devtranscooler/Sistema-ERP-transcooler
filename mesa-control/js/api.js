export const fetchConsoleControlServices = async(page = 1, perPage = 15, customerType = '') => {
    try {

        const urlDomain = window.location.origin;

        const params = new URLSearchParams();
        params.append('page', page);
        params.append('per_page', perPage);

        if (customerType) {
            params.append('tipo_cliente', customerType);
        }

        const url = `${urlDomain}/public/index.php/api/control-console?${params}`;

        const response = await fetch(url);

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message ?? 'Error al obtener servicios de unidad');
        }

        return data;
        
    } catch (error) {
        console.error(error)
    }
}

export const fetchConsoleControlServiceById = async(serviceId) => {
    try {

        const urlDomain = window.location.origin;

        const url = `${urlDomain}/public/index.php/api/control-console/${serviceId}`;

        const response = await fetch(url);

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message ?? 'Error al obtener informacion de servicio');
        }

        return data?.data ?? [];

        
    } catch (error) {
        console.error(error)
    }
}

export const fetchOperativeAdminUsers = async() => {
    try {

        const urlDomain = window.location.origin

        const response = await fetch(`${urlDomain}/public/index.php/api/users?rol=Administración operativa`)

        const data = await response.json()

        if(!response.ok) {
            throw new Error(data.message ?? 'Error al obtener usuarios')
        }

        return data.data
        
    } catch (error) {
        console.error(error)
    }
}

export const sendEmailReminder = async(formData) => {
    const urlDomain = window.location.origin

    const response = await fetch(`${urlDomain}/public/index.php/api/control-console/email`, {
        method: 'POST',
        body: formData
    }) 

    const data = await response.json()

    if(!response.ok) {
        throw new Error(data.message ?? 'Error al enviar el recordatorio');
    }

    return data
}