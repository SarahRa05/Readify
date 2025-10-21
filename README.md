# Readify
Readify is a digital library system for managing and borrowing books online.

                       ** MILESTONE 1 **

🧩 Project Overview  
Readify is a single-page web application (SPA) for managing a digital library.  
Users can browse, borrow, and review books, while administrators manage the system.  

Frontend: HTML, CSS, JavaScript (SPApp for routing)  
Backend (upcoming): PHP, FlightPHP, MySQL (PDO)  
Version Control: Git and GitHub 


🗂 Project Structure
readify/
├─ frontend/
│ ├─ index.html
│ ├─ css/style.css
│ ├─ js/app.js
│ ├─ assets/
│ │ ├─ spapp/
│ │ │ ├─ spapp.css
│ │ │ └─ jquery.spapp.js
│ │ └─ erd/ (diagram image here)
│ └─ views/
│ ├─ dashboard.html
│ ├─ catalog.html
│ ├─ myloans.html
│ ├─ admin.html
│ ├─ addbook.html
│ ├─ login.html
│ ├─ register.html
│ └─ 404.html
└─ backend/
├─ routes/ ├─ services/ ├─ dao


📘 Readify — Entity Relationship Diagram (ERD)

![Readify ERD](frontend/assets/erd/readify_simple_erd.png)

🧍 1. Users

| Attribute         | Type                                        | Description             |
| ----------------- | ------------------------------------------- | ----------------------- |
| user_id           | INT (PK, AUTO_INCREMENT)                    | Unique user ID          |
| email             | VARCHAR(255), UNIQUE                        | User email              |
| password_hash     | VARCHAR(255)                                | Encrypted password      |
| full_name         | VARCHAR(255)                                | User’s full name        |
| role              | ENUM('member','admin') DEFAULT 'member'     | Access role             |
| phone             | VARCHAR(20)                                 | Optional contact number |
| address           | TEXT                                        | User address            |
| registration_date | TIMESTAMP DEFAULT CURRENT_TIMESTAMP         | Account creation date   |
| status            | ENUM('active','suspended') DEFAULT 'active' | Account status          |


📚 2. Books

| Attribute        | Type                                  | Description                |
| ---------------- | ------------------------------------- | -------------------------- |
| book_id          | INT (PK, AUTO_INCREMENT)              | Unique book ID             |
| isbn             | VARCHAR(20), UNIQUE                   | ISBN number                |
| title            | VARCHAR(255)                          | Book title                 |
| author           | VARCHAR(255)                          | Author name                |
| publisher        | VARCHAR(255)                          | Publisher name             |
| publication_year | YEAR                                  | Year of publication        |
| category_id      | INT (FK → Categories.category_id)     | Book’s category            |
| description      | TEXT                                  | Short book summary         |
| total_copies     | INT DEFAULT 1                         | Number of copies           |
| available_copies | INT DEFAULT 1                         | Currently available copies |
| cover_image_url  | VARCHAR(500)                          | Book cover image path      |
| created_at       | TIMESTAMP DEFAULT CURRENT_TIMESTAMP   | Created date               |
| updated_at       | TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Updated date               |


🏷️ 3. Categories

| Attribute     | Type                                | Description        |
| ------------- | ----------------------------------- | ------------------ |
| category_id   | INT (PK, AUTO_INCREMENT)            | Unique category ID |
| category_name | VARCHAR(100), UNIQUE                | Category name      |
| description   | TEXT                                | Category details   |
| created_at    | TIMESTAMP DEFAULT CURRENT_TIMESTAMP | Creation date      |


📦 4. Loans

| Attribute     | Type                                                 | Description        |
| ------------- | ---------------------------------------------------- | ------------------ |
| loan_id       | INT (PK, AUTO_INCREMENT)                             | Unique loan record |
| user_id       | INT (FK → Users.user_id)                             | Borrower           |
| book_id       | INT (FK → Books.book_id)                             | Borrowed book      |
| borrow_date   | DATE                                                 | Date borrowed      |
| due_date      | DATE                                                 | Return due date    |
| return_date   | DATE, NULL                                           | Actual return date |
| status        | ENUM('active','returned','overdue') DEFAULT 'active' | Loan status        |
| renewal_count | INT DEFAULT 0                                        | Number of renewals |
| fine_amount   | DECIMAL(10,2) DEFAULT 0.00                           | Fines (if overdue) |
| created_at    | TIMESTAMP DEFAULT CURRENT_TIMESTAMP                  | Record created     |
| updated_at    | TIMESTAMP ON UPDATE CURRENT_TIMESTAMP                | Record updated     |


⭐ 5. Reviews

| Attribute   | Type                                  | Description      |
| ----------- | ------------------------------------- | ---------------- |
| review_id   | INT (PK, AUTO_INCREMENT)              | Unique review ID |
| user_id     | INT (FK → Users.user_id)              | Reviewer         |
| book_id     | INT (FK → Books.book_id)              | Reviewed book    |
| rating      | INT CHECK (1–5)                       | Rating score     |
| review_text | TEXT                                  | Review comment   |
| created_at  | TIMESTAMP DEFAULT CURRENT_TIMESTAMP   | Created date     |
| updated_at  | TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Updated date     |


User → Loan = 1 to many

Book → Loan = 1 to many

Book → Category = many to 1

User → Review = 1 to many

Book → Review = 1 to many


✅ Milestone 1 Deliverables  
- Project folders (frontend / backend / dao / services / routes)  
- Static SPA frontend using SPApp  
- Draft ERD diagram and database plan  
- GitHub repository with collaborator added  

Prepared by: SarahRa05  
International Burch University – Web Programming 2025