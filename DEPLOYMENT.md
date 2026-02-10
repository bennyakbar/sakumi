# Deployment Guide to Render + Supabase (True Free Tier)

This guide explains how to deploy **SAKU MI** completely for free using Supabase (Database) and Render (Application Hosting).

## 1. Database Setup (Supabase)

1.  Go to [Supabase](https://supabase.com/) and create a free account.
2.  Create a **New Project**.
3.  Set a database password and save it!
4.  Once created, go to **Project Settings** > **Database** -> **Connection string**.
5.  Select **URI** mode and copy the connection string. It will look like:
    `postgresql://postgres:[YOUR-PASSWORD]@db.[PROJECT-REF].supabase.co:5432/postgres`

## 2. Prepare Application for Render

The repository already includes a `Dockerfile` configured for production.

### Update `TrustProxies.php`
Ensure Laravel trusts the proxy headers from Render (for HTTPS).

**Modify `bootstrap/app.php`:**
```php
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
    })
```

## 3. Application Hosting (Render)

1.  Go to [Render](https://render.com/) and create a free account.
2.  Click **New +** and select **Web Service**.
3.  Connect your GitHub repository: `bennyakbar/sakumi`.
4.  **Settings**:
    *   **Name**: `sakumi-app` (or similar)
    *   **Region**: Singapore (closest to Indonesia)
    *   **Branch**: `main`
    *   **Runtime**: **Docker** (Important!)
    *   **Build Context**: `.`
    *   **Dockerfile Path**: `Dockerfile`
    *   **Instance Type**: Free

5.  **Environment Variables**:
    Add the following environment variables (from your `.env`):
    *   `APP_NAME`: `SAKU MI`
    *   `APP_ENV`: `production`
    *   `APP_KEY`: (Generate one via `php artisan key:generate --show`)
    *   `APP_DEBUG`: `false`
    *   `APP_URL`: (Leave empty for now, update after deployment url is known)
    *   `LOG_CHANNEL`: `stderr`
    *   `DB_CONNECTION`: `pgsql`
    *   `DB_URL`: (Paste your Supabase connection string here!)
        *   **Important**: Append `?sslmode=require` to the end of the Supabase URL if not present.
        *   Example: `postgresql://postgres:password@host:port/postgres?sslmode=require`

6.  Click **Create Web Service**.

## 4. Post-Deployment Steps

Once the deployment is live (green):

1.  **Run Migrations**:
    *   Go to the **Shell** tab in Render dashboard.
    *   Run: `php artisan migrate --force`
    *   Run: `php artisan db:seed --class=AdminTUSeeder --force`

2.  **Access App**:
    *   Click the URL provided by Render (e.g., `https://sakumi-app.onrender.com`).
    *   Login with the Admin TU credentials (e.g., `siti@skmi.sch.id`).

## 5. Notes on Free Tier Limits
*   **Render**: The free web service spins down after 15 minutes of inactivity. The first request will take ~30-50 seconds to wake it up.
*   **Supabase**: The free database pauses after 7 days of inactivity. You just need to log in to the Supabase dashboard to wake it up.

## 6. Alternative: Hugging Face Spaces (No Credit Card)

If Render asks for a credit card, **Hugging Face Spaces** is a great alternative that requires **no credit card**.

### Steps to Deploy on Hugging Face:

1.  **Create Account**: Go to [huggingface.co](https://huggingface.co/) and sign up.
2.  **Create Space**:
    *   Click **New Space**.
    *   **Space Name**: `sakumi-app`
    *   **License**: `MIT`
    *   **SDK**: Select **Docker**.
    *   **Template**: `Blank`.
    *   **Visibility**: `Public` (Free) or `Private` (Paid). Choose Public for free tier.

3.  **Upload Files**:
    *   Go to **Files** tab in your Space.
    *   Click **Add file** -> **Upload files**.
    *   Upload all files from your project (or connect via Git).
    *   **Easiest way**: Use the git command they provide to push your code there.
        ```bash
        git push https://huggingface.co/spaces/[YOUR_USERNAME]/sakumi-app main
        ```

4.  **Environment Variables (Secrets)**:
    *   Go to **Settings** tab of your Space.
    *   Scroll to **Variables and secrets**.
    *   Add **New Secret** for each variable:
        *   `APP_KEY`, `DB_URL`, etc. (Same list as above).
        *   **Note**: For `DB_URL`, ensure you use the `?sslmode=require` version.
    *   Add `APP_ENV` = `production` as a **Variable** (or Secret).

5.  **Automatic Migrations**:
    *   We added a `run.sh` script to the project.
    *   This script automatically runs `php artisan migrate` every time the Spaces container starts.
    *   So just wait for the "Building" status to turn into "Running".

6.  **Access App**:
    *   Click the "App" tab to see your running SAKU MI!
