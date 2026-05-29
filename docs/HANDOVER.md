# Developer Handover Document - Sungai Sulu Convenience Store

## Project Summary
Inventory system with 2 roles: Admin and Employee.

## Authentication Logic (IMPORTANT)

| Role | Login Credentials | Password Required? |
|------|-------------------|-------------------|
| Admin | user_id | YES (password = admin123) |
| Employee | employee_id | NO (password column is NULL) |

## Stock Logic (IMPORTANT)

**NO current_stock column in products table.**

Current stock = `SELECT COALESCE(SUM(quantity), 0) FROM stock_movements WHERE product_id = ?`

| Action | Insert into stock_movements |
|--------|----------------------------|
| Admin adds stock | quantity = +N, type = 'in' |
| Employee sells | quantity = -N, type = 'out' |

## Permissions

| Action | Admin | Employee |
|--------|-------|----------|
| Add/Edit/Delete Employee | ✅ | ❌ |
| Add/Delete Product | ✅ | ❌ |
| Add Stock (stock_in) | ✅ | ❌ |
| Sell (stock_out) | ✅ | ✅ |
| View Products | ✅ | ✅ |
| View Reports | ✅ | ❌ |

## All SQL Queries You Need

### Dashboard Query
```sql
SELECT 
    p.product_id,
    p.product_name,
    p.price,
    COALESCE(SUM(sm.quantity), 0) AS current_stock,
    p.min_stock,
    CASE 
        WHEN COALESCE(SUM(sm.quantity), 0) <= p.min_stock THEN 'LOW_STOCK'
        ELSE 'OK'
    END AS stock_status
FROM products p
LEFT JOIN stock_movements sm ON sm.product_id = p.product_id
GROUP BY p.product_id;
```
### Monthly Report
```sql
SELECT 
    p.product_id,
    p.product_name,
    COALESCE(SUM(CASE WHEN sm.type = 'in' AND MONTH(sm.created_at) = MONTH(CURDATE()) THEN sm.quantity ELSE 0 END), 0) AS stock_in_this_month,
    COALESCE(SUM(CASE WHEN sm.type = 'out' AND MONTH(sm.created_at) = MONTH(CURDATE()) THEN ABS(sm.quantity) ELSE 0 END), 0) AS stock_out_this_month,
    COALESCE(SUM(CASE WHEN sm.type = 'out' AND MONTH(sm.created_at) = MONTH(CURDATE()) THEN ABS(sm.quantity) * p.price ELSE 0 END), 0) AS sales_this_month
FROM products p
LEFT JOIN stock_movements sm ON sm.product_id = p.product_id
GROUP BY p.product_id;
```
### Add Stock (Admin)
```sql
INSERT INTO stock_movements (product_id, user_id, quantity, type, created_at)
VALUES (?, ?, ?, 'in', NOW());
```
### Sell Product (Employee/Admin)
```sql
INSERT INTO stock_movements (product_id, user_id, quantity, type, created_at)
VALUES (?, ?, ?, 'out', NOW());
```
### Add Employee
```sql
INSERT INTO users (full_name, phone, address, role, employee_id, password)
VALUES (?, ?, ?, 'employee', ?, NULL);
```
### Update Employee (phone + address only)
```sql
UPDATE users SET phone = ?, address = ? WHERE user_id = ? AND role = 'employee';
```
### Delete Employee
```sql
DELETE FROM users WHERE user_id = ? AND role = 'employee';
```
### Add Product (with new category option)
```sql
-- If new category:
INSERT INTO categories (category_name) VALUES (?);
-- Then insert product with that category_id
INSERT INTO products (product_name, price, category_id, min_stock) VALUES (?, ?, ?, ?);
-- Then add initial stock
INSERT INTO stock_movements (product_id, user_id, quantity, type, created_at) VALUES (?, ?, ?, 'in', NOW());
```
