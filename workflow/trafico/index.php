<div class="tab-content mt-2 p-2 border border-2 border-primary rounded bg-primary-subtle">
    <h2>Tráfico - Control de Flujo</h2>
    <p>Monitoreo del tráfico y flujo de trabajo.</p>

    <!-- Contenedor del mapa - aquí es donde Google Maps "dibujará" el mapa -->
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

<!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPTJ-KM1wA3sOZDXPuIt0Zfzw9_51xZn8&libraries=marker&callback=initMap"></script>-->
<script>
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

    let center = {
        lat: 19.61818379047129,
        lng: -99.16725370301855
    };


    async function getUnits() {
        try {
            const response = await fetch(URL_GEKO);

            if (!response.ok) {
                throw new Error("Error en la respuesta del servidor");
            }

            const data = await response.json();
            unidades = data[0].items; 
            //console.log("Unidades cargadas:", unidades);
        } catch (error) {
            console.error("Error al llamar la API:", error);
        }
    }


    async function initMap() {
        await getUnits(); //! ESPERAMOS A QUE LLEGUE LA API

        const {
            Map
        } = await google.maps.importLibrary("maps");
        const {
            AdvancedMarkerElement
        } = await google.maps.importLibrary("marker");

        map = new Map(document.getElementById("map"), {
            center,
            zoom: 6,
            mapId: "172b2970e28e4b885dc8a378", // requerido para AdvancedMarker
        });

        addMarkers(AdvancedMarkerElement);
    }

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
</script>