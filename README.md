# CMS SaaS - Local Setup & Testing

This is a PHP/MySQL multi-tenant Clinic Management System.

## How it works
The system uses the subdomain of the URL to identify which clinic (tenant) is being accessed.

- **Main Platform:** `localhost/cms-saas` or `cms.local`
- **Super Admin:** `admin.cms.local`
- **Clinic A:** `citycare.cms.local`

## Local Testing (XAMPP)

To test the multi-tenant routing locally, you need to simulate subdomains in your Windows hosts file.

1.  Open Notepad as **Administrator**.
2.  Open `C:\Windows\System32\drivers\etc\hosts`.
3.  Add the following lines:
    ```text
    127.0.0.1   cms.local
    127.0.0.1   admin.cms.local
    127.0.0.1   citycare.cms.local
    ```
4.  In your Apache `httpd-vhosts.conf` (usually `C:\xampp\apache\conf\extra\httpd-vhosts.conf`), add a wildcard virtual host or individual ones pointing to the project folder:
    ```apache
    <VirtualHost *:80>
        DocumentRoot "C:/xampp/htdocs/cms-saas"
        ServerName cms.local
        ServerAlias *.cms.local
    </VirtualHost>
    ```
5.  Restart Apache.

## Database Setup
1.  Create a database named `cms_saas`.
2.  Import `schema.sql`.
3.  Insert a test clinic to see it in action:
    ```sql
    INSERT INTO clinics (name, subdomain, primary_color, status) 
    VALUES ('City Care Clinic', 'citycare', '#10b981', 'active');
    ```

## Technology Stack
- **Backend:** Core PHP 8.x
- **Database:** MySQL (PDO)
- **Frontend:** Tailwind CSS (CDN), Vanilla JS
- **Architecture:** Multi-tenant (Shared Schema)
