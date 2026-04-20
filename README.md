# 📌 Contact Manager Web Application

## 📖 Project Overview

This project is a **Contact Manager Web Application** developed as a capstone project.
It allows users to manage their contacts in one centralized system instead of using multiple tools or notes.

---

## 🎯 Features

* Add new contacts
* View all contacts
* Edit existing contacts
* Delete contacts
* Upload contact images
* Assign categories (Family, Friends, Work, etc.)
* Search contacts (by name, email, or phone)
* Dashboard with statistics (Total Contacts & Categories)
* Export contacts to CSV

---

## 🛠 Technologies Used

### Backend:

* PHP
* MySQL
* XAMPP

### Frontend:

* Angular
* HTML
* CSS

### Tools:

* Git & GitHub
* Adobe XD (Prototype Design)

---

## 🧠 How It Works

* The **frontend (Angular)** communicates with the **backend (PHP API)**.
* The backend connects to a **MySQL database** to store and retrieve contact data.
* Users can perform full CRUD operations (Create, Read, Update, Delete).
* Images are uploaded and stored in the `uploads/` folder.

---

## ▶️ How to Run the Project (Locally)

### 1. Start Backend (XAMPP)

* Open XAMPP
* Start **Apache** and **MySQL**
* Place the project folder inside:

```
htdocs/
```

---

### 2. Database Setup

* Open **phpMyAdmin**
* Create a database named:

```
contact_manager
```

* Import your database tables (contacts1, categories)

---

### 3. Run Angular Frontend

In terminal:

```
cd contact-manager-frontend
ng serve
```

Then open:

```
http://localhost:4200
```

---

## ⚠️ Important Note

This project runs fully in a **local environment** because it uses PHP and MySQL.

GitHub is used to:

* Showcase project structure
* Track development progress
* Share code

---

## 🚀 Future Improvements

* Improve UI/UX design
* Add pagination
* Add authentication (login system)
* Deploy full project online

---

## 👩‍💻 Author

Capstone Project – Web & Mobile Development
