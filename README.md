# 🏀 Basketball Team Management System

A comprehensive, web-based information system designed to streamline the administrative, financial, and operational management of a school basketball extracurricular team.

Built with **PHP Native**, **MySQL**, and **Vanilla JavaScript**, focusing on performance, security, and mobile-first usability.

🔗 **Live Demo:** [http://palapabasket.page.gd](http://palapabasket.page.gd)

> **Note:**
> * **Test Member:** Email: `tamu@demo.com` | Pass: `tamu123` (Use this to test member dashboard)

---

## 📱 Key Features

### 1. 🛡️ Authentication & Role Management
* Secure login system with **Password Hashing (bcrypt)**.
* **Multi-role access:** Administrator (Full Access) and Member (Read-only & Personal Dashboard).
* **Approval System:** New member registrations require Admin approval before accessing the system.

### 2. 💸 Advanced Financial System (Smart Kas)
* **Auto-Save Technology:** Records cash payments instantly via AJAX without needing a "Save" button, preventing data loss.
* **Flexible Nominal:** Admins can input daily cash rates manually.
* **Real-time Calculation:** Automatically calculates total collected funds and outstanding debts.

### 3. ⚖️ Automated Punishment Logic
* **Business Logic:** The system automatically tracks attendance.
* **Trigger:** If a member accumulates **> 4 Alpha (Unexcused Absences)** within a semester, the system **automatically generates a fine** (e.g., IDR 25,000).
* **Semester Reset:** Attendance counters automatically reset at the beginning of a new semester (July/January).

### 4. 📊 Data Visualization & Reporting
* **Admin Dashboard:** Interactive charts using **Chart.js** to visualize attendance trends and member growth.
* **Excel Export:** One-click export for Attendance Reports and Financial/Cash Reports (`.xls` format) with proper formatting.

### 5. 🎨 Responsive & Adaptive UI
* **Desktop View:** Professional Grid Layout for Gallery and Organization Structure.
* **Mobile View:** Transformed layout into **"Feed Style"** (like Instagram) for better UX on smartphones.
* **Image Cropping:** Integrated **Cropper.js** to allow users to crop/zoom profile pictures to a perfect 1:1 or 4:3 ratio before uploading.

---

## 🛠️ Tech Stack

* **Backend:** PHP (Native, Procedural & OOP concepts)
* **Database:** MySQL (Relational Database)
* **Frontend:** HTML5, CSS3 (Custom Responsive), JavaScript (ES6+)
* **Libraries:**
    * [Chart.js](https://www.chartjs.org/) (Data Visualization)
    * [Cropper.js](https://github.com/fengyuanchen/cropperjs) (Image Manipulation)
* **Development:** VS Code, XAMPP

---

## 🚀 Installation (Localhost)

1.  **Clone the repo**
    ```bash
    git clone [https://github.com/yourusername/basketball-management-system.git](https://github.com/yourusername/basketball-management-system.git)
    ```
2.  **Setup Database**
    * Create a database named `db_basket48`.
    * Import the `db_basket48.sql` file provided in the root folder.
3.  **Configure Connection**
    * Open `api/koneksi.php` and `index.php`.
    * Adjust `$host`, `$username`, `$password`, and `$database` to match your local environment (usually `root` and empty password for XAMPP).
4.  **Run**
    * Place the folder in `htdocs` (XAMPP).
    * Open `localhost/basketball-management-system` in your browser.

---

## 👤 Author

Built with passion for efficient management systems.
