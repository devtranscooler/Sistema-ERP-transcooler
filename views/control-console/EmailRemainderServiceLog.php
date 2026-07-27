<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/DTO/control-console/ReminderMailDTO.php';

class EmailRemainderServiceLog
{
    public static function build(array $stageServiceLog, ReminderMailDTO $reminderMailDTO): string
    {
        return "
        <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>

                <style>
                    body{
                        margin:0;
                        padding:0;
                        background:#f4f6f9;
                        font-family:Arial, Helvetica, sans-serif;
                        color:#343a40;
                    }

                    .container{
                        width:100%;
                        padding:40px 0;
                    }

                    .card{
                        width:700px;
                        margin:0 auto;
                        background:#ffffff;
                        border-radius:8px;
                        overflow:hidden;
                        border:1px solid #e9ecef;
                    }

                    .header{
                        background:#0d6efd;
                        color:#ffffff;
                        padding:25px;
                    }

                    .header h2{
                        margin:0;
                        font-size:24px;
                    }

                    .header p{
                        margin-top:8px;
                        margin-bottom:0;
                        opacity:.9;
                    }

                    .content{
                        padding:30px;
                    }

                    .alert{
                        background:#fff3cd;
                        border-left:5px solid #ffc107;
                        color:#664d03;
                        padding:15px;
                        margin-bottom:25px;
                        border-radius:4px;
                    }

                    .section-title{
                        font-size:16px;
                        font-weight:bold;
                        color:#0d6efd;
                        margin-bottom:12px;
                        margin-top:30px;
                    }

                    table{
                        width:100%;
                        border-collapse:collapse;
                    }

                    td{
                        padding:10px;
                        border-bottom:1px solid #ececec;
                    }

                    td:first-child{
                        width:35%;
                        font-weight:bold;
                        background:#f8f9fa;
                    }

                    .badge{
                        display:inline-block;
                        padding:6px 12px;
                        border-radius:20px;
                        color:#fff;
                        font-size:13px;
                        font-weight:bold;
                    }

                    .badge-primary{
                        background:#0d6efd;
                    }

                    .badge-success{
                        background:#198754;
                    }

                    .comments{
                        background:#f8f9fa;
                        border-left:4px solid #0d6efd;
                        padding:15px;
                        border-radius:4px;
                        line-height:1.6;
                    }

                    .footer{
                        text-align:center;
                        font-size:12px;
                        color:#6c757d;
                        padding:20px;
                        background:#f8f9fa;
                    }

                    @media only screen and (max-width:720px){

                        .card{
                            width:95% !important;
                        }

                    }

                </style>

            </head>
            <body>
                <div class='container'>
                    <div class='card'>

                        <div class='header'>
                            <h2> Seguimiento de Servicio </h2>
                            <p> Recordatorio de seguimiento. </p>
                        </div>

                        <div class='content'>

                            <p>
                                Hola <strong> {$stageServiceLog['user_recipient']['nombre']} </strong>,
                            </p>

                            <p>
                                El siguiente servicio continúa en proceso y requiere seguimiento.
                            </p>


                            <div class='alert'>
                                Este servicio permanece en su estado actual desde hace
                                <strong> {$stageServiceLog['service'][0]['dias_transcurridos_desde_descarga']} días</strong>
                                posteriores a la fecha de descarga.
                            </div>


                            <div class='section-title'>
                                Información del servicio
                            </div>

                            <table>
                                <tr>
                                    <td>Servicio</td>
                                    <td>#{$stageServiceLog['service'][0]['servicio_id']}</td>
                                </tr>
                                <tr>
                                    <td>Unidad</td>
                                    <td>{$stageServiceLog['service'][0]['tipo_unidad']}</td>
                                </tr>
                                <tr>
                                    <td>Placas</td>
                                    <td>{$stageServiceLog['service'][0]['placas_unidad']}</td>
                                </tr>
                                <tr>
                                    <td>Eco</td>
                                    <td>{$stageServiceLog['service'][0]['eco']}</td>
                                </tr>
                                <tr>
                                    <td>Operador</td>
                                    <td>{$stageServiceLog['service'][0]['nombre_operador']}</td>
                                </tr>
                            </table>


                            <div class='section-title'>
                                Estado actual
                            </div>

                            <table>
                                <tr>
                                    <td>Etapa</td>
                                    <td>
                                        <span class='badge badge-primary'>
                                            {$stageServiceLog['service'][0]['nombre_etapa_servicio']}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Estatus</td>
                                    <td>
                                        <span class='badge badge-success'>
                                            {$stageServiceLog['service'][0]['nombre_estatus_etapa_servicio']}
                                        </span>
                                    </td>
                                </tr>
                            </table>


                            <div class='section-title'>
                                Comentarios
                            </div>

                            <div class='comments'>
                                {$reminderMailDTO->comments}
                            </div>

                            <p style='margin-top:35px; line-height:1.7;'>
                                Te recomendamos revisar el estado del servicio y realizar las acciones
                                correspondientes para mantener el flujo operativo.
                            </p>

                        </div>

                        <div class='footer'>
                            Este correo fue generado automáticamente por el Sistema de Monitoreo de Servicios.<br>
                            Por favor, no respondas a este mensaje.
                        </div>
                    </div>
                </div>
            </body>
        </html>
        ";
    }

}