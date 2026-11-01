#  Sungai Sulu Store Inventory Management System

A web-based inventory management system built with Laravel 10 for convenience stores to manage products, track stock, process sales, and generate reports.

---

##  About The Project

Sungai Sulu Store Inventory Management System is a complete solution designed to replace manual paper-based inventory tracking with an automated digital system. It helps store owners track stock in real-time, receive low stock alerts, process sales efficiently, and make data-driven business decisions.


---

##  Features

| Feature | Description |
|---------|-------------|
| **🔐 Authentication** | Login with email/password, role-based access (Admin & Cashier) |
| **📦 Product Management** | Add, edit, view, and delete products with categories |
| **📊 Stock Management** | Real-time stock tracking with automatic updates |
| **⚠️ Low Stock Alerts** | Automatic notifications when stock falls below reorder level |
| **🛒 Sales Processing** | Fast checkout with automatic stock deduction |
| **📈 Sales Reports** | Daily, weekly, and monthly sales reports |
| **👥 Employee Management** | Manage cashier accounts (Admin only) |
| **🗄️ Centralized Database** | All records stored securely in one place |

---

##  Built With

| Technology | Version |
|------------|---------|
| [Laravel](https://laravel.com/) | 10.x |
| [PHP](https://www.php.net/) | 8.1+ |
| [MySQL](https://www.mysql.com/) / SQLite | 8.0 / Latest |
| [TailwindCSS](https://tailwindcss.com/) | 3.x |
| [Laravel Breeze](https://laravel.com/docs/10.x/starter-kits#breeze) | 10.x |

---


##  Default Login Credentials


| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@example.com | password |
| **Cashier** | cashier@example.com | password |


---

##  Database Schema

| Table | Description |
|-------|-------------|
| `users` | Store employee/admin accounts |
| `products` | Product information |
| `categories` | Product categories |
| `stock_movements` | Track stock in/out transactions |
| `sales` | Sales transactions header |
| `sale_items` | Sales transaction details |
| `suppliers` | Supplier information |

---

##  System Workflow

```
1. Admin adds product & sets stock
   Login → Products → Add New → Fill Form → Save

2. Cashier processes sale → Auto stock deduction
   Login → New Sale → Search Product → Add to Cart → Checkout
   ⚡ System automatically deducts stock

3. Low stock alert triggered
   Stock < Reorder Level → Alert on Admin Dashboard
   ⚠️ Admin notified to restock

4. Admin views reports
   Login → Reports → Select Daily/Weekly/Monthly
   📊 View sales trends and make decisions
```

---

##  Project Structure

```
sungaisulu_store_inventory/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Application controllers
│   │   └── Middleware/       # Role-based access middleware
│   └── Models/               # Eloquent models
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Sample data seeders
├── resources/
│   └── views/                # Blade templates
├── routes/
│   ├── web.php               # Web routes
│   └── api.php               # API routes
├── public/                   # Public assets
├── .env                      # Environment configuration
└── README.md                 # This file
```

---

##  Team Members

| Role | Member | Responsibilities |
|------|--------|------------------|
| **Project Manager** | Erma | Task management, integration, documentation |
| **Database Manager** | Amni | Database design, migrations, models |
| **Backend Developer** | Anis | Controllers, business logic, validation |
| **Frontend Developer** | Aisyah| Blade templates, CSS, UI/UX |
| **Auth & Testing** | Sarah | Authentication, roles, testing |

---

##  Future Enhancements

- [ ] Barcode scanning integration
- [ ] Mobile application
- [ ] Supplier management module
- [ ] Expiry date tracking
- [ ] Predictive analytics
- [ ] E-invoicing (email receipts)

---

##  License

This project is developed for educational purposes as part of a university project.

---

##  Acknowledgments

- [Laravel Documentation](https://laravel.com/docs)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)
- Dr. Salem Abdullah Salem Garfan

---

##  Contact

**Project Team**

- **GitHub:** Amni(shark-ftw)
- **Email:** d20231106400@siswwa.upsi.edu.my


---

