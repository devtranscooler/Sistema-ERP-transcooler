<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/Media.php';

class DeliveryService
{
    /**
     * The function `getEvidencesMediaByDelivery` retrieves media evidences related to a list of
     * deliveries.
     * 
     * @param array deliveries The `getEvidencesMediaByDelivery` function takes an array of deliveries
     * as input and retrieves evidence media for each delivery. It iterates over each delivery,
     * constructs filters based on the delivery information, fetches the evidence media using the
     * `Media` model, and then adds the retrieved media to
     * 
     * @return array An array of deliveries with their associated media evidences is being returned.
     * Each delivery in the array will have a 'media' key containing an array of media evidences
     * related to that delivery. If no media evidences are found for a delivery, the 'media' key will
     * be set to null.
     */
    public static function getEvidencesMediaByDelivery(array $deliveries): array
    {
        foreach($deliveries as &$delivery) {

            $filters = [
                'tipo_recurso' => 'REPARTOS' ?? null,
                'tipo_recurso_id' => $delivery['id_reparto'] ?? null,
            ];

            $pagination = [
                'page' => 1,
                'per_page' => 15,
            ];

            $mediaModel = new Media();
            $evidencesByDelivery = $mediaModel->getAll($filters, $pagination);
            
            $delivery['media'] = !empty($evidencesByDelivery) ? $evidencesByDelivery : null;
        }

        unset($delivery);

        return $deliveries;
    }
}