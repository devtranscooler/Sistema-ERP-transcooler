<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/Media.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/MediaRequest.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/validations/file-manager/RequestPermissionRequest.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/validations/file-manager/ResourcesValidation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/helpers/StringHelper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/mails/file-manager/SendRequestPermissionMail.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/validations/file-manager/StatusPermissionRequest.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/views/file-manager/StatusRequestPermissionTemplate.php';

class FileManagerController
{
    /**
     * The function retrieves media files based on specified filters and pagination parameters,
     * returning the results along with pagination information or a message if no results are found.
     * 
     * `index()` is returning an array with the following structure:
     * ```
     * [
     *     'data' => ,
     *     "pagination" => [
     *         "page" => ['page'],
     *         "per_page" => ['per_page']
     *     ]
     * ]
     * ```
     * This array includes the data retrieved from the `filesManager()` method of the `Media` model
     * based on the
     */
    public function index()
    {
        $filters = [
            'extension' => $_GET['extension'] ?? null,
            'nombre_origen' => $_GET['nombre_origen'] ?? null
        ];

        $pagination = [
            'page' => (int)($_GET['page'] ?? 1),
            'per_page' => (int)($_GET['per_page'] ?? 15),
        ];

        $mediaModel = new Media();
        $media = $mediaModel->filesManager($filters, $pagination);

        if (empty($media['data'])) {
            http_response_code(404);
            return [
                "status" => false,
                "message" => "No results found",
            ];
        }

        http_response_code(200);
        return [
            'data' => $media['data'],
            "pagination" => [
                "page" => $pagination['page'],
                "per_page" => $pagination['per_page'],
                'total' => $media['total'],
                'total_pages' => (int) ceil($media['total'] / $pagination['per_page'])
            ]
        ];
    }

    /**
     * This PHP function retrieves a media file by its ID and returns it along with a success status or
     * a message if no results are found.
     * 
     * @param int mediaId The `show` function takes a parameter `mediaId` of type integer. This
     * function retrieves a media file using the `fileManagerById` method from the `Media` model based
     * on the provided `mediaId`. If the media file is found, it returns a response with HTTP status
     * code
     * 
     * @return an array with the key 'data' containing the media information if the media is found. If
     * the media is not found, it returns an array with 'status' set to false and a message indicating
     * "No results found". The HTTP response code is set accordingly to 200 for success or 404 for not
     * found.
     */
    public function show(int $mediaId)
    {
        $mediaModel = new Media();
        $media = $mediaModel->fileManagerById($mediaId);

        if (!$media) {
            http_response_code(404);
            return [
                "status" => false,
                "message" => "No results found",
            ];
        }

        http_response_code(200);
        return [
            'data' => $media
        ];
    }

    public function sendRequestPermission(array $post): array
    {
        $validation = RequestPermissionRequest::validate($post);

        if(!$validation['status']) {
            http_response_code(422);
            return $validation;
        }

        $resourcesValidation = ResourcesValidation::validation(
            (int) $post['usuario_solicitante_id'], 
            (int) $post['usuario_aprobador_id'],
            (int) $post['media_id']
        );

        if(!$resourcesValidation['status']) {
            http_response_code($resourcesValidation['code']);
            return [
                'message' => $resourcesValidation['message']
            ];
        }

        $token = StringHelper::generateToken(32);

        $mediaRequestModel = new MediaRequest();
        $mediaRequestModel->create([
            'media_id' => $validation['data']['media_id'],
            'usuario_solicitante_id' => $validation['data']['usuario_solicitante_id'],
            'usuario_aprobador_id' => $validation['data']['usuario_aprobador_id'],
            'estatus' => $validation['data']['estatus'],
            'fecha_aprobacion' => null,
            'comentario' => null,
            'token' => $token,
        ]);

        SendRequestPermissionMail::send($resourcesValidation['data'], $token);

        http_response_code(201);
        return [
            'message' => 'Solicitud enviada con éxito al usuario'
        ];
    }

    /**
     * This PHP function handles permission requests for a media file, validating the status and
     * updating the request accordingly.
     * 
     * @param string token The `statusRequestPermission` function you provided seems to handle updating
     * the status of a media request based on a token. It performs validation on the status parameter,
     * checks the validity of the token, and updates the request status if everything is valid.
     * @param string status The `statusRequestPermission` function takes in two parameters: `` of
     * type string and `` of type string. The function performs the following actions:
     * 
     * `statusRequestPermission` returns an array with a 'message' key based on
     * different conditions:
     * 1. If the status validation fails, it returns a 422 HTTP response code with a message from the
     * validation result.
     * 2. If the token is invalid (not found in the database), it returns a 404 HTTP response code with
     * a message indicating an invalid token.
     * 3. If the update
     */
    public function statusRequestPermission(string $token, string $status)
    {
        header('Content-Type: text/html; charset=UTF-8');
        $validation = StatusPermissionRequest::validation($status);

        if(!$validation['status']) {
            echo StatusRequestPermissionTemplate::renderInvalidStatus($validation['message']);
            exit;
        }

        $mediaRequestModel = new MediaRequest();
        $verifyToken = $mediaRequestModel->findByToken($token);

        if(!$verifyToken) {
            echo StatusRequestPermissionTemplate::renderInvalidToken();
            exit;
        }

        $updateRequest = $mediaRequestModel->update($verifyToken['id'], [
            'estatus' => $validation['data']['status'],
            'fecha_aprobacion' => date('Y-m-d'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if(!$updateRequest){
            echo StatusRequestPermissionTemplate::error500();
            exit;
        }

        echo StatusRequestPermissionTemplate::renderAnswer($status, $verifyToken['nombre_origen']);
        exit;
    }
}