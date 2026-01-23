# CMS Navigation Management Guide

## ✅ Yes, You Can Now Add Submenus from the CMS!

The navigation menu can now be fully managed through the CMS admin panel. You can add, edit, delete, and reorder menu items and submenus without touching code.

## How to Access Navigation Management

1. **Login to Admin Panel**: Go to `/admin/login`
2. **Navigate to Navigation**: Click "Navigation" in the admin menu (between "Media" and "Inquiries")
3. **Manage Menu Items**: You'll see all top-level menu items with their submenus listed below

## Adding a New Submenu Item

### Step 1: Access Navigation Management
- Go to `/admin/navigation` in your admin panel
- Click "Add Menu Item" button

### Step 2: Fill in the Form
- **Menu Label**: The text that appears in the menu (e.g., "Our Team", "Sustainability")
- **Target URL / Route Name**: 
  - For internal pages: Use route name (e.g., `company.about-us`)
  - For external links: Use full URL (e.g., `https://example.com`)
  - For parent items with dropdowns: Use `#`
- **Parent Menu Item**: 
  - Select a parent (e.g., "Company") to create a submenu
  - Leave as "None" for top-level menu items
- **Display Order**: Lower numbers appear first (0 = first)
- **Visible**: Check to show in menu, uncheck to hide

### Step 3: Save
Click "Create Navigation Item" to save.

## Example: Adding "Our Team" under "Company"

1. Go to `/admin/navigation/create`
2. Fill in:
   - **Label**: `Our Team`
   - **Target URL**: `company.our-team` (assuming this route exists)
   - **Parent Menu Item**: Select "Company"
   - **Order**: `3` (if "About Us" is 1 and "Location" is 2)
   - **Visible**: ✓ Checked
3. Click "Create Navigation Item"

The submenu will appear immediately in the frontend navigation!

## Features

### ✅ What You Can Do
- **Create top-level menu items** (e.g., "Company", "Products")
- **Create submenu items** under any parent
- **Edit** existing menu items
- **Delete** menu items (with validation - can't delete if it has children)
- **Reorder** items using the "Display Order" field
- **Show/Hide** items using the "Visible" checkbox
- **Link to routes** or external URLs
- **View hierarchy** - see parent items with their submenus listed below

### 🔒 Safety Features
- Cannot delete a parent item if it has submenu items
- Prevents circular references (can't set a child as its own parent)
- Route validation - system checks if routes exist
- Graceful fallback - if database is empty, falls back to hardcoded menu

## How It Works

### Database-Driven Navigation
The system now uses the `navigation_items` table to store menu configuration:
- **Parent items** have `parent_id = NULL`
- **Submenu items** have `parent_id` pointing to their parent
- Items are ordered by the `order` field
- Only `visible = true` items are shown

### Automatic Integration
- The layout (`layouts/app.blade.php`) automatically loads menu items from the database
- If no items exist in the database, it falls back to the hardcoded menu
- This ensures backward compatibility

## Migration Required

Before using the CMS navigation, you need to run the migration to fix the `parent_id` column:

```bash
php artisan migrate
```

This will:
- Change `parent_id` from `string` to `unsignedBigInteger`
- Add proper foreign key constraints
- Enable cascade deletion (deleting a parent deletes children)

## Initial Setup

### Option 1: Start Fresh
Create all menu items from scratch in the CMS.

### Option 2: Migrate Existing Menu
If you want to migrate your existing hardcoded menu to the database:

1. Go to `/admin/navigation/create`
2. Create parent items first (e.g., "Company", "Products")
3. Then create submenu items, selecting the appropriate parent

**Example Migration:**
1. Create "Company" (parent: None, order: 1, URL: `#`)
2. Create "About Us" (parent: Company, order: 1, URL: `company.about-us`)
3. Create "Location" (parent: Company, order: 2, URL: `company.location`)
4. Repeat for other menu sections

## Troubleshooting

### Menu Not Showing
- **Check visibility**: Ensure "Visible" is checked
- **Check database**: Verify items exist in `navigation_items` table
- **Check order**: Lower order numbers appear first
- **Check routes**: Ensure route names are correct

### Route Not Found Error
- Verify the route exists in `routes/web.php`
- Use `php artisan route:list` to see all available routes
- For external links, use full URL starting with `http://` or `https://`

### Submenu Not Appearing
- Verify the parent item is selected correctly
- Check that both parent and child have `visible = true`
- Ensure the parent item has `target_url = '#'` (for dropdown behavior)

### Can't Delete Item
- If item has submenu items, delete or reassign children first
- System prevents orphaned submenu items

## Technical Details

### Files Created/Modified

**New Files:**
- `app/Http/Controllers/Admin/NavigationController.php` - CRUD operations
- `resources/views/admin/navigation/index.blade.php` - List view
- `resources/views/admin/navigation/create.blade.php` - Create form
- `resources/views/admin/navigation/edit.blade.php` - Edit form
- `database/migrations/2025_12_23_120000_fix_navigation_items_parent_id.php` - Migration fix

**Modified Files:**
- `routes/web.php` - Added navigation routes
- `app/Providers/AppServiceProvider.php` - Added view composer
- `resources/views/layouts/app.blade.php` - Updated to use database navigation
- All admin views - Added "Navigation" link to menu

### Database Schema
```sql
navigation_items
- id (bigint, primary key)
- label (string) - Menu text
- target_url (string) - Route name or URL
- order (integer) - Display order
- visible (boolean) - Show/hide
- parent_id (unsignedBigInteger, nullable, FK) - For submenus
- timestamps
```

## Best Practices

1. **Naming Convention**: Use descriptive labels (e.g., "About Us" not "About")
2. **Ordering**: Use increments of 10 (0, 10, 20) to allow easy reordering
3. **Route Names**: Always use named routes for internal links
4. **Testing**: After creating items, test the frontend to ensure links work
5. **Backup**: Before major changes, consider backing up the `navigation_items` table

## Next Steps

1. **Run Migration**: `php artisan migrate`
2. **Create Menu Items**: Use the CMS to build your navigation
3. **Test**: Verify menu appears correctly on frontend
4. **Customize**: Adjust order and visibility as needed

---

**Note**: The system supports both database-driven and hardcoded navigation. If the database is empty, it automatically falls back to the hardcoded menu in the Blade template.
