<?php

class ReminderMailDTO
{

    public function __construct(
        public ?int $serviceId,
        public ?int $sendUserId,
        public ?int $recipientUserId,
        public ?string $comments
    ) {}

    public static function fromRequest(array $request): self
    {
        return new self(
            serviceId: isset($request['service_id']) ? (int) $request['service_id'] : null,
            sendUserId: isset($request['send_user_id']) ? (int) $request['send_user_id'] : null,
            recipientUserId: isset($request['recipient_user_id']) ? (int) $request['recipient_user_id'] : null,
            comments: $request['comments'] ?? null,
        );
    }
}