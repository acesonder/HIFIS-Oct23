/**
 * HIFIS Main JavaScript
 * Handles Ajax requests and UI interactions
 */

// Utility function for Ajax requests
function ajaxRequest(url, method, data, successCallback, errorCallback) {
    const xhr = new XMLHttpRequest();
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (successCallback) successCallback(response);
                } catch (e) {
                    if (successCallback) successCallback(xhr.responseText);
                }
            } else {
                if (errorCallback) {
                    errorCallback(xhr.status, xhr.statusText);
                } else {
                    showAlert('error', 'Request failed: ' + xhr.statusText);
                }
            }
        }
    };
    
    xhr.open(method, url, true);
    
    if (method === 'POST') {
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        const params = new URLSearchParams(data).toString();
        xhr.send(params);
    } else {
        xhr.send();
    }
}

// Show alert messages
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 
                       type === 'error' ? 'alert-error' : 'alert-info';
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
}

// Search functionality with Ajax
function searchClients(searchTerm) {
    const searchUrl = 'api/search_clients.php?q=' + encodeURIComponent(searchTerm);
    
    ajaxRequest(searchUrl, 'GET', null, function(response) {
        if (response.success) {
            updateClientTable(response.clients);
        }
    });
}

// Update client table with search results
function updateClientTable(clients) {
    const tbody = document.querySelector('#clientsTable tbody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (clients.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center">No clients found</td></tr>';
        return;
    }
    
    clients.forEach(client => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${escapeHtml(client.client_id)}</td>
            <td>${escapeHtml(client.first_name)}</td>
            <td>${escapeHtml(client.last_name)}</td>
            <td>${escapeHtml(client.date_of_birth || 'N/A')}</td>
            <td>${escapeHtml(client.gender || 'N/A')}</td>
            <td>${escapeHtml(client.phone || 'N/A')}</td>
            <td>${escapeHtml(client.household_type || 'N/A')}</td>
            <td>
                <a href="client_details.php?id=${client.client_id}" class="btn btn-primary btn-sm">View</a>
                <a href="edit_client.php?id=${client.client_id}" class="btn btn-secondary btn-sm">Edit</a>
                <button onclick="deleteClient(${client.client_id})" class="btn btn-danger btn-sm">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Delete client with Ajax
function deleteClient(clientId) {
    if (!confirm('Are you sure you want to delete this client? This action cannot be undone.')) {
        return;
    }
    
    ajaxRequest('api/delete_client.php', 'POST', { client_id: clientId }, function(response) {
        if (response.success) {
            showAlert('success', 'Client deleted successfully');
            // Reload the clients table
            location.reload();
        } else {
            showAlert('error', response.message || 'Failed to delete client');
        }
    });
}

// Load dashboard statistics with Ajax
function loadDashboardStats() {
    ajaxRequest('api/dashboard_stats.php', 'GET', null, function(response) {
        if (response.success) {
            updateDashboardStats(response.stats);
        }
    });
}

// Update dashboard statistics
function updateDashboardStats(stats) {
    const totalClientsEl = document.getElementById('totalClients');
    const activeServicesEl = document.getElementById('activeServices');
    const newClientsEl = document.getElementById('newClients');
    
    if (totalClientsEl) totalClientsEl.textContent = stats.total_clients || '0';
    if (activeServicesEl) activeServicesEl.textContent = stats.active_services || '0';
    if (newClientsEl) newClientsEl.textContent = stats.new_clients_month || '0';
}

// Form validation
function validateClientForm() {
    const firstName = document.getElementById('first_name');
    const lastName = document.getElementById('last_name');
    
    if (!firstName || !lastName) return true;
    
    if (firstName.value.trim() === '') {
        showAlert('error', 'First name is required');
        firstName.focus();
        return false;
    }
    
    if (lastName.value.trim() === '') {
        showAlert('error', 'Last name is required');
        lastName.focus();
        return false;
    }
    
    return true;
}

// Submit client form with Ajax
function submitClientForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    if (!validateClientForm()) {
        return false;
    }
    
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading"></span> Saving...';
    }
    
    ajaxRequest(form.action, 'POST', data, function(response) {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Client';
        }
        
        if (response.success) {
            showAlert('success', 'Client saved successfully');
            setTimeout(() => {
                window.location.href = 'clients.php';
            }, 1500);
        } else {
            showAlert('error', response.message || 'Failed to save client');
        }
    }, function() {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Client';
        }
    });
    
    return false;
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, m => map[m]);
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

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard stats if on dashboard page
    if (document.getElementById('totalClients')) {
        loadDashboardStats();
        // Refresh stats every 30 seconds
        setInterval(loadDashboardStats, 30000);
    }
    
    // Setup search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchClients(this.value);
            }, 500); // Debounce search
        });
    }
});
