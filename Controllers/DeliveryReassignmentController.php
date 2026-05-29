<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/StatusReassignment.php';

class DeliveryReassignmentController
{
    /**
     * The index function retrieves all status reassignments and returns them with a success message or
     * a "No results found" message.
     * 
     * @return The `index` function is returning an array with a message and data. If there are no
     * statuses found, it will return a 404 status code with a message indicating no results found. If
     * statuses are found, it will return a 200 status code along with a message and the statuses data.
     */
    public function index()
    {
        $statusModel = new StatusReassignment();
        $statuses = $statusModel->all();

        if(!$statuses || empty($statuses)) {
            http_response_code(404);
            return ['message' => 'No results found'];
        }

        http_response_code(200);
        return [
            'data' => $statuses
        ];
    }
}