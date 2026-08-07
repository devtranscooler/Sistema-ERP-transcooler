<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/StageService.php';

class StageServiceController 
{
    public function index()
    {
        $stageModel = new StageService();
        $stages = $stageModel->all();

        if(!$stages || empty($stages)) {
            http_response_code(404);
            return ['message' => 'Stages not found'];
        }

        http_response_code(200);
        return [
            'data' => $stages
        ];
    }
}