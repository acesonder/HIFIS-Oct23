/**
 * HIFIS Main JavaScript
 * Ajax functionality and interactive features
 */

// Utility function for Ajax requests
function ajaxRequest(url, method, data, successCallback, errorCallback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (successCallback) successCallback(response);
            } catch (e) {
                if (successCallback) successCallback(xhr.responseText);
            }
        } else {
            if (errorCallback) errorCallback(xhr.status, xhr.statusText);
        }
    };
    
    xhr.onerror = function() {
        if (errorCallback) errorCallback(xhr.status, xhr.statusText);
    };
    
    if (method === 'POST') {
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(data);
    } else {
        xhr.send();
    }
}

// Show alert message
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.container') || document.body;
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Confirm dialog
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Load shelter capacity data (real-time updates)
function loadShelterCapacity() {
    ajaxRequest('api/shelter_capacity.php', 'GET', null, function(response) {
        if (response.success) {
            updateShelterDisplay(response.data);
        }
    });
}

function updateShelterDisplay(data) {
    const container = document.getElementById('shelter-capacity');
    if (!container) return;
    
    let html = '<div class="stats-grid">';
    data.forEach(shelter => {
        const percentage = (shelter.occupied / shelter.total_capacity * 100).toFixed(0);
        const statusClass = percentage > 90 ? 'danger' : percentage > 70 ? 'warning' : 'success';
        
        html += `
            <div class="stat-card ${statusClass}">
                <h3>${shelter.resource_name}</h3>
                <div class="stat-number">${shelter.available}</div>
                <div class="stat-label">Available (${shelter.occupied}/${shelter.total_capacity})</div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

// Search clients
function searchClients(query) {
    const data = 'query=' + encodeURIComponent(query);
    
    ajaxRequest('api/search_clients.php', 'POST', data, function(response) {
        if (response.success) {
            displaySearchResults(response.data);
        }
    });
}

function displaySearchResults(clients) {
    const container = document.getElementById('search-results');
    if (!container) return;
    
    if (clients.length === 0) {
        container.innerHTML = '<p>No clients found.</p>';
        return;
    }
    
    let html = '<table><thead><tr><th>ID</th><th>Name</th><th>DOB</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
    
    clients.forEach(client => {
        html += `
            <tr>
                <td>${client.unique_identifier}</td>
                <td>${client.first_name} ${client.last_name}</td>
                <td>${client.date_of_birth}</td>
                <td><span class="badge badge-${client.status === 'active' ? 'success' : 'secondary'}">${client.status}</span></td>
                <td>
                    <a href="client_details.php?id=${client.client_id}" class="btn btn-primary btn-sm">View</a>
                </td>
            </tr>
        `;
    });
    
    html += '</tbody></table>';
    container.innerHTML = html;
}

// Update client status
function updateClientStatus(clientId, status) {
    const data = 'client_id=' + clientId + '&status=' + encodeURIComponent(status);
    
    ajaxRequest('api/update_client_status.php', 'POST', data, function(response) {
        if (response.success) {
            showAlert('Client status updated successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('Error updating client status', 'error');
        }
    });
}

// Add service record
function addServiceRecord(clientId, serviceId, notes) {
    const data = 'client_id=' + clientId + 
                 '&service_id=' + serviceId + 
                 '&notes=' + encodeURIComponent(notes);
    
    ajaxRequest('api/add_service.php', 'POST', data, function(response) {
        if (response.success) {
            showAlert('Service record added successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('Error adding service record', 'error');
        }
    });
}

// Load dashboard statistics
function loadDashboardStats() {
    ajaxRequest('api/dashboard_stats.php', 'GET', null, function(response) {
        if (response.success) {
            updateDashboardStats(response.data);
        }
    });
}

function updateDashboardStats(stats) {
    if (document.getElementById('total-clients')) {
        document.getElementById('total-clients').textContent = stats.total_clients || 0;
    }
    if (document.getElementById('active-clients')) {
        document.getElementById('active-clients').textContent = stats.active_clients || 0;
    }
    if (document.getElementById('services-today')) {
        document.getElementById('services-today').textContent = stats.services_today || 0;
    }
    if (document.getElementById('shelter-occupancy')) {
        document.getElementById('shelter-occupancy').textContent = stats.shelter_occupancy + '%' || '0%';
    }
}

// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'red';
            isValid = false;
        } else {
            input.style.borderColor = '#ddd';
        }
    });
    
    return isValid;
}

// Initialize real-time updates
function initializeRealTimeUpdates() {
    // Update shelter capacity every 30 seconds
    if (document.getElementById('shelter-capacity')) {
        loadShelterCapacity();
        setInterval(loadShelterCapacity, 30000);
    }
    
    // Update dashboard stats every 60 seconds
    if (document.getElementById('dashboard-stats')) {
        loadDashboardStats();
        setInterval(loadDashboardStats, 60000);
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Initialize real-time updates
    initializeRealTimeUpdates();
    
    // Search functionality
    const searchInput = document.getElementById('client-search');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value;
            if (query.length >= 2) {
                searchClients(query);
            }
        });
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    };
});
