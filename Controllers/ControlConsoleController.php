<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/ControlConsole.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/DTO/control-console/ReminderMailDTO.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/validations/control-console/ControlConsoleResourcesValidation.php';
require_once $_SERVER['DOCUMENT_ROOT']. '/mails/control-console/SendRemainderServiceLogMail.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/validations/control-console/ControlConsoleEmailRequestValidation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/StageServiceEmailLog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/Service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/StageService.php';


class ControlConsoleController
{
    public function index()
    {
        $filters = [
            'tipo_unidad' => $_GET['tipo_unidad'] ?? null,
            'tipo_cliente' => $_GET['tipo_cliente'] ?? null,
        ];

        $pagination = [
            'page' => (int)($_GET['page'] ?? 1),
            'per_page' => (int)($_GET['per_page'] ?? 15),
        ];

        $services_unit_model = new ControlConsole();
        $services = $services_unit_model->getServices($filters, $pagination);

        if(empty($services)) {
            http_response_code(404);
            return ['message' => 'Services not found'];
        }

        http_response_code(200);
        return [
            "data" => $services['data'],
            "pagination" => [
                "page" => $pagination['page'],
                "per_page" => $pagination['per_page'],
                'total' => $services['total'],
                'total_pages' => (int) ceil($services['total'] / $pagination['per_page'])
            ]
        ];
    }

    public function getAllUnits(){
        $controlConsoleModel = new ControlConsole();
        $unitsType = $controlConsoleModel->getAllUnits();
         http_response_code(200);
        return [
            'data' => $unitsType
        ];
    }

    public function show(int $serviceId)
    {
        $controlConsoleModel = new ControlConsole();
        $controlConsole = $controlConsoleModel->getById($serviceId);

        if (!$controlConsole['data']) {
            http_response_code(404);
            return [
                "status" => false,
                "message" => "No results found",
            ];
        }

        http_response_code(200);
        return [
            'data' => $controlConsole['data']
        ];
    }

    public function sendEmailAlert(array $post)
    {
        try {

            $validationRequest = ControlConsoleEmailRequestValidation::validate($post);

            if(!$validationRequest['status']) {
                http_response_code(422);
                return $validationRequest;
            }

            $reminderMailDTO = ReminderMailDTO::fromRequest($validationRequest['data']);
            $resourcesValidation = ControlConsoleResourcesValidation::validation($reminderMailDTO);

            if(!$resourcesValidation['status']) {
                http_response_code($resourcesValidation['code']);
                return [
                    'message' => $resourcesValidation['message']
                ];
            }

            SendRemainderServiceLogMail::send($resourcesValidation['data'], $reminderMailDTO);

            $email_log = new  StageServiceEmailLog();
            $email_log->create([
                'servicio_id' => $resourcesValidation['data']['service'][0]['servicio_id'],
                'etapa_servicio_id' => $resourcesValidation['data']['service'][0]['etapa_servicio_id'],
                'etapa_servicio_estatus_id' => $resourcesValidation['data']['service'][0]['etapa_servicio_estatus_id'],
                'id_usuario_emisor' => $resourcesValidation['data']['user_send']['id'],
                'id_usuario_receptor' => $resourcesValidation['data']['user_recipient']['id'],
                'comentarios' => $reminderMailDTO->comments
            ]);

            http_response_code(200);
            return [
                'message' => 'Recordatorio enviado con éxito'
            ];

        } catch (\Throwable $e) {
            return [
                "message" => $e->getMessage()
            ];
        }
    }

    public function getStageStatusByService(int $serviceId)
    {
        $controlConsoleModel = new ControlConsole();
        $service = $controlConsoleModel->getLastServiceStageByServiceId($serviceId);

        $data = [];

        $stageModel = new StageService();
        $stages = $stageModel->all();

        if(count($service) === 0) {
            $data[] = $stages;
        }

        http_response_code(200);
        return [
            'data' => $data
        ];
    }

    public function getAllStages(){
        $controlConsoleModel = new ControlConsole();
        $service = $controlConsoleModel->getAllStages();
         http_response_code(200);
        return [
            'data' => $service
        ];
    }

    public function updateStatus(array $data){
        

        $controlConsoleModel = new ControlConsole();
            $log = $controlConsoleModel->insertStatusLog([
                'userId'=> $data['userId'],
                'serviceId'=> $data['serviceId'],
                'stageServiceId' => $data['stageServiceId'],
                'statusServiceStatusId' => $data['statusServiceStatusId'],
                'comments' => $data['comments'],
            ]);

        if(!$log['status']){
            http_response_code(404);
            return [
                "status" => false,
                "message" => "Error log insert",
            ];
        }

        http_response_code(200);
        return [
            // 'data' => $data,
            'log' => $log
        ];
    }
}