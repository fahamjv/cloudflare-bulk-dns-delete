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
    git clone https://github.com/fahamjv/cloudflare-bulk-dns-delete.git
    ```

2. Change to the project directory:
    ```shell
    cd cloudflare-bulk-dns-delete
    ```

3. Install the required dependencies using Composer:
    ```shell
    composer install
    ```


## Usage

1. Get your tokens

    Cloudflare Zone ID [https://dash.cloudflare.com/](https://dashcloudflare.com/).

    API token [https://dash.cloudflare.com/profile/api-tokens](https://dash.cloudflare.com/profile/api-tokens)

2. Copy the .env file
    ```shell
    cp .env.example .env
    ```

3. Open the .env file and fill it with your tokens:
    ```shell
    ZONE_ID=
    BEARER_TOKEN=
    ```

4. Run the script:
    ```shell
    php main.php
    ```
    The script will start deleting DNS records in batches. It will handle rate limiting and retries for failed deletions.

