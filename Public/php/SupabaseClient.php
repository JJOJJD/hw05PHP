<?php

class SupabaseClient
{
    private string $url;
    private string $key;

    public function __construct(string $url, string $key)
    {
        $this->url = rtrim($url, '/');
        $this->key = $key;
    }

    public function insert(string $table, array $data): array
    {
        $endpoint = $this->url . '/rest/v1/' . $table;

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'apikey: ' . $this->key,
                'Authorization: Bearer ' . $this->key,
                'Prefer: return=representation',
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new RuntimeException('cURL error: ' . $curlError);
        }

        $decoded = json_decode($response, true);

        if ($httpCode >= 400) {
            $message = $decoded['message'] ?? $decoded['error'] ?? $response;
            throw new RuntimeException('Supabase error (' . $httpCode . '): ' . $message);
        }

        return $decoded[0] ?? [];
    }
}
