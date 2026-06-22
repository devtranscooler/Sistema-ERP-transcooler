<?php

class StatusRequestPermissionTemplate 
{
    public static function renderInvalidStatus(string $message): string
    {
        return "<!DOCTYPE html>
            <html lang='es'>
                <head>
                <meta charset='UTF-8'>
                <title>Estatus no válido</title>
                <style>

                    *{
                        box-sizing:border-box;
                    }

                    body{
                        margin:0;
                        padding:30px;
                        background:#F5F7FA;
                        font-family:Arial, Helvetica, sans-serif;
                    }

                    .container{
                        max-width:700px;
                        margin:auto;
                    }

                    .card{
                        background:white;
                        border-radius:12px;
                        overflow:hidden;
                        box-shadow:0 4px 20px rgba(0,0,0,.08);
                    }

                    .header{
                        background:#274DF5;
                        color:white;
                        padding:25px;
                        text-align: center;
                    }

                    .header h1{
                        margin:0;
                        font-size:24px;
                    }

                    .content{
                        padding:40px;
                        text-align:center;
                    }

                    .status-icon{
                        width:90px;
                        height:90px;
                        margin:auto;
                        border-radius:50%;
                        display:flex;
                        justify-content:center;
                        align-items:center;
                        font-size:42px;
                        font-weight:bold;
                    }

                    .warning{
                        background:#FFF4D6;
                        color:#F59F00;
                    }

                    .title{
                        margin-top:25px;
                        color:#212529;
                        font-size:28px;
                        font-weight:bold;
                    }

                    .description{
                        margin-top:15px;
                        color:#6C757D;
                        line-height:1.8;
                        font-size:16px;
                    }

                    .file-box{
                        margin-top:30px;
                        background:#F8F9FA;
                        border:1px solid #E9ECEF;
                        border-radius:8px;
                        padding:20px;
                    }

                    .file-label{
                        color:#6C757D;
                        font-size:14px;
                        margin-bottom:8px;
                    }

                    .file-name{
                        font-size:18px;
                        font-weight:bold;
                        color:#212529;
                    }

                    .footer{
                        background:#F8F9FA;
                        border-top:1px solid #E9ECEF;
                        padding:20px;
                        text-align:center;
                        color:#6C757D;
                        font-size:13px;
                    }

                    </style>

                    </head>
                    <body>
                        <div class='container'>
                            <div class='card'>
                                <div class='header'>
                                    <h1> Transcooler ERP </h1>
                                </div>
                            <div class='content'>
                                <div class='status-icon warning'>!</div>
                                <div class='title'> {$message} </div>
                                <div class='description'>
                                    La acción solicitada no es válida.
                                    Verifica que el enlace provenga del correo enviado por el sistema.
                                </div>
                                <div class='file-box'>
                                    <div class='file-label'> Motivo </div>
                                    <div class='file-name'>
                                        El estatus proporcionado no es permitido.
                                    </div>
                                </div>
                            </div>
                            <div class='footer'>
                                Esta acción fue registrada por Transcooler ERP.
                            </div>
                        </div>
                    </div>
                </body>
            </html>";
    }

    public static function renderInvalidToken(): string
    {
        return '
        <!DOCTYPE html>
            <html lang="es">
            <head>
            <meta charset="UTF-8">
            <title>Solicitud no encontrada</title>
            <style>

                *{
                    box-sizing:border-box;
                }

                body{
                    margin:0;
                    padding:30px;
                    background:#F5F7FA;
                    font-family:Arial, Helvetica, sans-serif;
                }

                .container{
                    max-width:700px;
                    margin:auto;
                }

                .card{
                    background:white;
                    border-radius:12px;
                    overflow:hidden;
                    box-shadow:0 4px 20px rgba(0,0,0,.08);
                }

                .header{
                    background:#274DF5;
                    color:white;
                    padding:25px;
                    text-align:center;
                }

                .header h1{
                    margin:0;
                    font-size:24px;
                }

                .content{
                    padding:40px;
                    text-align:center;
                }

                .status-icon{
                    width:90px;
                    height:90px;
                    margin:auto;
                    border-radius:50%;
                    display:flex;
                    justify-content:center;
                    align-items:center;
                    font-size:42px;
                    font-weight:bold;
                }

                .danger{
                    background:#FDEBEC;
                    color:#DC3545;
                }

                .title{
                    margin-top:25px;
                    color:#212529;
                    font-size:28px;
                    font-weight:bold;
                }

                .description{
                    margin-top:15px;
                    color:#6C757D;
                    line-height:1.8;
                    font-size:16px;
                }

                .file-box{
                    margin-top:30px;
                    background:#F8F9FA;
                    border:1px solid #E9ECEF;
                    border-radius:8px;
                    padding:20px;
                }

                .file-label{
                    color:#6C757D;
                    font-size:14px;
                    margin-bottom:8px;
                }

                .file-name{
                    font-size:18px;
                    font-weight:bold;
                    color:#212529;
                }

                .footer{
                    background:#F8F9FA;
                    border-top:1px solid #E9ECEF;
                    padding:20px;
                    text-align:center;
                    color:#6C757D;
                    font-size:13px;
                }

                </style>

                </head>
                <body>
                    <div class="container">
                        <div class="card">
                            <div class="header">
                                <h1>Transcooler ERP</h1>
                            </div>
                            <div class="content">
                                <div class="status-icon danger"> ✕ </div>
                                <div class="title">
                                    Solicitud no encontrada
                                </div>
                                <div class="description">
                                    No fue posible localizar la solicitud asociada al enlace proporcionado.
                                </div>
                                <div class="file-box">
                                    <div class="file-label"> Motivo </div>
                                    <div class="file-name">
                                        El token es inválido o ya no existe.
                                    </div>
                                </div>
                            </div>
                            <div class="footer">
                                Esta acción fue registrada por Transcooler ERP.
                            </div>
                        </div>
                    </div>
                </body>
            </html>';
    }

    public static function renderAnswer(string $status, string $fileName = 'S/I'): string
    {
        $title = $status === 'aprobado' ? 'Solicitud aprobada' : 'Solicitud Rechazada';
        $icon = $status === 'aprobado' ? '✓' : 'X';
        $classStatus = $status === 'aprobado' ? 'success' : 'danger';
        $message = $status === 'aprobado'
            ? 'Has aprobado correctamente la solicitud de descarga. El usuario ya puede descargar el archivo solicitado.' 
            : 'Has rechazado la solicitud de descarga. el usuario no podra descargar el archivo solicitdado';

        return  "<!DOCTYPE html>
        <html lang='es'>
            <head>
            <meta charset='UTF-8'>
            <title>Solicitud procesada</title>
            <style>

                *{
                    box-sizing:border-box;
                }

                body{
                    margin:0;
                    padding:30px;
                    background:#F5F7FA;
                    font-family:Arial, Helvetica, sans-serif;
                }

                .container{
                    max-width:700px;
                    margin:auto;
                }

                .card{
                    background:white;
                    border-radius:12px;
                    overflow:hidden;
                    box-shadow:0 4px 20px rgba(0,0,0,.08);
                }

                .header{
                    background:#274DF5;
                    color:white;
                    padding:25px;
                    text-align:center;
                }

                .header h1{
                    margin:0;
                    font-size:24px;
                }

                .content{
                    padding:40px;
                    text-align:center;
                }

                .status-icon{
                    width:90px;
                    height:90px;
                    margin:auto;
                    border-radius:50%;
                    display:flex;
                    justify-content:center;
                    align-items:center;
                    font-size:42px;
                    font-weight:bold;
                }

                .success{
                    background:#E8F8EE;
                    color:#198754;
                }

                .danger{
                    background:#FDEBEC;
                    color:#DC3545;
                }

                .warning{
                    background:#FFF4D6;
                    color:#F59F00;
                }

                .title{
                    margin-top:25px;
                    color:#212529;
                    font-size:28px;
                    font-weight:bold;
                }

                .description{
                    margin-top:15px;
                    color:#6C757D;
                    line-height:1.8;
                    font-size:16px;
                }

                .file-box{
                    margin-top:30px;
                    background:#F8F9FA;
                    border:1px solid #E9ECEF;
                    border-radius:8px;
                    padding:20px;
                }

                .file-label{
                    color:#6C757D;
                    font-size:14px;
                    margin-bottom:8px;
                }

                .file-name{
                    font-size:18px;
                    font-weight:bold;
                    color:#212529;
                }

                .footer{
                    background:#F8F9FA;
                    border-top:1px solid #E9ECEF;
                    padding:20px;
                    text-align:center;
                    color:#6C757D;
                    font-size:13px;
                }

                .btn{
                    display:inline-block;
                    margin-top:30px;
                    text-decoration:none;
                    background:#274DF5;
                    color:white;
                    padding:12px 24px;
                    border-radius:6px;
                    font-weight:bold;
                }

            </style>
            </head>
            <body>
                <div class='container'>
                    <div class='card'>
                    <div class='header'>
                        <h1>Transcooler ERP</h1>
                    </div>
                    <div class='content'>
                        <div class='status-icon {$classStatus}'>{$icon}</div>
                        <div class='title'>
                            {$title}
                        </div>
                        <div class='description'>
                            {$message}
                        </div>
                        <div class='file-box'>
                            <div class='file-label'>
                                Archivo
                            </div>
                            <div class='file-name'>
                                {$fileName}
                            </div>
                        </div>
                    </div>
                    <div class='footer'>
                        Esta acción fue registrada por Transcooler ERP.
                    </div>
                </div>
            </div>
        </body>
    </html>";
    
    }

    public static function error500(): string
    {
        return "<!DOCTYPE html>
            <html lang='es'>

            <head>
            <meta charset='UTF-8'>
            <title>Error interno del servidor</title>

            <style>

            *{
                box-sizing:border-box;
            }

            body{
                margin:0;
                padding:30px;
                background:#F5F7FA;
                font-family:Arial, Helvetica, sans-serif;
            }

            .container{
                max-width:700px;
                margin:auto;
            }

            .card{
                background:white;
                border-radius:12px;
                overflow:hidden;
                box-shadow:0 4px 20px rgba(0,0,0,.08);
            }

            .header{
                background:#274DF5;
                color:white;
                padding:25px;
            }

            .header h1{
                margin:0;
                font-size:24px;
            }

            .content{
                padding:40px;
                text-align:center;
            }

            .status-icon{
                width:90px;
                height:90px;
                margin:auto;
                border-radius:50%;
                display:flex;
                justify-content:center;
                align-items:center;
                font-size:42px;
                font-weight:bold;
            }

            .danger{
                background:#FDEBEC;
                color:#DC3545;
            }

            .title{
                margin-top:25px;
                color:#212529;
                font-size:28px;
                font-weight:bold;
            }

            .description{
                margin-top:15px;
                color:#6C757D;
                line-height:1.8;
                font-size:16px;
            }

            .file-box{
                margin-top:30px;
                background:#F8F9FA;
                border:1px solid #E9ECEF;
                border-radius:8px;
                padding:20px;
            }

            .file-label{
                color:#6C757D;
                font-size:14px;
                margin-bottom:8px;
            }

            .file-name{
                font-size:18px;
                font-weight:bold;
                color:#212529;
            }

            .footer{
                background:#F8F9FA;
                border-top:1px solid #E9ECEF;
                padding:20px;
                text-align:center;
                color:#6C757D;
                font-size:13px;
            }

            </style>

            </head>

            <body>

            <div class='container'>
                <div class='card'>

                    <div class='header'>
                        <h1>Transcooler ERP</h1>
                    </div>

                    <div class='content'>

                        <div class='status-icon danger'>⚠</div>

                        <div class='title'>
                            Error interno del servidor
                        </div>

                        <div class='description'>
                            Ocurrió un problema inesperado al procesar la solicitud.
                            Por favor intenta nuevamente más tarde o contacta al administrador del sistema.
                        </div>

                        <div class='file-box'>

                            <div class='file-label'>
                                Código de error
                            </div>

                            <div class='file-name'>
                                HTTP 500 - Internal Server Error
                            </div>
                        </div>
                    </div>
                <div class='footer'>
                    Esta acción fue registrada por Transcooler ERP.
                </div>

            </div>
        </div>
    </body>

    </html>";
    
    }
}
