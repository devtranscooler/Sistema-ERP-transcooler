<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/Models/Media.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/validations/media/FileValidator.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/DTO/media/UploadMediaDTO.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/media/FileUploadService.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Resources/Media/MediaResponse.php');

class MediaController
{
    /**
     * The function retrieves media data based on filters and pagination parameters, returning success
     * message along with the data and pagination details or a message if no results are found.
     * 
     * @return If the `` variable is not set or empty, a 404 HTTP response code with a message
     * "Not results found" will be returned. Otherwise, a 200 HTTP response code with a success
     * message, the media data (if available), and pagination information will be returned.
     */
    public function index()
    {
        $filters = [
            'tipo_recurso' => $_GET['tipo_recurso'] ?? null,
            'tipo_recurso_id' => $_GET['tipo_recurso_id'] ?? null,
        ];

        $pagination = [
            'page' => (int)($_GET['page'] ?? 1),
            'per_page' => (int)($_GET['per_page'] ?? 15),
        ];


        $mediaModel = new Media();
        $media = $mediaModel->getAll($filters, $pagination);

        if(!isset($media) || empty($media)){
            http_response_code(404);
            return [
                "message" => "Not results found",
            ];
        }

        http_response_code(200);
        return [
            "message" => "success",
            "data" => $media ?? null,
            "pagination" => [
                "page" => $pagination['page'],
                "per_page" => $pagination['per_page']
            ]
        ];
    }

    /**
     * The function `upload` processes file uploads, validates them, and returns a success response
     * with transformed media data.
     * 
     * @param array post The `upload` function you provided seems to handle file uploads based on the
     * `` and `` parameters. The `` parameter is an array that likely contains form
     * data or additional information related to the file upload process. On the other hand, the
     * `` parameter is an array
     * @param array files The `upload` function you provided seems to handle file uploads based on the
     * `` and `` parameters passed to it. The `` parameter is an array containing
     * information about the files being uploaded. This array likely includes details such as file
     * name, file type, file size, and
     * 
     * @return array An array is being returned with the following structure:
     * ```php
     * [
     *     "status" => true,
     *     "message" => "success",
     *     "data" => 
     * ]
     * ```
     * Where `` is an array of transformed media items after processing the uploaded files.
     */
    public function upload(array $post, array $files): array
    {
        $validation = FileValidator::fileValidation($files, $post);

        if (!$validation['status']) {
            return $validation;
        }

        $validatedFiles = $validation['files'];
        $data = $validation['data'];

        $mediaDTO = new UploadMediaDTO(
            $data['tipo_recurso'],
            $data['tipo_recurso_id'],
            $data['user_id'],
            $data['modulo_servicio']
        );

        $uploadService = new FileUploadService();

        $results = $uploadService->process($validatedFiles, $mediaDTO);

        $response = array_map(
            fn($item) => MediaResponse::transform($item),
            $results
        );

        http_response_code(201);
        return [
            "status" => true,
            "message" => "success",
            "data" => $response
        ];
    }

    /**
     * The function deletes a media file by its ID and returns a success message if the deletion is
     * successful.
     * 
     * @param int id The `delete` function you provided is a PHP method that deletes a media file based
     * on the given ID. The function first attempts to find the media file by the provided ID using the
     * `findById` method of the `Media` model. If the media file is not found, it returns a
     * 
     * @return The `delete` function is returning an array with a status and message based on the
     * outcome of the deletion operation. If the media with the provided ID is not found, it returns a
     * 404 status with a message indicating no results found. If the deletion is successful, it returns
     * a 200 status with a message indicating the file was deleted successfully.
     */
    public function delete(int $id)
    {
        $mediaModel = new Media();
        $media = $mediaModel->findById($id);

        if (!$media) {
            http_response_code(404);
            return [
                "status" => false,
                "message" => "No results found",
            ];
        }

        $mediaModel->delete($media['id']);

        http_response_code(200);
        return [
            "status" => true,
            "message" => "File deleted successfully",
        ];
    }
}