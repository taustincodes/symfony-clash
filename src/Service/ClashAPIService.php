<?php

namespace App\Service;

class ClashAPIService
{
    public function getClashRoyaleData(string $endpoint)
    {   
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6ImU1OTAwNTYxLWIzYjYtNDU4Ny1hYzZlLThiOTkxNmVkMDIwNSIsImlhdCI6MTYyNjYzNDQzNiwic3ViIjoiZGV2ZWxvcGVyLzZhNTg0MzQ5LTdmMzMtMThjOS1iNGI1LTE0ZmQ5MThiMTFlMyIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyI4Ni4xMS4xMTEuNTciXSwidHlwZSI6ImNsaWVudCJ9XX0.fenczhSyLGyXhTQDIma5Ujzrx0oddAW7IKp44kgBV1TdVOj_i588nIYw_O9aFashFwoTCagngvpH38aPln3gRA";

        $baseUrl = "https://api.clashroyale.com/v1";
        $header = array();
        $header[] = "Accept: application/json";
        $header[] = "Authorization: Bearer ". $token;

        $url = $baseUrl . $endpoint;

        header('Content-Type: text/html; charset=UTF-8');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $data = json_decode($response, true);
        curl_close($ch);

        return $data;
    }
}
