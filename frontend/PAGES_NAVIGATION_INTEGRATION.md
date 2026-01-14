# Pages & Navigation Integration

## Overview

The Pages and Navigation features are now **fully integrated** in the CMS. You can link navigation items directly to pages, and the system will automatically generate the correct route names.

## Integration Features

### 1. Database Relationship

- **New Column:** `page_id` added to `navigation_items` table
- **Foreign Key:** Links to `pages` table (nullable, on delete set null)
- **Model Relationship:** `NavigationItem` has `belongsTo(Page::class)` relationship

### 2. Page Selector in Navigation Forms

When creating or editing navigation items, you now have:

**"Link to Page" Dropdown:**
- Lists all published pages
- Shows page title and slug for easy identification
- Optional field - you can still enter routes manually
- Auto-fills route name when page is selected

### 3. Automatic Route Name Generation

When you select a page, the system automatically converts the page slug to the correct route name:

| Page Slug | Generated Route Name |
|-----------|---------------------|
| `home` | `home` |
| `company-about-us` | `company.about-us` |
| `company-location` | `company.location` |
| `company-sustainability` | `company.sustainability` |
| `product-feedstocks` | `product.feedstocks` |
| `contact-us-fulfill-form` | `contact-us.fulfill-form` |

### 4. Enhanced Navigation Index

The navigation index now shows:
- Which page is linked to each navigation item
- Clickable link to edit the linked page
- Clear indication of the relationship

## How to Use

### Creating a Navigation Item Linked to a Page

1. Go to `/admin/navigation/create`
2. Fill in **Menu Label** (e.g., "About Us")
3. **Select a Page** from the "Link to Page" dropdown
   - The route name will automatically fill in
4. Select **Parent Menu Item** (if creating a submenu)
5. Set **Order** and **Visibility**
6. Click "Create Navigation Item"

### Manual Route Entry (Still Supported)

You can still enter routes manually:
1. Leave "Link to Page" as "None"
2. Manually type the route name in "Target URL" field
3. Or enter an external URL

### Editing Navigation Items

- If a page is already linked, it will be pre-selected
- You can change the linked page or remove the link
- Route name updates automatically when page changes

## Benefits

✅ **Error Prevention**
- No more typos in route names
- Ensures route names match actual routes

✅ **Easier Management**
- Visual selection instead of manual typing
- See which pages are linked to navigation

✅ **Consistency**
- Route names always match page slugs
- Automatic conversion handles all cases

✅ **Flexibility**
- Can still enter routes manually for external links
- Can link to pages or use custom routes

## Technical Details

### Migration

**File:** `2025_12_23_130000_add_page_id_to_navigation_items.php`
- Adds `page_id` column (nullable, unsignedBigInteger)
- Adds foreign key constraint
- On delete: set null (preserves navigation item if page is deleted)

### Model Updates

**NavigationItem Model:**
```php
public function page()
{
    return $this->belongsTo(Page::class);
}
```

### Controller Logic

**Route Name Generation:**
- `slugToRouteName()` method converts page slugs to route names
- Handles special cases (home, contact-us prefix, etc.)
- Used when page is selected in form

**Validation:**
- `page_id` is optional
- If provided, must exist in `pages` table
- Auto-generates `target_url` from page slug

### JavaScript Functionality

**Auto-fill on Page Selection:**
- When page dropdown changes, route name field is auto-filled
- Uses route mapping passed from server
- Works in both create and edit forms

## Workflow Example

### Scenario: Add "Sustainability" to Company Menu

**Step 1: Create the Page**
1. Go to `/admin/pages/create`
2. Create page with slug: `company-sustainability`
3. Add content and publish

**Step 2: Create Navigation Item**
1. Go to `/admin/navigation/create`
2. Label: `Sustainability`
3. **Link to Page:** Select "Sustainability (company-sustainability)"
4. Route auto-fills: `company.sustainability`
5. Parent: Select "Company"
6. Save

**Result:** Navigation item is created and linked to the page. Route name is automatically correct.

## Backward Compatibility

- Existing navigation items without `page_id` continue to work
- Manual route entry still supported
- No breaking changes to existing functionality
- Migration is safe (adds nullable column)

## Database Schema

```sql
navigation_items
- id
- label
- target_url
- page_id (NEW - nullable, FK → pages.id)
- order
- visible
- parent_id
- timestamps
```

## Files Modified

1. **Migration:** `database/migrations/2025_12_23_130000_add_page_id_to_navigation_items.php` (NEW)
2. **Model:** `app/Models/NavigationItem.php` - Added `page_id` to fillable, added `page()` relationship
3. **Controller:** `app/Http/Controllers/Admin/NavigationController.php`
   - Added `Page` model import
   - Load pages in `create()` and `edit()` methods
   - Added `slugToRouteName()` method
   - Added `getPageRouteMap()` method
   - Updated `store()` and `update()` to handle `page_id`
4. **Views:**
   - `resources/views/admin/navigation/create.blade.php` - Added page selector
   - `resources/views/admin/navigation/edit.blade.php` - Added page selector
   - `resources/views/admin/navigation/index.blade.php` - Show linked pages

## Testing Checklist

- [x] Migration runs successfully
- [x] Can create navigation item with page selected
- [x] Route name auto-fills when page is selected
- [x] Can create navigation item without page (manual route)
- [x] Can edit navigation item and change linked page
- [x] Navigation index shows linked pages
- [x] Can click through to edit linked page from navigation index
- [x] Parent items can still have empty target_url (defaults to #)
- [x] Child items require target_url (either from page or manual)

## Next Steps

After running the migration, you can:
1. Edit existing navigation items to link them to pages
2. Create new navigation items using the page selector
3. Verify links work correctly on the frontend
