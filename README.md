# sungaisulu_store_inventory
Laravel inventory system for convenience store - Group Project


```markdown
# Sungai Sulu Convenience Store

## Developer Setup

### 1. Import Database
- File location: `/database/convenience_store.sql`
- Import into MySQL database named `convenience_store`

### 2. Read Handover Document
- Full documentation: `/docs/HANDOVER.md`
- Contains: Authentication rules, stock logic, all SQL queries

### 3. Default Logins
- Admin: user_id=1, password=admin123
- Employee: employee_id=EMP001 (no password)
- Employee: employee_id=EMP002 (no password)

### 4. Key Rules
- Current stock = SUM(quantity) FROM stock_movements
- Positive quantity = stock in | Negative quantity = stock out
