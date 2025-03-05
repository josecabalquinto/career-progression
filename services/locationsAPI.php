<?php
class LocationsAPI
{
    private $baseUrl = "https://psgc.gitlab.io/api";

    public function getCities($provinceCode)
    {
        return $this->makeRequest("/provinces/$provinceCode/cities-municipalities");
    }

    public function getBarangays($cityCode)
    {
        return $this->makeRequest("/cities-municipalities/$cityCode/barangays");
    }

    private function makeRequest($endpoint)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_FOLLOWLOCATION => true, // Follow redirects
            CURLOPT_HTTPHEADER => [
                "Accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ["error" => "cURL Error: " . $err];
        }

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return ["error" => "Invalid JSON response", "raw_response" => $response];
        }

        if ($httpCode !== 200) {
            return ["error" => "HTTP Error: $httpCode", "response" => $decodedResponse];
        }

        return $decodedResponse;
    }
}
