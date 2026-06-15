<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/services/helpers/StringHelper.php';
class BuildRequestPermissionMail
{
    public static function build(array $dataRequestPermission, string $token): string
    {
        $approverUserName = $dataRequestPermission['approver_user']['nombre'] ?? 'Unknown';
        $requestingUserName = $dataRequestPermission['requesting_user']['nombre'] ?? 'Unknown';
        $fileName = $dataRequestPermission['media_file']['nombre_origen'] ?? 'Unknown';
        $urlToken = StringHelper::getUrlDoimanWithProtocol() . "/public/index.php/api/file-manager/solicitudes/{$token}";

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
        </head>

            <body style='margin:0; padding:30px; background-color:#F5F7FA; font-family:Arial, Helvetica, sans-serif;'>
                <div style='max-width:600px; margin:auto; background:#FFFFFF;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.05);'>
                    <div style='background:#274DF5; color:white;padding:20px 30px;text-align:center;'>
                        <h2 style='margin:0;'>
                            Transcooler ERP
                        </h2>
                    </div>

                    <div style='padding:30px;'>
                        <h2 style='margin-top:0;color:#212529;'> Hola, {$approverUserName} </h2>
                        <p style='color:#6C757D;line-height:1.6;'>
                            Tienes una nueva solicitud de descarga pendiente de aprobación.
                        </p>

                        <p style='color:#212529;line-height:1.6;'>
                            El usuario
                            <strong> {$requestingUserName} </strong>
                                solicita permiso para descargar el siguiente archivo:
                        </p>

                        <div style='background:#F8F9FA;border:1px solid #E9ECEF;border-radius:8px;padding:16px;margin:20px 0;'>

                        <div style='font-size:14px;color:#6C757D;margin-bottom:6px;'>
                            Archivo solicitado
                        </div>
                        <div style='font-size:18px;font-weight:bold;color:#212529;'> {$fileName} </div>
                    </div>

                    <p style='color:#6C757D;font-size:14px;line-height:1.6;'>
                        Si reconoces esta solicitud puedes aprobarla. En caso contrario, puedes rechazarla.
                    </p>

                    <div style='margin-top:30px;'>
                        <a href='{$urlToken}/aprobado' style='display:inline-block;background:#274DF5;color:white;text-decoration:none;padding:12px 24px;border-radius:6px;font-weight:bold;margin-right:10px;'>
                            Aprobar solicitud
                        </a>
                        <a href='{$urlToken}/rechazado' style='display:inline-block;background:#F1F3F5;color:#495057;text-decoration:none;padding:12px 24px;border-radius:6px;font-weight:bold;'>
                            Rechazar
                        </a>
                    </div>
                </div>

                <div style='background:#F8F9FA;padding:20px 30px;border-top:1px solid #E9ECEF;'>
                    <p style='margin:0;font-size:12px;color:#6C757D;'>
                        Este mensaje fue generado automáticamente por Transcooler ERP.
                    </p>
                </div>
            </div>
        </body>
    </html>";

    }
}