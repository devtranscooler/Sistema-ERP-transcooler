<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/views/control-console/EmailRemainderServiceLog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/mail/MailService.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/DTO/control-console/ReminderMailDTO.php';

class SendRemainderServiceLogMail
{
    public static function send(array $stageServiceLog, ReminderMailDTO $reminderMailDTO): void
    {
        MailService::send(
            $stageServiceLog['user_recipient']['email'],
            EmailRemainderServiceLog::build($stageServiceLog, $reminderMailDTO),
            'Recordatorio de seguimiento de servicio',
        );
    }
}