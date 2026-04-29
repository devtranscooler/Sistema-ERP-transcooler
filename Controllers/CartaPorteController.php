<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/Models/Service.php');

class CartaPorteController 
{
    /**
    * This PHP function generates data for a carta porte based on a given ID and returns it with
    * appropriate HTTP response codes.
    * 
    * @param int id The `generate` function takes an integer parameter `` which is used to retrieve
    * data related to a service or carta porte. The function first creates an instance of the `Service`
    * class, then calls the `getCartaPorteData` method of this class to fetch data based on the
    * 
    * @return The `generate` function returns an array with a success status and data related to a
    * service identified by the provided ID. If the service is not found, it returns a 404 status code
    * along with a message indicating that no results were found.
    */
    public function generate(int $id)
    {
        $serviceModel = new Service();
        $service = $serviceModel->getCartaPorteData($id);

        if(!$service) {
            http_response_code(404);
            return [
                "status" => false,
                "message" => "No results found",
            ];
        }

        $service['origin_route_address'] = $serviceModel->getRouteAddress($service['servicio_id'], "origen") ?? null;
        $service['destination_route_address'] = $serviceModel->getRouteAddress($service['servicio_id'], "destino") ?? null;
        
        http_response_code(200);
        return [
            "success" => true,
            "data" => $service,
        ];
    }
}