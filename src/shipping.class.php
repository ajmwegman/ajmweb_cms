<?php
class shipping {
    private $apiKey;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getPostNLStatus($trackingNumber) {
        $url = "https://api.postnl.nl/shipment/v2/status/" . urlencode($trackingNumber);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: {$this->apiKey}",
            "Accept: application/json"
        ]);
        $result = curl_exec($ch);
        if ($result === false) {
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return json_decode($result, true);
    }
}
?>
