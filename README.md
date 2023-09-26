# Cloudflare Bulk DNS Delete

This repository provides a PHP script for bulk deleting DNS records in Cloudflare using the Cloudflare API.

## Prerequisites

Before you begin, ensure you have met the following requirements:

- PHP installed on your local machine or server.
- Composer installed for managing dependencies.
- A Cloudflare account with an active Zone ID.
- Cloudflare API Token with the necessary permissions.

## Installation

1. Clone this repository to your local machine:
    ```shell
    git clone https://github.com/fahamjv/cloudflare-dns-record-deletion.git
    ```

2. Change to the project directory:
    ```shell
    cd cloudflare-dns-record-deletion
    ```

3. Install the required dependencies using Composer:
    ```shell
    composer install
    ```


## Usage

1. Replace the placeholders in the script with your Cloudflare Zone ID and API Token:
    ```PHP
    $zoneId = "YOUR_ZONE_ID";
    $bearerToken = "YOUR_API_TOKEN";
    ```

2. Run the script:
    ```shell
    php delete_dns_records.php
    ```
    The script will start deleting DNS records in batches. It will handle rate limiting and retries for failed deletions.

