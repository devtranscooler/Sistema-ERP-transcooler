<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/Delivery.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/Service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/deliveries/DeliveryService.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/validations/deliveries/RequestValidator.php';

class DeliveryController
{
    /**
     * This PHP function retrieves deliveries by a specified service ID and returns the associated
     * media evidences.
     * 
     * @param int serviceId The `getDeliveriesByService` function takes a parameter `serviceId` of type
     * integer. This function retrieves deliveries based on the provided `serviceId`. If no deliveries
     * are found for the given `serviceId`, it returns a 404 status code with a message indicating that
     * the resources were
     * 
     * @return The function `getDeliveriesByService` is returning an array with a key "data" containing
     * the media evidences related to deliveries for a specific service. If no deliveries are found for
     * the given service, it returns a 404 HTTP response code along with a message stating "Resources
     * not found".
     */
    public function getDeliveriesByService(int $serviceId)
    {
        $deliveryModel = new Delivery();
        $deliveries = $deliveryModel->getDeliveriesByService($serviceId);

        if(!$deliveries || empty($deliveries)) {
            http_response_code(404);
            return ['message' => 'Resources not found'];
        }

        $mediaEvidences = DeliveryService::getEvidencesMediaByDelivery($deliveries);

        http_response_code(200);
        return [
            "data" => $mediaEvidences
        ];
    }

    /**
     * This PHP function updates a delivery record based on the provided ID and validated fields.
     * 
     * @param int id The `id` parameter in the `updateDelivery` function is an integer that represents
     * the unique identifier of the delivery that needs to be updated. This identifier is used to fetch
     * the specific delivery record from the database for updating its status.
     * @param array post The `updateDelivery` function you provided seems to be updating the status of
     * a delivery based on the `` and the data in the `` array.
     * 
     * @return an array with a success message 'Actualizado con éxito' if the delivery update is
     * successful. If there are validation errors, it returns the validation result. If the delivery
     * with the specified ID is not found, it returns a 404 error message 'Resources not found'.
     */
    public function updateDelivery(int $id, array $post)
    {
        $validation = RequestValidator::validation($post);

        if (!$validation['status']) {
            return $validation;
        }

        $validatedFields = $validation['data'];

        $deliveryModel = new Delivery();
        $delivery = $deliveryModel->getDeliveryById($id);

        if(!$delivery || empty($delivery)) {
            http_response_code(404);
            return ['message' => 'Delivery not found'];
        }

        $serviceModel = new Service();
        $service = $serviceModel->getServiceById($delivery[0]['id_servicio']);

        if(!$service || empty($service)) {
            http_response_code(404);
            return ['message' => 'Service not found'];
        }

        $updated = $deliveryModel->update($delivery[0]['id'], [
            'status' => $validatedFields['status']
        ]);

        $updateStatusService = $serviceModel->update($service[0]['id'], [
            'status_operativo' => strtolower($validatedFields['status'])
        ]);

        if (!$updated || !$updateStatusService) {
            http_response_code(500);
            return [
                'status' => false,
                'message' => 'No fue posible actualizar el reparto y/o servicio'
            ];
        }

        $updatedDelivery = $deliveryModel->getDeliveryById($id);

        http_response_code(200);
        return [
            'message' => "Actualizado con éxito",
            'data' => $updatedDelivery[0]
        ];
    }
}