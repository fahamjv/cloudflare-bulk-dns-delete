<?php

require "vendor/autoload.php"; // Make sure you have Guzzle installed via Composer

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/**
 * Replace with your Cloudflare Zone ID
 *
 * @link https://dash.cloudflare.com/[your-account-id]/[domain]
 */
$zoneId = $_ENV['ZONE_ID'];

/**
 * Replace with your Cloudflare API Token
 *
 * @link https://dash.cloudflare.com/profile/api-tokens
 */
$bearerToken = $_ENV['BEARER_TOKEN'];

// Configuration
$logFile = "script_log.txt";
ini_set("log_errors", 1);
ini_set("error_log", $logFile);
ini_set("memory_limit", "512M");
set_time_limit(0);

$client = new Client([
    "base_uri" => "https://api.cloudflare.com/client/v4/",
    "headers" => [
        "Content-Type" => "application/json",
        "Authorization" => "Bearer " . $bearerToken,
    ],
]);

/**
 * Function to fetch DNS records from Cloudflare
 *
 * @param   Client    $client
 * @param   string    $zoneId
 *
 * @return array
 */
function fetchDNSRecords($client, $zoneId)
{
    $response = $client->get(
        "zones/{$zoneId}/dns_records"
    );

    return json_decode($response->getBody(), true);
}

/**
 * Function to delete a DNS record using its ID
 *
 * @param   Client    $client
 * @param   string    $zoneId
 * @param   string    $recordId
 *
 * @return int|null
 */
function deleteDNSRecord($client, $zoneId, $recordId)
{
    try {
        $response = $client->delete("zones/{$zoneId}/dns_records/{$recordId}");
        return $response->getStatusCode();
    } catch (RequestException $e) {
        // Handle request exceptions, e.g., rate limiting, and log the error
        error_log("Error: {$e->getMessage()}. Retrying in a moment...");
        return null; // Indicates a retry is needed
    }
}

// Fetch and delete DNS records until no more records are returned
$totalDeleted = 0;

while (true) {
    $dnsRecords = fetchDNSRecords($client, $zoneId)["result"];

    if (empty($dnsRecords)) {
        break; // No more records to delete
    }

    foreach ($dnsRecords as $record) {
        $recordId = $record["id"];
        $recordName = $record["name"];
        $recordType = $record["type"];

        echo "Deleting: {$recordName} [{$recordType}]\n";

        $httpStatus = deleteDNSRecord($client, $zoneId, $recordId);

        if ($httpStatus === 200) {
            $totalDeleted++;
        } elseif ($httpStatus === null) {
            // Retry the same record after a delay
            sleep(5); // Wait for 5 seconds before retrying
            continue;
        } else {
            // Handle other errors
            echo "Error: {$httpStatus}. Skipping this record.\n";
        }

        echo "Response: {$httpStatus}\n\n";

        // Add a delay to avoid rate limiting
        sleep(1); // Wait for 1 second before processing the next record
    }
}

echo "{$totalDeleted} records deleted.\n";
