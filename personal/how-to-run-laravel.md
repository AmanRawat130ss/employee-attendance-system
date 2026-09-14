# How To Run This Laravel Project (Step-By-Step)

Yeh guide batati hai ki is project ko apne computer par XAMPP ke sath kaise chalana hai aur test karna hai.

---

## Step 1: XAMPP Start Karein

1. Apne computer par **XAMPP Control Panel** open karo.
2. **Apache** ke aage **"Start"** button par click karo (Green ho jayega).
3. **MySQL** ke aage **"Start"** button par click karo (Green ho jayega, Port 3306 show karega).

---

## Step 2: Database Setup (Do Options Hain)

### Option A: phpMyAdmin Se SQL File Import Karna (Easiest)
1. Browser mein open karo: `http://localhost/phpmyadmin`
2. Top menu mein **"Import"** tab par click karo.
3. **"Choose File"** button dabao aur select karo:
   `C:\xampp\htdocs\laravel\database.sql`
4. Page ke bottom par jakar **"Import"** ya **"Go"** button daba do.
   *(Database `employee_attendance_db` aur saara data automatically create ho jayega!)*

---

### Option B: Terminal Command Se Setup Karna (Laravel Style)
Agar aapko command line se karna pasand hai:
1. VS Code / Terminal mein project folder par jao:
   ```bash
   cd c:\xampp\htdocs\laravel
   ```
2. Pehle MySQL mein database banao (agar bana nahi hai):
   ```bash
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS employee_attendance_db;"
   ```
3. Laravel migration aur seeders run karo:
   ```bash
   php artisan migrate --seed
   ```
   *(Yeh saari tables bana kar Admin aur sample employees ka dummy attendance data daal dega).*

---

## Step 3: Laravel Server Start Karein

Terminal / Command Prompt / PowerShell mein ye command chalao:

```bash
php artisan serve
```

Aapko aisi screen dikhegi:
```text
   INFO  Server running on [http://127.0.0.1:8000].

   Press Ctrl+C to stop the server
```

---

## Step 4: Browser Mein Open Karein

Ab apna browser (Chrome/Edge) kholo aur URL daalo:

👉 **`http://127.0.0.1:8000`** (ya `http://localhost:8000`)

Yeh automatically aapko **Login Page** par le jayega!

---

## Step 5: Test Accounts (Credentials)

Aapko screen par **"1-Click Quick Demo"** buttons bhi milenge, ya manually ye credentials enter kar sakte ho:

### 1. Admin Login (Pure System Ka Control):
* **Email:** `admin@ems.com`
* **Password:** `password123`

### 2. Employee Login (Attendance Portal):
* **Email:** `rahul@ems.com`
* **Password:** `password123`
*(Ya `priya@ems.com`, `vikram@ems.com`, `sneha@ems.com` — sabka password `password123` hai)*

---

## Troubleshooting & Common Commands

* **Agar ".env" change kiya aur changes reflect nahi ho rahe:**
  ```bash
  php artisan config:clear
  php artisan cache:clear
  ```
* **Agar Port 8000 already use mein hai:**
  ```bash
  php artisan serve --port=8080
  ```
  *(Fir browser mein `http://127.0.0.1:8080` open karein)*
* **Agar Database reset karna ho fresh data ke sath:**
  ```bash
  php artisan migrate:fresh --seed
  ```
