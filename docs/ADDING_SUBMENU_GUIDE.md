# Guide: Adding a Submenu to Navigation Menu

## Current Menu Implementation Analysis

### Implementation Type: **Hardcoded in Blade Template**

The navigation menu is currently **hardcoded** in the Blade template at:
- **File**: `frontend/resources/views/layouts/app.blade.php`
- **Lines**: 29-55

### Current Menu Structure

The menu uses the following structure:
- **Parent items** with class `nav-dropdown` contain dropdown menus
- **Submenu items** are wrapped in `<ul class="nav-dropdown-menu">`
- **CSS classes** handle dropdown behavior (hover-based)
- **Routes** are defined in `frontend/routes/web.php`

### Existing Menu Items

1. **Company** (dropdown)
   - About Us → `route('company.about-us')`
   - Location → `route('company.location')`

2. **Products** (dropdown)
   - Feedstocks → `route('products.feedstocks')`
   - Methyl Ester → `route('products.methyl-ester')`
   - Others → `route('products.others')`

3. **News and Event** (dropdown)
   - News → `route('news-and-event.news')`
   - Event → `route('news-and-event.event')`

4. **Contact Us** (single link)
   - Contact → `route('contact')`

### Database Model (Not Currently Used)

There is a `NavigationItem` model (`frontend/app/Models/NavigationItem.php`) with parent-child relationships, but it's **not currently being used** in the layout. This suggests a future migration to database-driven menus is planned.

---

## Method 1: Adding Submenu (Current Hardcoded Approach)

This is the **simplest and current approach** used in your codebase.

### Step 1: Add the Route (if needed)

If the submenu item needs a new page, add the route in `frontend/routes/web.php`:

```php
// Example: Adding "Our Team" under Company
Route::prefix('company')->group(function () {
    // ... existing routes ...
    
    Route::get('/our-team', function () {
        return (new PageController())->show('company-our-team');
    })->name('company.our-team');
});
```

### Step 2: Edit the Blade Template

Open `frontend/resources/views/layouts/app.blade.php` and locate the parent menu item (e.g., "Company" dropdown).

**Example: Adding "Our Team" under "Company"**

**Before:**
```blade
<li class="nav-dropdown">
    <a href="#">Company</a>
    <ul class="nav-dropdown-menu">
        <li><a href="{{ route('company.about-us') }}">About Us</a></li>
        <li><a href="{{ route('company.location') }}">Location</a></li>
    </ul>
</li>
```

**After:**
```blade
<li class="nav-dropdown">
    <a href="#">Company</a>
    <ul class="nav-dropdown-menu">
        <li><a href="{{ route('company.about-us') }}">About Us</a></li>
        <li><a href="{{ route('company.location') }}">Location</a></li>
        <li><a href="{{ route('company.our-team') }}">Our Team</a></li>
    </ul>
</li>
```

### Step 3: Verify the Route Exists

Ensure the route name matches what you used in the Blade template. Test by running:

```bash
php artisan route:list | grep company
```

### Complete Example: Adding Multiple Submenus

**Example: Adding "Sustainability" and "Careers" under "Company"**

```blade
<li class="nav-dropdown">
    <a href="#">Company</a>
    <ul class="nav-dropdown-menu">
        <li><a href="{{ route('company.about-us') }}">About Us</a></li>
        <li><a href="{{ route('company.location') }}">Location</a></li>
        <li><a href="{{ route('company.sustainability') }}">Sustainability</a></li>
        <li><a href="{{ route('company.careers') }}">Careers</a></li>
    </ul>
</li>
```

**Corresponding routes in `web.php`:**
```php
Route::prefix('company')->group(function () {
    Route::get('/about-us', function () {
        return (new PageController())->show('company-about-us');
    })->name('company.about-us');
    
    Route::get('/location', function () {
        return (new PageController())->show('company-location');
    })->name('company.location');
    
    Route::get('/sustainability', function () {
        return (new PageController())->show('company-sustainability');
    })->name('company.sustainability');
    
    Route::get('/careers', function () {
        return (new PageController())->show('company-careers');
    })->name('company.careers');
});
```

---

## Method 2: Database-Driven Approach (Future Migration)

If you want to migrate to a database-driven menu system using the existing `NavigationItem` model, follow these steps:

### Step 1: Fix Migration (Important)

The current migration has `parent_id` as a `string`, but it should be a foreign key. Create a new migration to fix this:

```bash
php artisan make:migration fix_navigation_items_parent_id
```

**Migration content:**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
        
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('visible');
            $table->foreign('parent_id')->references('id')->on('navigation_items')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
        
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->string('parent_id')->nullable();
        });
    }
};
```

### Step 2: Create a View Composer or Service Provider

Create a service to load navigation items. Add this to `app/Providers/AppServiceProvider.php`:

```php
use Illuminate\Support\Facades\View;
use App\Models\NavigationItem;

public function boot(): void
{
    View::composer('layouts.app', function ($view) {
        $menuItems = NavigationItem::whereNull('parent_id')
            ->where('visible', true)
            ->with(['children' => function ($query) {
                $query->where('visible', true)->orderBy('order');
            }])
            ->orderBy('order')
            ->get();
        
        $view->with('menuItems', $menuItems);
    });
}
```

### Step 3: Update Blade Template to Use Database

Replace the hardcoded menu in `app.blade.php`:

```blade
<ul class="nav-links" data-nav-links>
    @foreach($menuItems as $item)
        @if($item->children->count() > 0)
            <li class="nav-dropdown">
                <a href="{{ $item->target_url !== '#' ? route($item->target_url) : '#' }}">
                    {{ $item->label }}
                </a>
                <ul class="nav-dropdown-menu">
                    @foreach($item->children as $child)
                        <li>
                            <a href="{{ route($child->target_url) }}">
                                {{ $child->label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @else
            <li>
                <a href="{{ route($item->target_url) }}">
                    {{ $item->label }}
                </a>
            </li>
        @endif
    @endforeach
</ul>
```

### Step 4: Seed or Create Menu Items

Create a seeder or manually insert menu items:

```php
// In DatabaseSeeder or a NavigationSeeder
NavigationItem::create([
    'label' => 'Company',
    'target_url' => '#',
    'order' => 1,
    'visible' => true,
    'parent_id' => null,
]);

$companyId = NavigationItem::where('label', 'Company')->first()->id;

NavigationItem::create([
    'label' => 'About Us',
    'target_url' => 'company.about-us',
    'order' => 1,
    'visible' => true,
    'parent_id' => $companyId,
]);

NavigationItem::create([
    'label' => 'Location',
    'target_url' => 'company.location',
    'order' => 2,
    'visible' => true,
    'parent_id' => $companyId,
]);

// Add new submenu item
NavigationItem::create([
    'label' => 'Our Team',
    'target_url' => 'company.our-team',
    'order' => 3,
    'visible' => true,
    'parent_id' => $companyId,
]);
```

### Step 5: Create Admin Interface (Optional)

If you want CMS-driven menu management, create admin controllers and views for managing `NavigationItem` records.

---

## Where Changes Need to Be Made

### For Current Hardcoded Approach:

1. **Blade Template**: `frontend/resources/views/layouts/app.blade.php`
   - Add new `<li>` item inside the appropriate `nav-dropdown-menu`

2. **Routes File**: `frontend/routes/web.php`
   - Add new route definition if creating a new page

3. **Page Content**: Create the page in CMS (if using dynamic pages)
   - Or create a static view if needed

### For Database-Driven Approach:

1. **Migration**: Fix `parent_id` column type
2. **Service Provider**: Add view composer in `AppServiceProvider`
3. **Blade Template**: Replace hardcoded menu with dynamic loop
4. **Database**: Seed or insert menu items
5. **Admin Panel** (optional): Create CRUD for navigation items

---

## Ensuring Submenu Follows Existing Patterns

### Styling
The existing CSS classes handle all styling:
- `nav-dropdown` - Parent item with dropdown
- `nav-dropdown-menu` - Submenu container
- CSS handles hover behavior automatically

### Active State
Currently, there's no active state logic in the template. If you want to add it:

```blade
<li>
    <a href="{{ route('company.about-us') }}" 
       class="{{ request()->routeIs('company.about-us') ? 'active' : '' }}">
        About Us
    </a>
</li>
```

The CSS already has `.active` styling defined (line 145 in `styles.css`).

### Ordering
For hardcoded approach: Order is determined by HTML order in the template.

For database approach: Use the `order` column when querying.

---

## Common Pitfalls and Notes

### 1. Route Name Mismatch
- **Issue**: Using `route('company.our-team')` but route is named differently
- **Solution**: Always verify route names with `php artisan route:list`

### 2. Missing Route Definition
- **Issue**: Submenu links to a route that doesn't exist
- **Solution**: Ensure route is defined in `web.php` before adding to menu

### 3. Parent Link Behavior
- **Current**: Parent items link to `#` (no page)
- **Note**: If you want parent to link to a page, change `href="#"` to a route

### 4. Mobile Menu Behavior
- The menu uses JavaScript for mobile toggle (`data-nav-toggle`, `data-nav-links`)
- Ensure new items work on mobile by testing

### 5. Database Migration Issue
- **Issue**: `parent_id` is currently a string in migration
- **Impact**: Foreign key constraints won't work properly
- **Solution**: Fix migration before using database approach (see Method 2, Step 1)

### 6. Permissions/Visibility
- **Current**: No permission checks in hardcoded menu
- **Future**: Database approach allows `visible` flag for conditional display

### 7. External Links
- If submenu needs external link, use full URL:
  ```blade
  <li><a href="https://example.com">External Link</a></li>
  ```

### 8. Nested Dropdowns (Multi-level)
- **Current CSS**: Only supports one level of dropdown
- **Note**: Adding 3rd level requires additional CSS/JS modifications

---

## Quick Reference: File Locations

| Component | File Path |
|-----------|-----------|
| Main Layout | `frontend/resources/views/layouts/app.blade.php` |
| Routes | `frontend/routes/web.php` |
| Navigation Model | `frontend/app/Models/NavigationItem.php` |
| Migration | `frontend/database/migrations/2025_12_22_074027_create_navigation_items_table.php` |
| CSS Styles | `frontend/public/css/styles.css` (lines 149-208) |

---

## Recommended Approach

**For immediate needs**: Use **Method 1** (hardcoded Blade) - it's simple, fast, and matches your current implementation.

**For long-term maintainability**: Consider migrating to **Method 2** (database-driven) if you need:
- Admin-managed menus
- Dynamic menu visibility
- User permission-based menus
- Frequent menu changes without code deployment

---

## Example: Complete Workflow

**Scenario**: Add "Sustainability" submenu under "Company"

1. **Add route** in `web.php`:
   ```php
   Route::get('/sustainability', function () {
       return (new PageController())->show('company-sustainability');
   })->name('company.sustainability');
   ```

2. **Edit Blade template** (`app.blade.php`):
   ```blade
   <li class="nav-dropdown">
       <a href="#">Company</a>
       <ul class="nav-dropdown-menu">
           <li><a href="{{ route('company.about-us') }}">About Us</a></li>
           <li><a href="{{ route('company.location') }}">Location</a></li>
           <li><a href="{{ route('company.sustainability') }}">Sustainability</a></li>
       </ul>
   </li>
   ```

3. **Test**: Visit the site and hover over "Company" to see the new submenu item.

4. **Create page content** in CMS admin panel (if using dynamic pages) with slug `company-sustainability`.

Done! ✅
