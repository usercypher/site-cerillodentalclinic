# site-cerillodentalclinic

## How to Run

### 1. Download the Project

- Download the project as a ZIP file and extract it.

### 2. Install the Required Binary

- Download the binary for your OS from:
  https://github.com/usercypher/uc/tree/main/bin/uc-web/dist
- Place the binary in the folder `bin/uc-web/dist/`.

### 3. Install PHP and MariaDB

**Linux / macOS**

- Install PHP-FPM and MariaDB (use your distribution's package manager if needed):

      apt install php php-fpm mariadb-server

- Start MariaDB in a separate terminal (keep it running):

      mariadbd-safe

**Windows**

- Download PHP from https://www.php.net/downloads.php and extract it **into** `bin/php/php-windows-amd64\` (64-bit) or `bin/php/php-windows-386\` (32-bit), so that `php.exe` sits directly at `bin/php/php-windows-amd64\php.exe` (or the 386 equivalent).
- Install MariaDB Server from https://mariadb.org/download/ — on the download page choose **Windows (x86_64) → MSI Package**. During install, tick **"Install as service"** and **"Use UTF8 as default server's character set"**. The service then starts automatically; no manual start needed.

> **Windows notes**
>
> - **PHP path:** The folder must be `bin\php\php-windows-amd64\` or `bin\php\php-windows-386\` — `php.exe` directly inside it, not nested in an extra subfolder.
> - **Running scripts:** Run `compile.bat` and `uc-web.bat` from a `cmd` window opened in the project root (`cd` into it first), not by double-clicking — this keeps the console open so you can see errors.
> - **Server window:** Leave the `uc-web.bat` window open; it **is** the server. Closing it stops the app.
> - **chmod:** The `chmod +x` lines in the Linux/macOS block have no Windows equivalent and can be ignored.

### 4. Configure `uc.config.php`

Edit `/uc.config.php` and fill in valid credentials:

    'DB_HOST'              => '127.0.0.1',
    'DB_PORT'              => '3306',
    'DB_NAME'              => 'dental_clinic',
    'DB_USER'              => 'root',
    'DB_PASS'              => '',
    'DB_TIME'              => '+08:00',

    'API_GEMINI_KEY'       => '',   // Gemini API key (Google AI Studio)
    'API_GEMINI_URL'       => '',   // Gemini endpoint URL
    'OAUTH2_CLIENT_ID'     => '',   // Google Cloud Console → Credentials
    'OAUTH2_CLIENT_SECRET' => '',   // Google Cloud Console → Credentials
    'OAUTH2_REFRESH_TOKEN' => '',   // From an OAuth2 consent flow

> **Database:** The defaults above match the MariaDB setup in Step 7 (`root`, no password, database `dental_clinic` on `127.0.0.1:3306`). Leave them as-is unless your MariaDB install uses different credentials. `DB_TIME` is the app's timezone offset — adjust if you're not in UTC+8.
>
> **API / OAuth2 keys:** These come from **Google AI Studio** (Gemini API key + URL) and **Google Cloud Console → APIs & Services → Credentials** (OAuth2 client ID, secret, and refresh token). The exact steps change often and vary with the Google Cloud Console UI, so the guide doesn't walk through them — if you don't already have these, ask an AI assistant or search for:
>
> - *"how to get a Gemini API key from Google AI Studio"*
> - *"how to get an OAuth2 client ID, secret, and refresh token from Google Cloud Console"*
>
> **Note:** These values are **required**. If left blank, the app will start but will throw errors at runtime when the AI/Gemini or OAuth2 features are used. Fill them in before relying on those features.

### 5. Compile the App

Open a terminal in the project root folder and run:

**Linux / macOS**

    chmod +x compile.sh
    ./compile.sh

**Windows**

    compile.bat

### 6. Configure and Run the Server

**Linux / macOS**

1. Edit `bin/uc-web/uc-web.json` and set the `bin` value to the path of your PHP-FPM binary.
2. Then run (in a separate terminal):

       chmod +x uc-web.sh
       chmod +x bin/uc-web/dist/*
       ./uc-web.sh

**Windows**

- Run `uc-web.bat` from a `cmd` window in the project root.
- The Windows setup is automatic after placing the PHP binary in `bin\php\php-windows-amd64\` or `bin\php\php-windows-386\` (see Step 3).
- Leave the window open — it **is** the server.

### 7. Configure the Database

1. Open Adminer: http://127.0.0.1:8080/adminer

   > **Note:** Leave the password and database fields blank
   > (assumes a default MariaDB install with no root password set).

2. Log in with:

   | Field    | Value         |
   |----------|---------------|
   | System   | MySQL/MariaDB |
   | Server   | 127.0.0.1     |
   | Username | root          |
   | Password | *(blank)*     |
   | Database | *(blank)*     |

3. Create a new database named `dental_clinic` (use the "Create database" option in Adminer).

4. Copy the contents of the following files (in this order):

   - `bin/db.schema.sql`
   - `bin/db.seed.sql`

5. Select the newly created `dental_clinic` database, click **SQL command** in the menu, paste the copied content from the files **in order**, then click **Execute**.

### 8. Open the App and Log In

Go to:

    http://127.0.0.1:8080/

Log in with the default admin account:

| Field    | Value           |
|----------|-----------------|
| Email    | `admin@test`    |
| Password | `123`           |

> **Note:** Change the default admin password after your first login.

You're done.

