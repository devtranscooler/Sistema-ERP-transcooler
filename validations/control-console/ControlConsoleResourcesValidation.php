<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/Service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/ControlConsole.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/DTO/control-console/ReminderMailDTO.php';

class ControlConsoleResourcesValidation
{
    public static function validation(ReminderMailDTO $reminderMailDTO): array
    {
        $controlConsoleModel = new ControlConsole();
        $service = $controlConsoleModel->getLastServiceStageByServiceId($reminderMailDTO->serviceId);

        if(!$service) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'El servicio solicitado no existe'
            ];
        }

        $userSendModel = new User();
        $userSend = $userSendModel->findById($reminderMailDTO->sendUserId);

        if(!$userSend) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'El usuario emisor no existe'
            ];
        }

        $userRecipientModel = new User();
        $userRecipient = $userRecipientModel->findById($reminderMailDTO->recipientUserId);

        if(!$userRecipient) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'El usuario receptor no existe'
            ];
        }

        return [
            'status' => true,
            'data' => [
                'service' => $service,
                'user_send' => $userSend,
                'user_recipient' => $userRecipient
            ]
        ];
    }
}