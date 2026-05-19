# 📌 Contact Manager Web Application

## 📖 Project Overview

The Contact Manager Web Application is a full-stack web application developed as a Capstone Project.

The main goal of this project is to help users store and manage their contact information in one centralized and organized system instead of using paper notes or multiple disconnected tools.

The application allows users to create, manage, search, filter, update, and delete contacts through a modern and responsive dashboard interface.

---

# ✨ Main Features

## 🔐 Authentication System

- Secure login system
- Session handling using Local Storage
- Login validation and error handling

---

## 👥 Contact Management

- Add new contacts
- Edit existing contacts
- Delete contacts
- View all contacts
- Upload contact images

---

## 🔍 Search & Filter

- Search contacts by:
  - Name
  - Email
  - Phone Number

- Filter contacts by category

---

## 📁 Categories

- Organize contacts into categories
- Dynamic category loading from database

---

## 📤 Export System

- Export contacts to CSV file

---

## 📊 Dashboard

- Total Contacts statistics card
- Total Categories statistics card
- Responsive modern dashboard UI

---

## ✅ Validation & Error Handling

- Required field validation
- Email format validation
- Phone number validation
- Name validation
- Delete confirmation popup
- Success and error toast messages

---

## 🎨 UI/UX Improvements

- Responsive design
- Professional dashboard layout
- Modern cards and buttons
- Loading spinner animation
- Empty state UI
- Hover animations and transitions

---

# 🛠 Technologies Used

## Frontend

- Angular
- TypeScript
- HTML
- CSS

---

## Backend

- PHP
- MySQL

---

## Development Tools

- XAMPP
- Git & GitHub
- Angular CLI
- VS Code
- Adobe XD

---

# 🧠 System Workflow

1. User logs into the system
2. Angular frontend communicates with PHP backend APIs
3. PHP APIs connect to MySQL database
4. Contact data is stored and retrieved dynamically
5. Users can perform full CRUD operations
6. Images are uploaded and displayed from the uploads folder

---

# 📂 Project Structure

```bash
contact-manager-web-app/
│
├── api_add_contact.php
├── api_get_contacts.php
├── api_update_contact.php
├── api_delete_contact.php
├── api_get_categories.php
├── export_contacts.php
├── login.php
├── uploads/
│
└── contact-manager-frontend/
    │
    ├── src/
    │   ├── app/
    │   │   ├── app.ts
    │   │   ├── app.html
    │   │   ├── app.css
    │   │   └── services/
    │   │       └── contact.ts
```

---

# ▶️ How to Run the Project

## 1️⃣ Start XAMPP

- Open XAMPP Control Panel
- Start:
  - Apache
  - MySQL

---

## 2️⃣ Place Project Folder

Move the project folder into:

```bash
htdocs/
```

Example:

```bash
/Applications/XAMPP/xamppfiles/htdocs/
```

---

## 3️⃣ Database Setup

Open phpMyAdmin and create a database named:

```bash
contact_manager
```

Import the required tables:

- contacts1
- categories

---

## 4️⃣ Run Angular Frontend

Open terminal:

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/contact-manager-web-app/contact-manager-frontend
```

Then run:

```bash
ng serve
```

---

## 5️⃣ Open the Application

```bash
http://localhost:4200
```

---

# 🔑 Demo Login Credentials

```bash
Email: hiba@gmail.com
Password: 123123
```

---

# 📸 Project Screenshots

The project includes:

- Login Page
- Dashboard
- Contact Table
- Add Contact Form
- Validation Messages
- Empty State UI
- Loading Spinner

---

# 🚀 Future Improvements

- Deploy application online
- Add user registration system
- Add pagination
- Add dark mode
- Add advanced analytics
- Improve backend security
- Add cloud image storage

---

# 🎯 Learning Outcomes

Through this project, the following skills were strengthened:

- Frontend development using Angular
- Backend development using PHP
- Database management with MySQL
- API integration
- Form validation
- Responsive UI design
- GitHub version control
- Full-stack application architecture

---

# 👩‍💻 Author

Hiba Abo Shawish

Capstone Project – Web & Mobile Development