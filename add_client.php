<?php
/**
 * HIFIS Add Client
 * Form to add new client to the system
 */
require_once 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h2>Add New Client</h2>
        <a href="clients.php" class="btn btn-secondary">Back to Clients</a>
    </div>
    
    <div class="form-container">
        <form id="addClientForm" action="api/add_client.php" method="POST" onsubmit="return submitClientForm('addClientForm');">
            <h3>Personal Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth">
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="Prefer not to say">Prefer not to say</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            
            <h3>Contact Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="e.g., 123-456-7890">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="email@example.com">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="emergency_contact_name">Emergency Contact Name</label>
                    <input type="text" id="emergency_contact_name" name="emergency_contact_name">
                </div>
                
                <div class="form-group">
                    <label for="emergency_contact_phone">Emergency Contact Phone</label>
                    <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone">
                </div>
            </div>
            
            <h3>Additional Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="veteran_status">Veteran Status</label>
                    <select id="veteran_status" name="veteran_status">
                        <option value="Unknown">Unknown</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="disability_status">Disability Status</label>
                    <select id="disability_status" name="disability_status">
                        <option value="Unknown">Unknown</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="household_type">Household Type</label>
                    <select id="household_type" name="household_type">
                        <option value="Individual">Individual</option>
                        <option value="Family">Family</option>
                        <option value="Youth">Youth</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="household_size">Household Size</label>
                    <input type="number" id="household_size" name="household_size" value="1" min="1">
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-success">Save Client</button>
                <a href="clients.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
