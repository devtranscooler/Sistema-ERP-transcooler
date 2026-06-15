export const optionPermissionUser = (user) => {
    return `<option value="${user.id}"> ${user.nombre_completo} </option>`
}