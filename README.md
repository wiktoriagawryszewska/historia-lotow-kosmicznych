# Historia Lotów Kosmicznych

Web application developed in PHP presenting the history of space exploration along with a basic e-commerce module.

---

## Overview

The project combines an informational website with a simple online store. It includes content pages about space exploration and a product management system with a shopping cart and administrative panel.

---

## Features

### Informational Content
- Home page with introduction to space exploration
- Historical overview (Sputnik, Gagarin, etc.)
- Image gallery of key events
- Future of space exploration

### E-commerce Functionality
- Product listing with images and pricing
- Add to cart
- Shopping cart management:
  - update quantities
  - remove items
  - calculate total
- Order finalization with stock update in database

### Administration Panel
- Product management:
  - create
  - edit
  - delete
- Category management
- Content/page management

---

## Technologies

- PHP (procedural approach)
- MySQL / MariaDB
- HTML5, CSS3
- XAMPP (local development environment)

---

## Project Structure

```
v1.8/
│── index.php
│── cfg.php
│── admin/
│   └── admin.php
│── img/
│── css/
│── js/
```

---

## Installation

1. Install XAMPP or equivalent local server  
2. Copy the project directory to:

```
C:\xampp\htdocs\v1.8
```

3. Start services:
- Apache
- MySQL

4. Open in browser:

```
http://localhost/v1.8
```

---

## Database Setup

Create database:

```sql
CREATE DATABASE moja_strona;
```

Required tables:
- `produkty2` – product data
- `kategorie` – product categories
- `page_list` – content pages

---

## Example Query

```sql
UPDATE produkty2
SET ilosc_magazyn = ilosc_magazyn - 1
WHERE id = 1;
```

---

## Known Limitations

- Basic authentication mechanism (no security hardening)
- No validation of stock limits during purchase
- Session-based cart (no persistence across sessions)
- No transactional handling for orders

---

## Possible Improvements

- Implement secure authentication and authorization
- Add input validation and error handling
- Introduce MVC architecture
- Integrate payment processing
- Improve UI/UX using a frontend framework

---

## Author

Wiktoria Gawryszewska  
Computer Science – University Project

---

## License

This project is intended for educational purposes.
