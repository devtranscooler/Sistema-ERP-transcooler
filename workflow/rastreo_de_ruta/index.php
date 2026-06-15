<?php
    $id_unidad = $_POST['id_unidad'] ?? null;
    $shipment = $_POST['shipment'] ?? null;
    $id_operador = $_POST['id_operador'] ?? null;
?>
<div class="tab-content mt-2 p-2 border border-2 border-primary rounded bg-primary-subtle">
    <h2>Rastreo de unidades</h2>

    <div class="row g-2" style="margin-bottom: 0.5rem;">
        <div class="col-md-4">
            <label class="form-label">
                Número económico
            </label>
            <div class="position-relative">
                <input type="text"
                    class="form-control"
                    id="eco_busqueda"
                    placeholder="Buscar unidad..."
                    autocomplete="off">    
                <div id="lista_unidades"
                    class="list-group shadow-sm"
                    style="position: fixed; z-index: 9999; min-width: 200px; display: none;"></div>
            </div>
            <input type="hidden" name="id_unidad" id="id_unidad" value="<?= $id_unidad ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">
                Shipment
            </label>

            <div class="position-relative">
                <input
                    type="text"
                    class="form-control"
                    id="shipment_busqueda"
                    placeholder="Buscar shipment..."
                    autocomplete="off">

                <div id="lista_shipments"
                    class="list-group shadow-sm"
                    style="position: fixed; z-index: 9999; min-width: 200px; display: none;">
                </div>
            </div>

            <input type="hidden" id="id_unidad_shipment">
        </div>
        <div class="col-md-4">
            <label class="form-label">
                Nombre del operador 
            </label>
            <div class="position-relative">
                <input type="text"
                    class="form-control"
                    id="operador_busqueda"
                    placeholder="Buscar operador..."
                    autocomplete="off">

                <div id="lista_operadores"
                    class="list-group shadow-sm"
                    style="position: fixed; z-index: 9999; min-width: 200px; display: none;"></div>
            </div>
            <input type="hidden" name="id_operador" id="id_operador" value="<?= $id_operador ?>">
        </div>
    </div>
    <div id="map"></div>
</div>

<style>
    /* Estilos para el contenedor del mapa */
    #map {
        width: 100%;
        height: 70vh;
        border-radius: 10px;
    }

    /* Responsive: en pantallas pequeñas, hacemos el mapa más bajito */
    @media (max-width: 768px) {
        #map {
            height: 300px;
        }
    }
</style>

<script>
    /* This code snippet is a self-invoking function that dynamically loads the Google Maps JavaScript
    API by creating a script element and appending it to the document head. Here is a breakdown of
    what the code is doing: */
    (g => {
        var h, a, k, p = "The Google Maps JavaScript API",
            c = "google",
            l = "importLibrary",
            q = "__ib__",
            m = document,
            b = window;
        b = b[c] || (b[c] = {});
        var d = b.maps || (b.maps = {}),
            r = new Set,
            e = new URLSearchParams,
            u = () => h || (h = new Promise(async (f, n) => {
                await (a = m.createElement("script"));
                e.set("libraries", [...r] + "");
                for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                e.set("callback", c + ".maps." + q);
                a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                d[q] = f;
                a.onerror = () => h = n(Error(p + " could not load."));
                a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                m.head.append(a)
            }));
        d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n))
    })({
        key: "AIzaSyBPTJ-KM1wA3sOZDXPuIt0Zfzw9_51xZn8",
        v: "weekly",
    });
</script>

<script>
    const URL_GEKO = "https://ws.geckotech.com.mx/api/get_devices?lang=es&user_api_hash=$2y$10$XMS86OQIbVLsSn9RFUchy.ztxdBa2AGuVIJqO9YtYK2pLonC3R8HO";

    let unidades = [];
    let map;
    let ecoSeleccionado = null;

    let center = {
        lat: 19.61818379047129,
        lng: -99.16725370301855
    };

    /* The `async function getUnits(eco = null) { ... }` function is responsible for fetching data from
    a specified API endpoint (`URL_GEKO`) asynchronously. Here is a breakdown of what the function
    does: */
    async function getUnits(eco = null) {
        try {
            const response = await fetch(URL_GEKO);

            if (!response.ok) {
                throw new Error("Error en la respuesta del servidor");
            }

            const data = await response.json();

            if (eco) {
                unidades = data[0].items; 
                unidades = filterByEco(unidades, eco);
            } else {
                unidades = data[0].items;           
            }

            /* console.log("Unidades cargadas:", unidades); */
        } catch (error) {
            console.error("Error al llamar la API:", error);
        }
    }

    /* The `async function initMap(eco = null) { ... }` function is responsible for initializing the
    Google Map on the webpage. Here is a breakdown of what the function does: */
    async function initMap(eco = null) {
        await getUnits(eco);

        const {
            Map
        } = await google.maps.importLibrary("maps");
        const {
            AdvancedMarkerElement
        } = await google.maps.importLibrary("marker");

        map = new Map(document.getElementById("map"), {
            center,
            zoom: 6,
            mapId: "172b2970e28e4b885dc8a378", 
        });

        addMarkers(AdvancedMarkerElement);
    }

    /**
    * The function `addMarkers` adds markers to a map for each unit with specified latitude and
    * longitude coordinates.
    * 
    * @return If there are no units to display, a warning message "No hay unidades para mostrar" will
    * be logged to the console, and the function will return without adding any markers.
    */
    function addMarkers(AdvancedMarkerElement) {

        if (!unidades.length) {
            console.warn("No hay unidades para mostrar");
            return;
        }

        unidades.forEach(unidad => {
            if (!unidad.lat || !unidad.lng) return;

            const icono = document.createElement("img");
            icono.src = "../../img/trailer.png";
            icono.style.width = "auto";
            icono.style.height = "35px";


            new AdvancedMarkerElement({
                map: map,
                position: {
                    lat: parseFloat(unidad.lat),
                    lng: parseFloat(unidad.lng)
                },
                content: icono,
                title: unidad.name || "Unidad"
                
            }); 
        });
    }

    initMap();

    /**
     * The `initBuscador` function in PHP initializes a search feature that dynamically fetches and
     * displays search results based on user input, allowing selection of items from a list with
     * optional callbacks.
     * 
     * @return The `initBuscador` function is being returned.
     */
    function initBuscador({
        inputId,
        listaId,
        hiddenId,
        action,
        labelKey,
        apiUrl,
        fetchAction,
        onSelect
    }) {
        const input = document.getElementById(inputId);
        const lista = document.getElementById(listaId);
        const inputHidden = document.getElementById(hiddenId);

        // Si algún elemento no existe en el DOM simplemente salimos (evita el crash)
        if (!input || !lista || !inputHidden) {
            console.warn(`initBuscador: no se encontró algún elemento (${inputId}, ${listaId}, ${hiddenId})`);
            return;
        }

        function posicionarLista() {
            const rect = input.getBoundingClientRect();
            lista.style.top = rect.bottom + 'px';
            lista.style.left = rect.left + 'px';
            lista.style.width = rect.width + 'px';
        }

        input.addEventListener('input', async function() {
            const valor = this.value.trim();

            inputHidden.value = '';

            if (valor === '') {

                lista.innerHTML = '';
                lista.style.display = 'none';
                
                if (ecoSeleccionado !== null) {
                    ecoSeleccionado = null;
                    initMap();
                }

                return;
            }

            if (valor.length < 2) {
                lista.style.display = 'none';
                lista.innerHTML = '';
                return;
            }

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `action=${action}&term=${encodeURIComponent(valor)}`
                });

                const result = await response.json();
                const data = result.data ?? [];

                lista.innerHTML = '';

                if (data.length === 0) {
                    lista.innerHTML = '<span class="list-group-item text-muted">Sin resultados</span>';
                } else {
                    data.forEach(item => {
                        const el = document.createElement('a');
                        el.classList.add('list-group-item', 'list-group-item-action');
                        el.textContent = item[labelKey];
                        el.href = '#';

                        el.addEventListener('click', (e) => {
                            e.preventDefault();
                            input.value = item[labelKey];
                            inputHidden.value = item.id;
                            lista.style.display = 'none';
                            lista.innerHTML = '';
                            if (onSelect) onSelect(item);
                        });

                        lista.appendChild(el);
                    });
                }

                posicionarLista();
                lista.style.display = 'block';

            } catch (err) {
                console.error(`Error en buscador [${action}]:`, err);
            }
        });

        document.querySelector('.modal')?.addEventListener('scroll', () => {
            if (lista.style.display !== 'none') posicionarLista();
        });

        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !lista.contains(e.target)) {
                lista.style.display = 'none';
                lista.innerHTML = '';
            }
        });
    }

    /* The `initBuscador` function is being called with an object as its parameter. This function seems
    to be setting up a search functionality for units based on the provided configuration options: */
    initBuscador({
        inputId: 'eco_busqueda',
        listaId: 'lista_unidades',
        hiddenId: 'id_unidad',
        action: 'buscar_unidades',
        fetchAction: 'find_unidad',
        labelKey: 'eco',
        apiUrl: '../unidades/unidades.api.php',

        onSelect: (item) => {
            ecoSeleccionado = item.eco;
            initMap(ecoSeleccionado);
        }
    });

    /* The above code is initializing a search functionality for shipments using a JavaScript function
    called `initBuscador`. It takes in several parameters such as input ID, list ID, hidden ID,
    action, API URL, label key, and a callback function `onSelect` that is triggered when a shipment
    item is selected. */
    initBuscador({
        inputId: 'shipment_busqueda',
        listaId: 'lista_shipments',
        hiddenId: 'id_unidad_shipment',
        action: 'buscar_shipments',
        apiUrl: './servicio_cliente/servicios.api.php',
        labelKey: 'shipment',

        onSelect: (item) => {
            if (item.tracking === 'En ruta'){
                ecoSeleccionado = item.eco;
                initMap(ecoSeleccionado);
            } else {
                Swal.fire({
                    icon: "info",
                    title: "¡Advertencia!",
                    text: "El sipment no pertenece a un servicio activo",
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        }
    });
    
    /* The above code is initializing a search functionality for a bus operator using the
    `initBuscador` function. It sets up parameters such as input field ID, list ID, hidden ID,
    actions for searching and fetching data, label key for displaying results, and API URL for
    making requests. */
    initBuscador({
        inputId: 'operador_busqueda',
        listaId: 'lista_operadores',
        hiddenId: 'id_operador',
        action: 'buscar_operadores',
        fetchAction: 'find_operador',
        labelKey: 'nombreOperador',
        apiUrl: '../usuarios/usuarios.api.php',

        onSelect: async (item) => {
            try {
                const response = await fetch('./servicio_cliente/servicios.api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `action=buscar_servicio_operador&id_operador=${item.id}`
                });

                const result = await response.json();
                if (result.data && result.data.eco) {
                    ecoSeleccionado = result.data.eco;
                    initMap(ecoSeleccionado);
                } else {
                    Swal.fire({
                        icon: "info",
                        title: "¡Advertencia!",
                        text: "El operador no pertenece a un servicio activo",
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            } catch (error) {
                console.error('Error al buscar servicio del operador:', error);
            }
        }
    });

    /* The `filterByEco` function takes an array of `units` and an `eco` value as parameters. It
    filters the `units` array based on the `eco` value provided. */
    const filterByEco = (units, eco) => {
        return units.filter(unit => {
            const parts = unit.name.split(' - ')
            const unitEco = parts[1]

            return unitEco === eco
        })
    }

</script>

