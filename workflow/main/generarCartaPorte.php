<?php

    $servicio = json_decode($_POST['servicio'] ?? '{}', true);    

?>

    <div class="modal-header dar-salida-modal">
        <h5 class="modal-title">
            <i class="bi bi-filetype-pdf"></i> Carta porte
        </h5>
    </div>

    <div class="modal-body generar-carta-porte-modal">

        <div class="d-flex justify-content-end p-2">
            <button id="generate-pdf" class="btn btn-primary"> 
                <i class="bi bi-printer"></i> Imprimir 
            </button>
        </div>

        <div id="container-pdf" class="bg-secondary p-3 rounded" style="overflow-y: auto; max-height: 80vh;">
    
                
            <div class="pdf-page shadow-sm bg-white mx-auto rounded" id="hoja-1">
                
                <!-- LOGO, CARTA PORTE, FCCP, FECHA EXPEDICION -->
                <div class="d-flex justify-content-between gap-3 p-4">
                    <div>
                        <img src="/img/logo1.png" alt="Logo transccoler" class="img-fluid">
                    </div>
                    <div class="d-flex justify-content-between align-items-center gap-3 px-5 fw-bold">
                        <div class="row">
                            <div class="col-12">
                                <p class="m-0 small"> CARTA PORTE </p>
                                <p class="m-0 small"> FCCP-010826 </p>
                                <p class="m-0 small"> FECHA EXPEDICIÓN </p>
                                <p class="m-0 small"> <?= date('d-m-Y') ?> </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p class="m-0 small"> VIAJE </p>
                                <p class="m-0 small"> 16005 </p>
                                <p class="m-0 small"> PEDIDO </p>
                                <p class="m-0 small"> 17196 </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- TERMINA LOGO, CARTA PORTE, FCCP, FECHA EXPEDICION -->

                <!-- TRANSPORTISTA  Y CLIENTE -->
                <div class="row g-0 mt-4 px-3">
                    <div class="col-12 p-0 mt-1">
                        <div class="row mt-1 px-3 d-flex align-items-stretch">
                            
                            <div class="col-6 d-flex flex-column">
                                <p class="text-start fw-bold mb-1"> TRANSPORTISTA </p>
                                <div class="border p-1 h-100 small">
                                    <p class="m-0"> Transccoler, S.A. de C.V. </p>
                                    <p class="m-0"> Melchor Ocampo No. 1 </p>
                                    <p class="m-0"> Mariano Escobedo </p>
                                    <p class="mt-1 m-0"> Tultitlán Estado de México C.P. 54946 </p>
                                    <p class="m-0"> RFC: TRA-010502-MA5 </p>
                                    <p class="m-0"> Tel. 5382 1999 / 5382 1853 </p>
                                </div>
                            </div>

                            <div class="col-6 d-flex flex-column">
                                <p class="text-start fw-bold mb-1"> CLIENTE </p>
                                <div class="border p-1 h-100 small">
                                    <p class="m-0"> <?= $servicio['nombre_cliente'] ? strtoupper($servicio['nombre_cliente']) : 'S/D' ?> </p>
                                    <p class="m-0"> <?= $servicio['direccion_cliente'] ?? 'S/D' ?> </p>
                                    <p class="mt-3 m-0"> Querétaro, QUE </p>
                                    <p class="m-0"> <?= $servicio['rfc_cliente'] ?? 'S/D' ?> </p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- TERMINA TRANSPORTISTA  Y CLIENTE -->

                <!-- ORIGEN  Y DESTINO -->
                <div class="row g-0 my-4 px-3">
                    <div class="col-12 p-0 mt-1">
                        <div class="row mt-1 px-3 d-flex align-items-stretch">
                            
                            <div class="col-6 d-flex flex-column">
                                <p class="text-start fw-bold mb-1"> ORIGEN </p>
                                <div class="border p-1 h-100 small">
                                    <p class="m-0"> Pilgrim's Tepeji </p>
                                    <p class="m-0"> <?= $servicio['origin_route_address']['origen_inicio'] ?? 'S/D' ?> </p>
                                    <p class="mt-1 m-0"> Tepeji del Río de Ocampo, HGO </p>
                                    <p class="m-0"> PPR910701LEA </p>
                                    <p class="m-0"> RECOGER EN: </p>
                                </div>
                            </div>

                            <div class="col-6 d-flex flex-column">
                                <p class="text-start fw-bold mb-1"> DESTINO </p>
                                <div class="border p-1 h-100 small">
                                    <p class="m-0"> Cedis Pilgrims Tepotzotlan </p>
                                    <p class="m-0"> <?= $servicio['destination_route_address']['destino_final'] ?? 'S/D' ?> </p>
                                    <p class="mt-1 m-0"> Tepotzotlán, MEX </p>
                                    <p class="m-0"> XAXX010101000 </p>
                                    <p class="m-0"> ENTREGAR EN: </p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- TERMINA ORIGEN Y DESTINO -->

                <!-- CANTIDAD, PRESENTACION, DESCRIPCION, PESO, TARIFA -->
                <div class="col-12 px-3 mt-2 fw-bold">
                    <div class="container">
                        <div class="row row-cols-5 mt-2">
                            <div class="col">
                                <p class="border-bottom"> CANTIDAD: </p>
                            </div>
                            <div class="col">
                                <p class="border-bottom"> PRESENTACIÓN: </p>
                            </div>
                            <div class="col">
                                <p class="border-bottom"> DESCRIPCIÓN: </p>
                            </div>
                            <div class="col">
                                <p class="border-bottom"> PESO: </p>
                            </div>
                            <div class="col">
                                <p class="border-bottom"> TARIFA: </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 px-3 mt-2">
                    <div class="container">
                        <div class="row row-cols-5 mt-2">
                            <div class="col">
                                <p></p>
                            </div>
                            <div class="col">
                                <p></p>
                            </div>
                            <div class="col">
                                <p></p>
                            </div>
                            <div class="col">
                                <p> <?= $servicio['capacidad'] ? strtoupper($servicio['capacidad']) : 'S/D' ?> </p>
                            </div>
                            <div class="col">
                                <p> FLETE $1.00 </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- TERMINA CANTIDAD, PRESENTACION, DESCRIPCION, PESO, TARIFA -->

                <!-- TEXTO TRANSPORTA PRODUCTO -->
                <div class="row p-3">
                    <p>  TRANSPORTA PRODUCTO DE LA COMPAÑIA (PRODUCTO PERECEDERO REFRIGERADO) </p>
                </div>
                <!-- TERMINA TEXTO TRANSPORTA PRODUCTO -->

                <!-- VALOR DECLARADO, REFERENCIA, IMPORTE CON LETRA, OPERADOR, ETC -->
                <div class="container my-2 px-3">
                    <div class="row align-items-start mb-1 border-bottom">
                        <div class="col-md-6">
                            <span class="data-label fw-bold"> VALOR DECLARADO: </span>
                            <span class="data-value"> N/D </span>
                        </div>
                        <div class="col-md-6 text-start">
                            <span class="data-label fw-bold"> REFERENCIA: </span>
                            <span class="data-value text-muted small"> FR21-0000164735 </span>
                        </div>
                    </div>
                    <div class="sub-divider"></div>

                    <div class="row mb-1">
                        <div class="col-md-12">
                            <div class="row mb-1">
                                <div class="col-md-4">
                                    <span class="data-label fw-bold"> LÍNEA: </span>
                                </div>
                                <div class="col-md-8 text-md-end">
                                    <span class="data-label fw-bold"> OPERADOR: </span>
                                    <span class="data-value"> <?= $servicio['nombre_operador'] ?? 'S/D' ?> </span>
                                </div>
                            </div>
                            <div class="sub-divider"></div>
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <div>
                                        <span class="data-label fw-bold"> TRACTOR: </span>
                                        <span class="data-value"> 388 </span>
                                    </div>
                                    <div>
                                        <span class="data-label fw-bold"> PLACAS: </span>
                                        <span class="data-value"> <?= $servicio['placas_unidad'] ?? 'S/D' ?> </span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-1 text-md-end">
                                    <div>
                                        <span class="data-label fw-bold"> REM1: </span>
                                    </div>
                                    <div>
                                        <span class="data-label fw-bold"> PLACAS: </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div>
                                        <span class="data-label fw-bold"> REM2: </span>
                                    </div>
                                    <div>
                                        <span class="data-label fw-bold"> PLACAS: </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-end mt-3">
                        <div class="d-flex justify-content-end ps-3 text-end">
                            <div class="d-flex justify-content-between align-items-center">
                                <p> SUBTOTAL </p>
                                <P> $1.00 </P>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end ps-3 text-end">
                            <div class="d-flex justify-content-between align-items-center">
                                <p> IVA 16% </p>
                                <P> $0.16 </P>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end ps-3 text-end">
                            <div class="d-flex justify-content-between align-items-center">
                                <p> RETENCIÓN 4% </p>
                                <P> $0.04 </P>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold"> IMPORTE CON LETRA: (*** UN PESO 12/100 M.N. ***) </p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="fw-bold"> TOTAL A PAGAR </p>
                                <p> $1.12 </p>
                            </div>
                        </div>
                    </div>

                    <div class="sub-divider"></div>

                    <div class="row align-items-start mb-1">
                        <div class="col-md-8">
                            <div class="mb-1">
                                <span class="data-label"> OBSERVACIONES: </span>
                            </div>
                            <div class="text-muted small"> FR21-0000164735 </div>
                        </div>
                        <div class="col-md-4 ps-3 text-end">
                            <div class="row mb-1">
                                <div class="col-12 text-start">
                                    <span class="data-label"> CITA DE CARGA: </span>
                                </div>
                            </div>
                            <div class="row mb-1 text-muted small">
                                <div class="col-12 text-start"> 27 DE ABRIL A LAS 11:30 </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-12 text-start">
                                    <span class="data-label"> CITA DE ENTREGA: </span>
                                </div>
                            </div>
                            <div class="row text-muted small">
                                <div class="col-12 text-start"> 27 DE ABRIL A LAS 12:45 </div>
                            </div>
                        </div>
                    </div>
                    <div class="section-divider"></div>

                    <div class="row mt-3 text-center small text-muted">
                        <div class="col p-2">
                            <div class="data-label">NOMBRE:</div>
                        </div>
                        <div class="col p-2">
                            <div class="data-label">DEPENDENCIA:</div>
                        </div>
                        <div class="col p-2">
                            <div class="data-label">TELÉFONO:</div>
                        </div>
                        <div class="col p-2">
                            <div class="data-label">SELLO 1:</div>
                        </div>
                        <div class="col p-2">
                            <div class="data-label">SELLO 2:</div>
                        </div>
                    </div>

                </div>
                <!-- TERMINA VALOR DECLARADO, REFERENCIA, IMPORTE CON LETRA, OPERADOR, ETC -->
                
            </div>
            
        </div>

    </div>

    <div class="modal-footer generar-carta-porte-modal">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="close-modal">
            <i class="bi bi-x-circle me-1"></i> Cerrar
        </button>
    </div>

    <script>
        

        (function () {
            
            document.getElementById("generate-pdf").addEventListener("click", () => {
                const contenido = document.getElementById("container-pdf");

                // Guarda estilos originales
                const originalHeight = contenido.style.maxHeight;
                const originalOverflow = contenido.style.overflow;

                // Quita límites
                contenido.style.maxHeight = "none";
                contenido.style.overflow = "visible";
                contenido.classList.remove("bg-secondary","rounded")

                generarPDF()
            });

            async function generarPDF() { 

                const { jsPDF } = window.jspdf; 
                const contenido = document.getElementById("container-pdf"); 
                const canvas = await html2canvas(contenido, 
                { 
                    scale: 3 // mejora calidad 
                }); 
                
                const imgData = canvas.toDataURL("image/jpeg"); 
                const pdf = new jsPDF("p", "mm", "letter"); 
                const imgWidth = 210; 
                const pageHeight = 295; 
                const imgHeight = (canvas.height * imgWidth) / canvas.width; 
                let heightLeft = imgHeight; 
                let position = 0; 
                
                pdf.addImage(imgData, "PNG", 0, position, imgWidth, imgHeight); 
                heightLeft -= pageHeight; 
                
                while (heightLeft > 0) { 
                    position = heightLeft - imgHeight; 
                    pdf.addPage(); 
                    pdf.addImage(imgData, "PNG", 0, position, imgWidth, imgHeight); 
                    heightLeft -= pageHeight; 
                } 

                const blob = pdf.output("blob");
                const url = URL.createObjectURL(blob);

                window.open(url, "_blank");
            }

        })();

    </script>