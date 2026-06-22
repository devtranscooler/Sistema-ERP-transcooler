<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/mails/file-manager/BuildRequestPermissionMail.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/mail/MailService.php';

class SendRequestPermissionMail
{
    public static function send(array $dataRequestPermission, string $token): void
    {
        MailService::send(
            $dataRequestPermission['approver_user']['email'],
            BuildRequestPermissionMail::build(
                $dataRequestPermission,
                $token
            )
        );
    }
}