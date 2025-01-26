# News Aggregator API Platform

The **News Aggregator** platform combines multiple news APIs into a unified experience, allowing users to browse, search, and personalize their news feed. This repository provides a streamlined setup for developers to get started quickly.

---

## Features

- Integrates various news APIs into a single platform.
- Provides a personalized news feed based on user preferences.
- Enables searching and filtering of news by keyword, category, source, and author.
- Supports scheduled scraping and import of news data.

---

## Setup Instructions

### Prerequisites

- Docker and Docker Compose installed.
- Familiarity with Laravel Sail for local development.

### Installation Steps

1. **Set Docker Context**
   ```bash
   docker context use default
   ```

2. **Clone the Repository**
   ```bash
   git clone https://github.com/asmshaon/news-aggregator
   cd news-aggregator
   ```

3. **Start the Application**
   ```bash
   ./vendor/bin/sail up
   OR for 1st run you may need this with --build flag
   ./vendor/bin/sail up --build
   ```
   
4. **Encrypt/Decrypt Environment File**
    - Contact me to obtain the encryption key.
    - Decrypt `.env` file:
      ```bash
      ./vendor/bin/sail artisan env:decrypt --key=<your-encryption-key> --filename=.env.decrypted --force
      ```

5. **Prepare the `.env` File**
    - Copy the decrypted environment file:
      ```bash
      cp env.decrypted .env
      ```

6. **Run Migrations**
   ```bash
   ./vendor/bin/sail composer install
   ```

7. **Run Migrations**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

8. **Scrape News Data**
    - Import all sources:
      ```bash
      ./vendor/bin/sail artisan news-aggregator:scrape-all
      ```
    - Import individual sources:
      ```bash
      ./vendor/bin/sail artisan news-aggregator:guardian-api:scrape
      ./vendor/bin/sail artisan news-aggregator:news-api:scrape
      ./vendor/bin/sail artisan news-aggregator:ny-times-api:scrape
      ```

9. **Optional: Schedule Tasks**
   ```bash
   ./vendor/bin/sail artisan schedule:work
   ```
   
10. [Download the Frontend APP](https://github.com/asmshaon/news-aggregator-front) and [Check out there](http://localhost:3000/)


---

## Postman Collection

- Contact me to obtain the Postman collection for API testing.

---

## Notes

- Keep the encryption key secure and use it consistently for encrypting and decrypting the `.env` file.
- For production, consider setting up a robust environment variable management system.
- Use the `schedule:work` command for periodic updates of the news database.

---

## API Integrated 

- NEWS API ORG
- Guardian API
- New York Times API

---

##  Troubleshoot
Please see this documentation if you are unable to run this app, this may vary, I am using Linux
https://laravel.com/docs/11.x/sail#rebuilding-sail-images

*** If you get any ports conflicts please kill your process or use different port on docker compose file ***

Happy Developing! 🎉
```
