// Global CRUD Handler
window.CRUD = {
    // Store modal instances
    modals: {},
    
    // Open modal for any CRUD operation
    openModal: function(config) {
        const {
            modalId = 'globalModal',
            title,
            actionUrl,
            method = 'POST',
            formId = 'globalForm',
            size = 'md', // sm, md, lg, xl
            loadData = null, // Function to load additional data
            onSuccess = null,
            onError = null
        } = config;
        
        const modal = document.getElementById(modalId);
        const modalTitle = modal.querySelector('.modal-title');
        const modalBody = modal.querySelector('.modal-body');
        const submitBtn = modal.querySelector('#submitBtn');
        
        // Reset and setup modal
        modalTitle.textContent = title;
        
        // Set modal size
        const dialog = modal.querySelector('.modal-dialog');
        dialog.className = `modal-dialog modal-dialog-centered modal-${size}`;
        
        // Show loading
        if (modalBody) {
            modalBody.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading...</p>
                </div>
            `;
        }
        
        // Load form content
        fetch(actionUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Render form
            if (modalBody) {
                modalBody.innerHTML = this.renderForm(data, config);
            }
            
            // Set form action and method
            const form = document.getElementById(formId);
            if (form) {
                form.action = data.action || actionUrl;
                const methodField = form.querySelector('input[name="_method"]');
                if (methodField) methodField.value = method;
                else if (method !== 'POST') {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '_method';
                    input.value = method;
                    form.appendChild(input);
                }
            }
            
            // Execute custom load data function
            if (loadData) loadData(data);
            
            // Show modal
            const bsModal = this.getModal(modalId);
            bsModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            if (modalBody) {
                modalBody.innerHTML = `
                    <div class="alert alert-danger m-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        Error loading form. Please try again.
                    </div>
                `;
            }
            if (onError) onError(error);
        });
    },
    
    // Render form based on type
    renderForm: function(data, config) {
        const formId = config.formId || 'globalForm';
        
        if (config.formTemplate) {
            // Use custom template if provided
            return config.formTemplate(data);
        }
        
        // Default dynamic form renderer
        if (data.fields) {
            return this.buildForm(data.fields, formId, config);
        }
        
        // If HTML is returned directly
        return data.html || '<form id="'+formId+'" method="POST"></form>';
    },
    
    // Build form dynamically from field definitions
    buildForm: function(fields, formId, config) {
        let html = `<form id="${formId}" method="POST">`;
        html += `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">`;
        
        fields.forEach(field => {
            html += `
                <div class="mb-3">
                    <label class="form-label">${field.label}</label>
                    <input type="${field.type || 'text'}" 
                           name="${field.name}" 
                           class="form-control ${field.class || ''}"
                           value="${field.value || ''}"
                           ${field.required ? 'required' : ''}
                           placeholder="${field.placeholder || ''}">
                    ${field.help ? `<small class="form-text text-muted">${field.help}</small>` : ''}
                </div>
            `;
        });
        
        html += `
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-check2"></i> ${config.submitText || 'Save'}
                </button>
            </div>
        </form>`;
        
        return html;
    },
    
    // Submit form via AJAX
    submitForm: function(formId, options = {}) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        const formData = new FormData(form);
        const submitBtn = form.querySelector('#submitBtn');
        const originalText = submitBtn?.innerHTML;
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';
        }
        
        fetch(form.action, {
            method: form.querySelector('input[name="_method"]')?.value || 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = form.closest('.modal');
                if (modal) {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    bsModal.hide();
                }
                
                // Show success message
                this.showAlert('success', data.message || 'Operation completed successfully');
                
                // Reload page or update table
                if (options.reload !== false) {
                    setTimeout(() => location.reload(), 1500);
                } else if (options.onSuccess) {
                    options.onSuccess(data);
                }
            } else {
                this.showFormErrors(form, data.errors);
                if (options.onError) options.onError(data);
            }
        })
        .catch(error => {
            this.showAlert('error', 'An error occurred. Please try again.');
            if (options.onError) options.onError(error);
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    },
    
    // Show form validation errors
    showFormErrors: function(form, errors) {
        // Remove existing errors
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        
        // Add new errors
        for (let field in errors) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = errors[field][0];
                input.parentNode.appendChild(errorDiv);
            }
        }
    },
    
    // Show alert messages
    showAlert: function(type, message) {
        const alertHtml = `
            <div class="alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" 
                 style="z-index: 9999; min-width: 300px;" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', alertHtml);
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) alert.remove();
        }, 3000);
    },
    
    // Confirm delete
    confirmDelete: function(config) {
        const {
            url,
            itemName,
            onSuccess = null,
            method = 'DELETE'
        } = config;
        
        const confirmHtml = `
            <div class="modal fade" id="tempDeleteModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <i class="bi bi-exclamation-triangle" style="font-size: 48px; color: #dc2626;"></i>
                            <p class="mt-2">Are you sure you want to delete <strong>${itemName}</strong>?</p>
                            <small class="text-muted">This action cannot be undone.</small>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        const existingModal = document.getElementById('tempDeleteModal');
        if (existingModal) existingModal.remove();
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', confirmHtml);
        const modalElement = document.getElementById('tempDeleteModal');
        const modal = new bootstrap.Modal(modalElement);
        
        // Handle delete confirmation
        document.getElementById('confirmDeleteBtn').onclick = function() {
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                modal.hide();
                if (data.success) {
                    CRUD.showAlert('success', data.message || 'Deleted successfully');
                    if (onSuccess) onSuccess(data);
                    else setTimeout(() => location.reload(), 1500);
                } else {
                    CRUD.showAlert('error', data.message || 'Delete failed');
                }
            })
            .catch(error => {
                modal.hide();
                CRUD.showAlert('error', 'An error occurred');
            });
        };
        
        modal.show();
        
        // Clean up modal when hidden
        modalElement.addEventListener('hidden.bs.modal', function() {
            modalElement.remove();
        });
    },
    
    // Get or create modal instance
    getModal: function(modalId) {
        const modalElement = document.getElementById(modalId);
        if (!this.modals[modalId]) {
            this.modals[modalId] = new bootstrap.Modal(modalElement);
        }
        return this.modals[modalId];
    }
};

// Auto-initialize forms with AJAX submission
document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF token meta tag if not exists
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = document.querySelector('input[name="_token"]')?.value || '';
        document.head.appendChild(meta);
    }
    
    // Global form submission handler
    document.body.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.dataset.ajax === 'true') {
            e.preventDefault();
            CRUD.submitForm(form.id, {
                reload: form.dataset.reload !== 'false',
                onSuccess: window[form.dataset.onSuccess],
                onError: window[form.dataset.onError]
            });
        }
    });
});