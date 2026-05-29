    <?php

        $servicio = json_decode($_POST['servicio'] ?? '{}', true);  

    ?>
    
    <div class="modal-header">
        <h5 class="modal-title">
            <i class="bi bi-arrow-left-right"></i> Reasignar reparto
        </h5>
    </div>

    <div class="modal-body">
        <form>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label"> Motivo de reasignación </label>
                <select id="id_estatus_reasignacion" name="id_estatus_reasignacion" class="form-select" aria-label="Default select example">
                    <option selected> Selecciona una opción </option>
                </select>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <label for="id_operador_origen" class="form-label"> Repartidor actual </label>
                    <input 
                        id="id_operador_origen"
                        name="id_operador_origen"
                        class="form-control" 
                        type="text" 
                        value="<?= $servicio['nombreOperador'] ?? null ?>" 
                        aria-label="readonly input example" 
                        disabled>
                </div>
                <div class="col-12 col-md-6 position-relative">
                    <label for="id_operador_search" class="form-label"> Repartidor destino </label>
                    <input 
                        id="id_operador_search"
                        name="id_operador_search"
                        class="form-control" 
                        type="search"
                        autocomplete="off">
                    <input
                        type="hidden"
                        id="id_operador_destino"
                        name="id_operador_destino">
                    <div id="results_operators" class="position-absolute bg-white w-100 shadow rounded z-3"></div>
                </div>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label"> Nota </label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
        </form>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger rounded-pill" data-bs-dismiss="modal" id="close-modal">
            Cerrar
        </button>
        <button type="button" id="handleSubmit" class="btn btn-primary rounded-pill">
            Guardar
        </button>
    </div>

    <script>
        

        (function () {

            const url = window.location.origin
            const statusReassignmentSelect = document.getElementById("id_estatus_reasignacion")
            const destinationOperator = document.getElementById("id_operador_search")
            const resultOperatorsFound = document.getElementById("results_operators")
            let operatorDestination = document.getElementById("id_operador_destino")

            let timeoutId;
            
            const fetchStatusReassignment = async() => {
                try {

                    const response = await fetch(`${url}/public/index.php/api/reasignacion-repartos`)

                    const data = await response.json();

                    if(response.ok){
                        selectStatusOptions(data.data)
                    }

                    return null
                    
                } catch (error) {
                    console.error(error)
                }
            }

            const fetchOperatorTypeUsers = async(operatorName) => {

                const urlParams = new URL(`${url}/public/index.php/api/operators`);
                urlParams.searchParams.set('nombre', operatorName);

                try {

                    const response = await fetch(urlParams)
                    const data = await response.json();

                    if(response.ok){
                        return data.data
                    }

                    return null
                    
                } catch (error) {
                    console.error(error)
                }
            }

            const selectStatusOptions = (data) => {

                statusReassignmentSelect.innerHTML = "";

                const html = data.map(option => {
                    return `<option value="${option.id}"> ${option.nombre} </option>`
                }).join("")

                return statusReassignmentSelect.innerHTML = html
            }
            
            destinationOperator.addEventListener("input", (event) => {
                
                clearTimeout(timeoutId);

                document.getElementById("id_operador_destino").value = "";

                // Esperamos 500 ms de inactividad antes de buscar
                timeoutId = setTimeout(async() => {
                    const searchText = event.target.value.trim();

                    if (!searchText) {
                        resultOperatorsFound.innerHTML = "";
                        return;
                    }
                    
                    const operators = await fetchOperatorTypeUsers(searchText)
                    displayOperatorResults(operators)
                }, 500);
            })

            const displayOperatorResults = (operators) => {
                if (!operators?.length) {
                    resultOperatorsFound.innerHTML = `
                        <div class="list-group-item">
                            No se encontraron operadores
                        </div>
                    `;
                    return;
                }

                let html = "";

                operators.forEach(operator => {
                    html += `
                        <button
                            type="button"
                            class="list-group-item list-group-item-action operator-item"
                            data-id="${operator.id}"
                            data-name="${operator.nombre_completo}">
                                ${operator.nombre_completo}
                        </button>
                    `;
                });

                resultOperatorsFound.innerHTML = `
                    <div class="list-group">
                        ${html}
                    </div>
                `;
            }

            resultOperatorsFound.addEventListener("click", (event) => {

                const item = event.target.closest(".operator-item");

                if (!item) return;

                const operatorId = item.dataset.id;
                const operatorName = item.dataset.name;

                destinationOperator.value = operatorName;

                document.getElementById("id_operador_destino").value = operatorId;

                resultOperatorsFound.innerHTML = "";

            });


            handleSubmit.addEventListener("click", (event) => {
                event.preventDefault();

                if(isNaN(statusReassignmentSelect.value) || !statusReassignmentSelect.value) {
                    alert("Debes seleccionar un motivo de reasignación")
                    return
                }
                
                if(operatorDestination.value === "" || !operatorDestination.value || isNaN(operatorDestination.value)) {
                    alert("Debes agregar un operador");
                    return
                }

                console.log(operatorDestination.value)
            })

        })();

    </script>