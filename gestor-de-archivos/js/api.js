export const fetchMediaFileManager = async(page = 1, perPage = 15, extension = '', nombreOrigen = '') => {
    try {
    
        const urlDomain = window.location.origin;

        const params = new URLSearchParams();
        params.append('page', page);
        params.append('per_page', perPage);

        if (extension) {
            params.append('extension', extension);
        }

        if(nombreOrigen) {
            params.append('nombre_origen', nombreOrigen)
        }

        const url = `${urlDomain}/public/index.php/api/file-manager?${params}`;

        const response = await fetch(url);

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message ?? 'Error al obtener archivos');
        }

        return data;
        
    } catch (error) {        
        console.error(error)
        return {
            data: [],
            pagination: {
                page: 1,
                total_pages: 0
            }
        };
    }
}


export const fetchUsersAdmin = async() => {
    try {

        const urlDomain = window.location.origin

        const response = await fetch(`${urlDomain}/public/index.php/api/users?rol=Administrador`)

        const data = await response.json()

        if(!response.ok) {
            throw new Error(data.message ?? 'Error al obtener usuarios')
        }

        return data.data
        
    } catch (error) {
        console.error(error)
    }
}

export const fetchFileDetail = async(fileId) => {
    try {

        const urlDomain = window.location.origin

        const response = await fetch(`${urlDomain}/public/index.php/api/file-manager/${fileId}`)
        
        const data = await response.json()

        if(!response.ok) {
            throw new Error(data.message ?? 'Error al obtener informacion de archivo')
        }

        return data.data
        
    } catch (error) {
        console.error(error)
    }
}

export const uploadManagerFile = async(formData) => {
    try {

        const urlDomain = window.location.origin

        const response = await fetch(`${urlDomain}/public/index.php/api/media`, {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if(!response.ok) {
            throw new Error("Error al guardar tu archivo");
        }

        return data.data
        
    } catch (error) {
        console.error(error)
    }
}

export const sendRequestPermission = async(formData) => {
    const urlDomain = window.location.origin

    const response = await fetch(`${urlDomain}/public/index.php/api/file-manager`, {
        method: 'POST',
        body: formData
    }) 

    const data = await response.json()

    if(!response.ok) {
        throw new Error(data.message ?? 'Error al enviar la solicitud');
    }

    return data
}

export const deletefile = async(fileId) => {
    try {

        const urlDomain = window.location.origin

        const response = await fetch(`${urlDomain}/public/index.php/api/media/${fileId}`, {
            method: 'DELETE'
        })

        const data = await response.json()

        if(!response.ok) {
            throw new Error(data.message ?? 'Error al eliminar archivo')
        }

        return data.message
        
    } catch (error) {
        console.error(error)
    }
}