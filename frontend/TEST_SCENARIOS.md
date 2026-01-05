# Comprehensive Test Scenarios

## 1. Functional Testing

### 1.1 Public Website Testing

#### Home Page
- [ ] Home page loads correctly
- [ ] Banner displays with correct image
- [ ] Navigation menu works (all dropdowns)
- [ ] All sections render correctly
- [ ] Images load properly
- [ ] Responsive design works on mobile/tablet/desktop

#### Company Pages
- [ ] About Us page loads with correct content
- [ ] Location page loads with correct content
- [ ] Banner images display correctly
- [ ] Sections render in correct order
- [ ] Media/images display properly

#### Products Pages
- [ ] Feedstocks page loads correctly
- [ ] Methyl Ester page loads correctly
- [ ] Others page loads correctly
- [ ] All product pages have proper banners
- [ ] Content sections display correctly

#### News and Event Pages
- [ ] News page loads correctly
- [ ] Event page loads correctly
- [ ] Content displays properly

#### Contact Page
- [ ] Contact form displays correctly
- [ ] Form validation works (required fields)
- [ ] Form submission works
- [ ] Success message displays after submission
- [ ] Email notification sent (if configured)
- [ ] Contact information displays correctly

### 1.2 CMS Admin Testing

#### Authentication
- [ ] Login page loads correctly
- [ ] Login with valid credentials works
- [ ] Login with invalid credentials shows error
- [ ] Logout works correctly
- [ ] Session persists correctly
- [ ] Protected routes redirect to login

#### Pages Management
- [ ] Pages list displays all pages
- [ ] Create new page works
- [ ] Edit page works
- [ ] Delete page works
- [ ] Page preview works
- [ ] Banner image upload works
- [ ] Banner image preview works
- [ ] Page status (draft/published) works
- [ ] Slug validation works (unique constraint)

#### Sections Management
- [ ] Sections list displays correctly
- [ ] Create section works
- [ ] Edit section works
- [ ] Delete section works
- [ ] Section reordering works
- [ ] Media attachment works
- [ ] Section types work (hero, content_block)

#### Media Management
- [ ] Media library displays all media
- [ ] Upload image works
- [ ] Upload validation works (file type, size)
- [ ] Delete media works
- [ ] Media URL generation works

#### Inquiries Management
- [ ] Inquiries list displays all inquiries
- [ ] View inquiry details works
- [ ] Update inquiry status works
- [ ] Inquiry data displays correctly

#### Settings Management
- [ ] Settings page loads
- [ ] Update settings works
- [ ] Settings persist correctly

## 2. Performance Testing

### 2.1 Page Load Performance
- [ ] Home page loads in < 2 seconds
- [ ] Other pages load in < 2 seconds
- [ ] Cached pages load in < 500ms
- [ ] Database queries are optimized (no N+1)
- [ ] Images load with lazy loading

### 2.2 Caching
- [ ] Page cache works correctly
- [ ] Cache clears on content update
- [ ] Cache headers set correctly
- [ ] Static assets cached properly

### 2.3 Database Performance
- [ ] Database indexes work correctly
- [ ] Query execution time acceptable
- [ ] No slow queries

## 3. Security Testing

### 3.1 Authentication & Authorization
- [ ] Password requirements enforced
- [ ] Session timeout works
- [ ] CSRF protection enabled
- [ ] Admin routes protected
- [ ] Unauthorized access blocked

### 3.2 Input Validation
- [ ] SQL injection prevention
- [ ] XSS prevention
- [ ] File upload validation
- [ ] Input sanitization

### 3.3 Data Protection
- [ ] Sensitive data not exposed
- [ ] Error messages don't leak info
- [ ] Database credentials secure
- [ ] Environment variables protected

## 4. Penetration Testing

### 4.1 SQL Injection
- [ ] Test all input fields
- [ ] Test URL parameters
- [ ] Test search functionality
- [ ] Test pagination

### 4.2 Cross-Site Scripting (XSS)
- [ ] Test stored XSS in forms
- [ ] Test reflected XSS in URLs
- [ ] Test DOM-based XSS
- [ ] Test rich text editor

### 4.3 Cross-Site Request Forgery (CSRF)
- [ ] Test form submissions
- [ ] Test state-changing operations
- [ ] Verify CSRF tokens

### 4.4 Authentication Bypass
- [ ] Test direct URL access
- [ ] Test session hijacking
- [ ] Test password reset
- [ ] Test brute force protection

### 4.5 File Upload Vulnerabilities
- [ ] Test malicious file uploads
- [ ] Test file type validation
- [ ] Test file size limits
- [ ] Test path traversal

### 4.6 Information Disclosure
- [ ] Check error messages
- [ ] Check debug mode
- [ ] Check exposed files
- [ ] Check directory listing

## 5. Browser Compatibility Testing

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile browsers (iOS Safari, Chrome Mobile)

## 6. Responsive Design Testing

- [ ] Desktop (1920x1080)
- [ ] Laptop (1366x768)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

## 7. Accessibility Testing

- [ ] Keyboard navigation
- [ ] Screen reader compatibility
- [ ] Alt text for images
- [ ] Color contrast
- [ ] Form labels

