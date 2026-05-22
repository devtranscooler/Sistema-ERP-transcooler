<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/Delivery.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/deliveries/DeliveryOperatorService.php';

class DeliveryOperatorController
{
    
    /**
     * This PHP function retrieves deliveries by operator ID and returns them in a response array.
     * 
     * @param int id The `index` function takes an integer parameter ``, which is used to retrieve
     * deliveries by operator from the database. If no deliveries are found for the specified operator,
     * a 404 HTTP response is returned along with a message indicating that the resources were not
     * found. If deliveries are found, a
     * 
     * @return An array is being returned with the key 'data' containing the deliveries fetched by the
     * Delivery model for the specified operator ID. If no deliveries are found, a 404 HTTP response
     * code is set and an array with a 'message' key indicating 'Resources not found' is returned.
     */
    public function index(int $idOperator)
    {
        $deliveryModel = new Delivery();
        $deliveries = $deliveryModel->getDeliveriesByOperator($idOperator);

        if(!$deliveries || empty($deliveries)) {
            http_response_code(404);
            return ['message' => 'Resources not found'];
        }

        $deliveryService = DeliveryOperatorService::generateDeliveriesAddresses($deliveries);

        http_response_code(200);
        return [
            'data' => $deliveryService
        ];
    }

    /**
     * This PHP function retrieves products associated with a specific delivery ID and returns them in
     * a response array.
     * 
     * @param int deliveryId The `productByDelivery` function takes a `deliveryId` as a parameter. It
     * then creates an instance of the `Delivery` model and calls the `getProductsByDelivery` method to
     * retrieve products associated with the given `deliveryId`.
     * 
     * @return an array with a key 'data' containing the products fetched by the delivery ID. If no
     * products are found, it returns a 404 HTTP response code along with a message 'Resources not
     * found'.
     */
    public function productByDelivery(int $deliveryId)
    {
        $deliveryModel = new Delivery();
        $products = $deliveryModel->getProductsByDelivery($deliveryId);

        if(!$products || empty($products)) {
            http_response_code(404);
            return ['message' => 'Resources not found'];
        }

        http_response_code(200);
        return[
            'data' => $products
        ];
    }
}