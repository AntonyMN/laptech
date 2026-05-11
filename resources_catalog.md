# Laptech Project Resource Catalog

This document provides a comprehensive list of all technical resources and configurations used in the development and deployment of the Laptech Platform.

## 1. Hosting & Infrastructure
*   **Provider**: DigitalOcean
*   **Server IP**: `159.89.109.15`
*   **Operating System**: Ubuntu 22.04 LTS
*   **Web Server**: Nginx
*   **Deployment Path**: `/var/www/laptech`
*   **SSL Status**: Managed via Certbot (Let's Encrypt)

## 2. Application Stack
*   **Framework**: Laravel 13 (PHP 8.3)
*   **Frontend**: Inertia.js with Vue 3
*   **Styling**: TailwindCSS 4.0
*   **Build Tool**: Vite 8.0
*   **State Management**: Pinia (Cart)
*   **Icons**: FontAwesome 6 (Free)
*   **Editor**: Quill.js (Intelligence Hub)

## 3. Database Configuration
*   **Type**: MySQL
*   **Host**: `localhost` (127.0.0.1)
*   **Port**: `3306`
*   **Database Name**: `laptech_db`
*   **Charset**: `utf8mb4`

## 4. Source Control
*   **Repository**: `https://github.com/AntonyMN/laptech.git`
*   **Main Branch**: `master`

## 5. Third-Party Integrations
*   **Maps/Assets**: Unsplash (Demo Images)
*   **Typography**: Google Fonts (Montserrat, Open Sans)
*   **AI Support (Pending)**: Google Gemini API (Content Generation)

## 6. Key Administrative Access
*   **Admin Console**: `/admin/dashboard`
*   **Default Credentials**: `admin@laptech.com` / `password` (Must be changed post-migration)

---
*Generated on 2026-05-11*
