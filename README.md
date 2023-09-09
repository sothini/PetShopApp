# Petshop Application README

Welcome to the README file for your Laravel application. This document provides essential information about setting up and configuring your Laravel application. Please follow the instructions below to ensure a smooth setup process.

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Environment Variables (most important)](#environment-variables)



## 1. Prerequisites

Before you proceed, make sure you have the following prerequisites installed on your development environment:

- PHP >= 8
- Composer (https://getcomposer.org/)
- MySQL or any other supported database system
- Laravel CLI (https://laravel.com/docs/8.x/installation)



## 2. Installation

   1. Clone this repository to your local machine:

```bash
   git clone https://github.com/sothini/PetShopApp.git
```

 2. Navigate to the project directory:

```bash
   cd PetshopApp
   ```


 3. Install the required PHP dependencies using Composer:

```bash
  composer install
  ```


 4. Create a copy of the .env.example file and rename it to .env:

```bash
   cp .env.example .env
   ```

 5. Generate an application key:

```bash
   php artisan key:generate
   ```
	




## 3. Environment Variables (Most Important)

The following environment variables MUST be set in your `.env` file for the application to function correctly, apart from other common variables like database connection:

- **L5_SWAGGER_GENERATE_ALWAYS**: Set this to true.
- **L5_SWAGGER_BASE_PATH**: set you app base path here till v1 (eg /app/public/api/v1 ).
- **VERIFICATION_KEY**: Set your verification key for signing tokens.
- **PRIVATE_KEY**: put your PEM-encoded private key in the RSA format here, this is also for the token.


