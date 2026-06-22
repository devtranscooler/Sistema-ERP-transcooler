<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/Media.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/MediaRequest.php';

class ResourcesValidation
{
    public static function validation(int $requestingUserId, int $approverUserId, int $mediaId): array
    {
        $userModel = new User();
        $requestingUser = $userModel->findById($requestingUserId);

        if(!$requestingUser) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'El usuario solicitante no existe'
            ];
        }

        $approverUser = $userModel->findById($approverUserId);
        
        if(!$approverUser) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'El usuario aprobador no existe'
            ];
        }

        if(!$approverUser['email'] || empty($approverUser['email'])) {
            return [
                'code' => 422,
                'status' => false,
                'message' => 'El usuario aprobador no tiene cuenta de correo electrónico'
            ];
        }

        if (!filter_var($approverUser['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'code' => 422,
                'status' => false,
                'message' => 'El usuario aprobador no tiene una cuenta de correo valida'
            ];
        }

        $mediaModel = new Media();
        $mediaExists = $mediaModel->findById($mediaId);

        if(!$mediaExists) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'El id del archivo proporcionado no existe'
            ];
        }

        $mediaRequestModel = new MediaRequest();
        $mediaRequest = $mediaRequestModel->findByMediaId($mediaId);

        if($mediaRequest) {
            return [
                'code' => 401,
                'status' => false,
                'message' => "Ya existe una solicitud de permiso para este archivo con el estatus {$mediaRequest['estatus']}"
            ];
        }

        return [
            'status' => true,
            'data' => [
                'approver_user' => $approverUser,
                'requesting_user' => $requestingUser,
                'media_file' => $mediaExists
            ]
        ];
    }
}