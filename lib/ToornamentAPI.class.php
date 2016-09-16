<?php

/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 11-09-16
 * Time: 19:33
 */
class ToornamentAPI
{

    private $baseUrl = "https://api.toornament.com/";
    private $clientSecret;
    private $clientId;
    private $token;

    public function __construct()
    {
        $this->clientId = sfConfig::get("app_toornament_id");
        $this->clientSecret = sfConfig::get("app_toornament_secret");
        $this->apiKey = sfConfig::get("app_toornament_api_key");
        $this->retrieveKey();
    }

    public function retrieveKey()
    {
        $folder = sfConfig::get("sf_app_base_cache_dir");
        $filename = $folder . DIRECTORY_SEPARATOR . "toornament.json";
        if (!file_exists($filename) || @filemtime($filename) + 60 * 60 * 24 < time()) {
            $this->token = $this->get("oauth/v2/token", array(
                "grant_type" => "client_credentials",
                "client_id" => $this->clientId,
                "client_secret" => $this->clientSecret
            ));
            file_put_contents($filename, json_encode($this->token));
        } else {
            $this->token = json_decode(file_get_contents($filename), true);
        }
    }

    private function prepare($uri, $needOAuth = false, $headers = array())
    {
        $ch = curl_init($this->baseUrl . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($needOAuth)
            $headers[] = "Authorization: Bearer " . $this->token['access_token'];
        $headers[] = "X-Api-Key: " . $this->apiKey;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        return $ch;
    }


    public function get($uri, $params = array(), $needOAuth = false)
    {
        if (count($params) > 0) {
            $uri .= "?" . http_build_query($params);
        }

        $ch = $this->prepare($uri, $needOAuth);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            return json_decode($result, true);
        } else {
            throw new Exception("{$this->baseUrl}$uri returned $httpcode");
        }
    }

    public function post($uri, $params = array(), $needOAuth = false)
    {
        $data = json_encode($params);

        $ch = $this->prepare($uri, $needOAuth, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)));

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            return json_decode($result, true);
        } else {
            echo $result;
            throw new Exception("{$this->baseUrl}$uri returned $httpcode");
        }
    }

    public function patch($uri, $params = array(), $needOAuth = false)
    {
        $data = json_encode($params);

        $ch = $this->prepare($uri, $needOAuth, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)));

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            return json_decode($result, true);
        } else {
            echo $result;
            throw new Exception("{$this->baseUrl}$uri returned $httpcode");
        }
    }

    public function put($uri, $params = array(), $needOAuth = false)
    {
        $data = json_encode($params);

        $ch = $this->prepare($uri, $needOAuth, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)));

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            return json_decode($result, true);
        } else {
            throw new Exception("PUT {$this->baseUrl}$uri returned $httpcode");
        }
    }
}