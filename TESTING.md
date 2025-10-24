# HIFIS Platform - Testing Checklist

This checklist helps verify all features are working correctly after installation.

## Prerequisites
- [ ] MySQL database created and schema imported
- [ ] Database credentials configured in `includes/config.php`
- [ ] Web server running (Apache/Nginx)
- [ ] PHP 7.4+ installed with mysqli extension
- [ ] Project accessible via browser

## 1. Authentication Tests

### Login System
- [ ] Navigate to login page (http://localhost/HIFIS-Oct23)
- [ ] Test invalid login credentials (should show error)
- [ ] Test valid login with admin/admin123
- [ ] Verify redirect to dashboard after login
- [ ] Check user info displayed in header
- [ ] Test logout functionality
- [ ] Verify redirect to login after logout

### Session Management
- [ ] After login, verify session persists across pages
- [ ] Test access to protected pages when not logged in
- [ ] Verify redirect to login for unauthorized access

## 2. Dashboard Tests

### Statistics Display
- [ ] Verify "Total Clients" count displays
- [ ] Verify "Active Clients" count displays
- [ ] Verify "Services Today" count displays
- [ ] Verify "Shelter Occupancy" percentage displays
- [ ] Check color coding of stat cards

### Real-time Features
- [ ] Wait 60 seconds and verify stats auto-refresh
- [ ] Open browser console and check for Ajax requests
- [ ] Verify no errors in console

### Content Display
- [ ] Check "Shelter Resources" section displays
- [ ] Verify "Recent Clients" table shows data
- [ ] Verify "Recent Activity" log displays
- [ ] Check all navigation links are clickable

## 3. Client Management Tests

### Add New Client
- [ ] Click "Clients" in navigation
- [ ] Click "Add New Client" button
- [ ] Fill in all required fields
- [ ] Submit form
- [ ] Verify success message
- [ ] Verify client appears in list
- [ ] Check unique ID was generated

### Search Functionality
- [ ] Use search box to find client by name
- [ ] Search by client ID
- [ ] Test partial name matching
- [ ] Verify results update without page reload

### Filter by Status
- [ ] Filter by "Active" status
- [ ] Filter by "Inactive" status
- [ ] Filter by "Exited" status
- [ ] Test "All" filter

### View Client Details
- [ ] Click "View" on any client
- [ ] Verify all client information displays
- [ ] Check personal information section
- [ ] Check service information section
- [ ] Verify navigation back to clients list works

## 4. Service Management Tests

### View Services
- [ ] Navigate to "Services" page
- [ ] Verify all service types display
- [ ] Check usage counts show
- [ ] Verify service statistics section

### Add Service to Client
- [ ] From client details, click "Add Service"
- [ ] Select a service type
- [ ] Enter service date
- [ ] Add notes
- [ ] Submit form
- [ ] Verify redirect to client details
- [ ] Check service appears in client's service history

## 5. Resource Management Tests

### Shelter Capacity
- [ ] Navigate to "Resources" page
- [ ] Verify all shelter resources display
- [ ] Check capacity cards show correct data
- [ ] Verify color coding (red >90%, yellow >70%, green ≤70%)
- [ ] Wait 30 seconds and verify auto-refresh
- [ ] Check detailed capacity table

## 6. Assessment Tests

### View Assessments
- [ ] Navigate to "Assessments" page
- [ ] Verify assessments list displays
- [ ] Check priority distribution statistics

### Add Assessment
- [ ] From client details, click "Add Assessment"
- [ ] Enter assessment date
- [ ] Enter vulnerability score (0-100)
- [ ] Select housing priority
- [ ] Add assessment notes
- [ ] Submit form
- [ ] Verify assessment appears in client details

## 7. Reports Tests

### Generate Reports
- [ ] Navigate to "Reports" page
- [ ] Set custom date range
- [ ] Click "Generate Report"
- [ ] Verify statistics update
- [ ] Check demographics section
- [ ] Verify service breakdown displays

### Data Display
- [ ] Check gender distribution table
- [ ] Verify special populations data
- [ ] Check service type breakdown
- [ ] Verify privacy notice displays

## 8. User Management Tests (Admin Only)

### View Users
- [ ] Navigate to "Users" page
- [ ] Verify all users display
- [ ] Check user roles show correctly
- [ ] Verify last login times
- [ ] Check active/inactive status badges

## 9. Ajax Functionality Tests

### Real-time Updates
- [ ] Open browser developer tools (F12)
- [ ] Go to Network tab
- [ ] Navigate to dashboard
- [ ] Wait for auto-refresh
- [ ] Verify Ajax requests to `/api/dashboard_stats.php`
- [ ] Go to resources page
- [ ] Verify Ajax requests to `/api/shelter_capacity.php`

### Client Search
- [ ] On clients page, type in search box
- [ ] Verify Ajax request to `/api/search_clients.php`
- [ ] Check results appear without page reload
- [ ] Verify smooth user experience

## 10. Responsive Design Tests

### Desktop View
- [ ] Test on full-screen browser
- [ ] Verify layout looks good
- [ ] Check all elements are visible
- [ ] Test all navigation works

### Tablet View
- [ ] Resize browser to tablet width (~768px)
- [ ] Verify responsive layout activates
- [ ] Check navigation adapts
- [ ] Test functionality

### Mobile View
- [ ] Resize browser to mobile width (~375px)
- [ ] Verify mobile layout
- [ ] Check touch-friendly buttons
- [ ] Test scrolling and navigation

## 11. Security Tests

### Access Control
- [ ] Without login, try accessing dashboard.php directly
- [ ] Verify redirect to login
- [ ] Login as non-admin user (if you create one)
- [ ] Try accessing users.php
- [ ] Verify access denied for non-admin

### Input Validation
- [ ] Try submitting forms with empty required fields
- [ ] Verify validation messages
- [ ] Test XSS prevention (try entering `<script>alert('test')</script>`)
- [ ] Verify input is sanitized

### SQL Injection Prevention
- [ ] In search box, try `' OR '1'='1`
- [ ] Verify no SQL errors
- [ ] Verify prepared statements prevent injection

## 12. Database Tests

### Data Integrity
- [ ] Add a client and verify in database
- [ ] Add a service and check relationships
- [ ] Delete a client (if implemented) and check cascading
- [ ] Verify timestamps are set correctly

### Activity Logging
- [ ] Perform various actions (add client, service, etc.)
- [ ] Check `activity_log` table in database
- [ ] Verify all actions are logged
- [ ] Check IP addresses are recorded

## 13. Error Handling Tests

### Form Errors
- [ ] Submit empty forms
- [ ] Verify error messages display
- [ ] Check error styling (red borders, alerts)

### Database Errors
- [ ] Temporarily break database connection
- [ ] Verify graceful error handling
- [ ] Restore connection

## 14. Browser Compatibility Tests

### Test in Multiple Browsers
- [ ] Google Chrome
- [ ] Mozilla Firefox
- [ ] Safari (if available)
- [ ] Microsoft Edge
- [ ] Verify consistent functionality across all

## 15. Performance Tests

### Page Load Times
- [ ] Test initial page load speed
- [ ] Check dashboard loads quickly
- [ ] Verify Ajax requests are fast
- [ ] Test with multiple browser tabs

### Database Performance
- [ ] Add 50+ test clients
- [ ] Verify list still loads quickly
- [ ] Test search performance
- [ ] Check reports generate fast

## Test Results Summary

**Date Tested:** _______________
**Tested By:** _______________
**Environment:** _______________

### Results
- Total Tests: _______________
- Passed: _______________
- Failed: _______________
- Notes: _______________

### Issues Found
1. _______________
2. _______________
3. _______________

### Recommendations
1. _______________
2. _______________
3. _______________

---

## Automated Test Commands (Future Enhancement)

```bash
# Database validation
mysql -u root -p -e "USE hifis_db; SHOW TABLES;"

# File permissions check
ls -la /path/to/HIFIS-Oct23/

# PHP syntax check
find . -name "*.php" -exec php -l {} \;

# Test database connection
php -r "require 'includes/db.php'; echo 'Connection successful!';"
```

## Notes

- All checkboxes should be checked before considering the platform production-ready
- Document any issues found during testing
- Retest after fixing any bugs
- Perform regression testing after updates

**Remember: Testing is crucial for ensuring a reliable system!**
