<?php

declare(strict_types=1);

class DeliveryOperatorService
{
    /**
     * This PHP function generates delivery addresses based on the previous delivery destination.
     * 
     * @param array deliveries An array containing delivery information, where each element is an
     * associative array with keys like 'direccion_destino' representing the destination address of the
     * delivery.
     * 
     * @return array The function `generateDeliveriesAddresses` returns an array of deliveries with the
     * 'origen_inicio' key added to each delivery item. If the input array `` is empty, the
     * function will return the same empty array.
     */
    public static function generateDeliveriesAddresses(array $deliveries): array
    {
        if(count($deliveries) > 0){
            for ($i = 1; $i < count($deliveries); $i++) {
                $deliveries[$i]['origen_inicio'] = $deliveries[$i - 1]['direccion_destino'];
            }

            return $deliveries;
        }

        return $deliveries;
    }
}