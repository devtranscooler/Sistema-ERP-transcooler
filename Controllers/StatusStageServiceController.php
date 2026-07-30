<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/StatusStageService.php';
class StatusStageServiceController
{
    public function getStatusStageById(int $stageId)
    {
        $statusStageModel = new StatusStageService();
        $statuses = $statusStageModel->getStatusById($stageId);

        if(!$statuses || empty($statuses)) {
            http_response_code(404);
            return ['message' => 'Statuses stage not found'];
        }

        http_response_code(200);
        return [
            'data' => $statuses
        ];
    }
}