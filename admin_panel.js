// Admin Panel JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize admin panel
    initAdminPanel();
    updateCurrentDate();
    setupNavigationHandlers();
    setupSearchAndFilters();
    setupTableInteractions();
    setupModalHandlers();
});

// Initialize the admin panel
function initAdminPanel() {
    console.log('Panneau d\'administration AVEC AMOUR initialisé');
    
    // Show dashboard by default
    showSection('dashboard');
    
    // Set active navigation item
    const dashboardNav = document.querySelector('[data-section="dashboard"]').closest('.nav-item');
    if (dashboardNav) {
        dashboardNav.classList.add('active');
    }
}

// Update current date display
function updateCurrentDate() {
    const dateElement = document.getElementById('currentDate');
    if (dateElement) {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        dateElement.textContent = now.toLocaleDateString('fr-FR', options);
    }
}

// Setup navigation handlers
function setupNavigationHandlers() {
    const navLinks = document.querySelectorAll('.nav-item a[data-section]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const sectionId = this.getAttribute('data-section');
            
            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Add active class to clicked nav item
            this.closest('.nav-item').classList.add('active');
            
            // Show corresponding section
            showSection(sectionId);
        });
    });
    
    // Logout button handler
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                // Redirect to login page or main site
                window.location.href = 'admin_login.php';
            }
        });
    }
}

// Show specific section
function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.admin-section').forEach(section => {
        section.classList.remove('active');
    });
    
    // Show target section
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active');
        
        // Load section-specific data
        switch(sectionId) {
            case 'dashboard':
                loadDashboardData();
                break;
            case 'products':
                loadProductsData();
                break;
            case 'orders':
                loadOrdersData();
                break;
            case 'customers':
                loadCustomersData();
                break;
            case 'inventory':
                loadInventoryData();
                break;
            case 'analytics':
                loadAnalyticsData();
                break;
            case 'settings':
                loadSettingsData();
                break;
        }
    }
}

// Setup search and filter handlers
function setupSearchAndFilters() {
    // Product search
    const productSearch = document.querySelector('#products .search-bar input');
    if (productSearch) {
        productSearch.addEventListener('input', function() {
            filterProducts(this.value);
        });
    }
    
    // Order search
    const orderSearch = document.querySelector('#orders .search-bar input');
    if (orderSearch) {
        orderSearch.addEventListener('input', function() {
            filterOrders(this.value);
        });
    }
    
    // Filter selects
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            const section = this.closest('.admin-section').id;
            if (section === 'products') {
                filterProducts();
            } else if (section === 'orders') {
                filterOrders();
            }
        });
    });
}

// Setup table interactions
function setupTableInteractions() {
    // Edit buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-btn')) {
            const row = e.target.closest('tr');
            const section = e.target.closest('.admin-section').id;
            
            if (section === 'products') {
                editProduct(row);
            } else if (section === 'orders') {
                editOrder(row);
            }
        }
        
        // Delete buttons
        if (e.target.closest('.delete-btn')) {
            const row = e.target.closest('tr');
            const section = e.target.closest('.admin-section').id;
            
            if (confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                if (section === 'products') {
                    deleteProduct(row);
                }
            }
        }
        
        // View buttons
        if (e.target.closest('.view-btn')) {
            const row = e.target.closest('tr');
            const section = e.target.closest('.admin-section').id;
            
            if (section === 'orders') {
                viewOrderDetails(row);
            }
        }
        
        // Add product button
        if (e.target.closest('.add-product-btn')) {
            showAddProductModal();
        }
    });
}

// Data loading functions
function loadDashboardData() {
    // Simulate loading dashboard data
    console.log('Chargement des données du tableau de bord...');
    
    // You can add AJAX calls here to load real data
    // Example:
    // fetch('/api/dashboard-stats')
    //     .then(response => response.json())
    //     .then(data => updateDashboardStats(data));
}

function loadProductsData() {
    console.log('Chargement des données des produits...');
    // Load products data
}

function loadOrdersData() {
    console.log('Chargement des données des commandes...');
    // Load orders data
}

function loadCustomersData() {
    console.log('Chargement des données des clients...');
    // Load customers data
}

function loadInventoryData() {
    console.log('Chargement des données de l\'inventaire...');
    // Load inventory data
}

function loadAnalyticsData() {
    console.log('Chargement des données d\'analyse...');
    // Load analytics data
}

function loadSettingsData() {
    console.log('Chargement des paramètres...');
    // Load settings data
}

// Filter functions
function filterProducts(searchTerm = '') {
    const table = document.querySelector('#products .products-table table tbody');
    const rows = table.querySelectorAll('tr');
    const categoryFilter = document.querySelector('#products .filter-select').value;
    const statusFilter = document.querySelectorAll('#products .filter-select')[1].value;
    
    rows.forEach(row => {
        const productName = row.cells[1].textContent.toLowerCase();
        const category = row.cells[2].textContent.toLowerCase();
        const status = row.querySelector('.status').className;
        
        const matchesSearch = productName.includes(searchTerm.toLowerCase());
        const matchesCategory = !categoryFilter || category.includes(categoryFilter);
        const matchesStatus = !statusFilter || status.includes(statusFilter);
        
        if (matchesSearch && matchesCategory && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterOrders(searchTerm = '') {
    const table = document.querySelector('#orders .orders-table table tbody');
    const rows = table.querySelectorAll('tr');
    const statusFilter = document.querySelector('#orders .filter-select').value;
    
    rows.forEach(row => {
        const orderId = row.cells[0].textContent.toLowerCase();
        const customerName = row.cells[1].textContent.toLowerCase();
        const status = row.querySelector('.status').className;
        
        const matchesSearch = orderId.includes(searchTerm.toLowerCase()) || 
                            customerName.includes(searchTerm.toLowerCase());
        const matchesStatus = !statusFilter || status.includes(statusFilter);
        
        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Product management functions
function editProduct(row) {
    const productName = row.cells[1].textContent;
    const category = row.cells[2].textContent;
    const price = row.cells[3].textContent;
    const stock = row.cells[4].textContent;
    
    // Show edit modal with product data
    showProductModal({
        name: productName,
        category: category,
        price: price,
        stock: stock,
        isEdit: true
    });
}

function deleteProduct(row) {
    // Remove row from table
    row.remove();
    
    // You can add AJAX call here to delete from server
    showNotification('Produit supprimé avec succès', 'success');
}

function showAddProductModal() {
    showProductModal({
        isEdit: false
    });
}

function showProductModal(data = {}) {
    // Create modal HTML
    const modalHTML = `
        <div class="modal-overlay">
            <div class="modal">
                <div class="modal-header">
                    <h3>${data.isEdit ? 'Modifier le Produit' : 'Ajouter un Produit'}</h3>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="product-form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="product-name">Nom du Produit</label>
                            <input type="text" id="product-name" value="${data.name || ''}" required>
                        </div>
                        <div class="form-group">
                            <label for="product-category">Catégorie</label>
                            <select id="product-category" required>
                                <option value="">Sélectionner une catégorie</option>
                                <option value="fleurs" ${data.category === 'Fleurs' ? 'selected' : ''}>Fleurs</option>
                                <option value="resine" ${data.category === 'Résine' ? 'selected' : ''}>Résine</option>
                                <option value="concentres" ${data.category === 'Concentrés' ? 'selected' : ''}>Concentrés</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="product-description">Description</label>
                            <textarea id="product-description" rows="3" placeholder="Description du produit...">${data.description || ''}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="product-price">Prix (€)</label>
                            <input type="number" id="product-price" value="${data.price ? data.price.replace(' €', '') : ''}" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="product-stock">Stock</label>
                            <input type="number" id="product-stock" value="${data.stock || ''}" required>
                        </div>
                        <div class="form-group">
                            <label for="product-image">Image du Produit</label>
                            <input type="file" id="product-image" accept="image/*">
                            <small class="form-hint">Formats acceptés: JPG, PNG, WEBP (max 5MB)</small>
                        </div>
                        <div class="form-group">
                            <label for="product-video">Vidéo du Produit (optionnel)</label>
                            <input type="file" id="product-video" accept="video/*">
                            <small class="form-hint">Formats acceptés: MP4, WEBM, MOV (max 50MB)</small>
                        </div>
                        <div class="form-group">
                            <label for="product-image-link">Lien Image (optionnel)</label>
                            <input type="url" id="product-image-link" placeholder="https://exemple.com/image.jpg" value="${data.imageLink || ''}">
                            <small class="form-hint">Lien alternatif vers une image hébergée en ligne</small>
                        </div>
                        <div class="form-group">
                            <label for="product-video-link">Lien Vidéo (optionnel)</label>
                            <input type="url" id="product-video-link" placeholder="https://exemple.com/video.mp4" value="${data.videoLink || ''}">
                            <small class="form-hint">Lien alternatif vers une vidéo hébergée en ligne</small>
                        </div>
                        <div class="media-preview" id="mediaPreview" style="display: none;">
                            <h4>Aperçu des médias:</h4>
                            <div class="preview-container"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary cancel-btn">Annuler</button>
                    <button class="btn-primary save-btn">${data.isEdit ? 'Modifier' : 'Ajouter'}</button>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Setup file preview handlers
    setupFilePreviewHandlers();
    
    // Setup modal handlers
    setupModalHandlers();
}

function setupModalHandlers() {
    // Close modal handlers
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('close-modal') || 
            e.target.classList.contains('cancel-btn') || 
            e.target.classList.contains('modal-overlay')) {
            closeModal();
        }
        
        if (e.target.classList.contains('save-btn')) {
            saveProduct();
        }
    });
}

function closeModal() {
    const modal = document.querySelector('.modal-overlay');
    if (modal) {
        modal.remove();
    }
}

function setupFilePreviewHandlers() {
    const imageInput = document.getElementById('product-image');
    const videoInput = document.getElementById('product-video');
    const imageLinkInput = document.getElementById('product-image-link');
    const videoLinkInput = document.getElementById('product-video-link');
    const previewContainer = document.getElementById('mediaPreview');
    
    // Image file preview
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            handleFilePreview(e.target, 'image');
        });
    }
    
    // Video file preview
    if (videoInput) {
        videoInput.addEventListener('change', function(e) {
            handleFilePreview(e.target, 'video');
        });
    }
    
    // Image link preview
    if (imageLinkInput) {
        imageLinkInput.addEventListener('input', function(e) {
            handleLinkPreview(e.target.value, 'image');
        });
    }
    
    // Video link preview
    if (videoLinkInput) {
        videoLinkInput.addEventListener('input', function(e) {
            handleLinkPreview(e.target.value, 'video');
        });
    }
}

function handleFilePreview(input, type) {
    const file = input.files[0];
    if (!file) return;
    
    // Check file size
    const maxSize = type === 'video' ? 50 * 1024 * 1024 : 5 * 1024 * 1024; // 50MB for video, 5MB for image
    if (file.size > maxSize) {
        showNotification(`Fichier trop volumineux. Taille maximum: ${type === 'video' ? '50MB' : '5MB'}`, 'error');
        input.value = '';
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        updatePreview(e.target.result, type, file.name);
    };
    reader.readAsDataURL(file);
}

function handleLinkPreview(url, type) {
    if (!url) {
        updatePreview(null, type);
        return;
    }
    
    // Validate URL format
    try {
        new URL(url);
        updatePreview(url, type);
    } catch {
        // Invalid URL, clear preview
        updatePreview(null, type);
    }
}

function updatePreview(src, type, fileName = '') {
    const previewContainer = document.getElementById('mediaPreview');
    const previewContent = previewContainer.querySelector('.preview-container');
    
    if (!src) {
        // Hide preview if no source
        if (previewContent.children.length === 0) {
            previewContainer.style.display = 'none';
        }
        return;
    }
    
    // Show preview container
    previewContainer.style.display = 'block';
    
    // Remove existing preview of same type
    const existingPreview = previewContent.querySelector(`[data-type="${type}"]`);
    if (existingPreview) {
        existingPreview.remove();
    }
    
    // Create preview element
    const previewWrapper = document.createElement('div');
    previewWrapper.className = 'media-preview-item';
    previewWrapper.setAttribute('data-type', type);
    
    let mediaElement;
    if (type === 'image') {
        mediaElement = document.createElement('img');
        mediaElement.src = src;
        mediaElement.alt = fileName || 'Preview';
        mediaElement.style.cssText = 'max-width: 200px; max-height: 150px; border-radius: 8px; object-fit: cover;';
    } else if (type === 'video') {
        mediaElement = document.createElement('video');
        mediaElement.src = src;
        mediaElement.controls = true;
        mediaElement.style.cssText = 'max-width: 200px; max-height: 150px; border-radius: 8px;';
    }
    
    const labelElement = document.createElement('div');
    labelElement.textContent = `${type === 'image' ? 'Image' : 'Vidéo'}: ${fileName || 'Lien externe'}`;
    labelElement.style.cssText = 'font-size: 0.9rem; color: #ed6eba; margin-bottom: 0.5rem;';
    
    previewWrapper.appendChild(labelElement);
    previewWrapper.appendChild(mediaElement);
    previewContent.appendChild(previewWrapper);
}

function saveProduct() {
    const form = document.querySelector('.product-form');
    
    // Validate form
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Create FormData for file uploads
    const formData = new FormData();
    
    // Get form values
    const productData = {
        name: document.getElementById('product-name').value,
        category: document.getElementById('product-category').value,
        description: document.getElementById('product-description').value,
        price: document.getElementById('product-price').value,
        stock: document.getElementById('product-stock').value,
        imageLink: document.getElementById('product-image-link').value,
        videoLink: document.getElementById('product-video-link').value
    };
    
    // Add text data to FormData
    Object.keys(productData).forEach(key => {
        formData.append(key, productData[key]);
    });
    
    // Add files to FormData
    const imageFile = document.getElementById('product-image').files[0];
    const videoFile = document.getElementById('product-video').files[0];
    
    if (imageFile) {
        formData.append('productImage', imageFile);
    }
    
    if (videoFile) {
        formData.append('productVideo', videoFile);
    }
    
    // Show loading state
    const saveBtn = document.querySelector('.save-btn');
    const originalText = saveBtn.textContent;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="ri-loader-4-line"></i> Sauvegarde...';
    
    // Ajouter l'action pour l'API
    formData.append('action', 'upload_product');
    
    // Envoyer à l'API réelle
    fetch('upload_config.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 413) {
                throw new Error('Fichier trop volumineux. Veuillez réduire la taille du fichier ou utiliser un lien externe.');
            }
            throw new Error('Erreur lors de la sauvegarde');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Reset button
            saveBtn.disabled = false;
            saveBtn.textContent = originalText;
            
            // Close modal and show success message
            closeModal();
            showNotification(data.message, 'success');
            
            // Refresh products table
            loadProductsData();
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        // Reset button
        saveBtn.disabled = false;
        saveBtn.textContent = originalText;
        
        showNotification(error.message, 'error');
        console.error('Erreur:', error);
    });
    
    // Example of how to send to server with proper handling of large files:
    /*
    fetch('/api/products', {
        method: 'POST',
        body: formData,
        // Don't set Content-Type header, let browser set it with boundary for multipart/form-data
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 413) {
                throw new Error('Fichier trop volumineux. Veuillez réduire la taille du fichier.');
            }
            throw new Error('Erreur lors de la sauvegarde');
        }
        return response.json();
    })
    .then(data => {
        saveBtn.disabled = false;
        saveBtn.textContent = originalText;
        closeModal();
        showNotification('Produit sauvegardé avec succès', 'success');
        loadProductsData();
    })
    .catch(error => {
        saveBtn.disabled = false;
        saveBtn.textContent = originalText;
        showNotification(error.message, 'error');
    });
    */
}

// Order management functions
function editOrder(row) {
    const orderId = row.cells[0].textContent;
    console.log('Editing order:', orderId);
    // Implement order editing logic
}

function viewOrderDetails(row) {
    const orderId = row.cells[0].textContent;
    console.log('Viewing order details:', orderId);
    // Implement order details view
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button class="close-notification">&times;</button>
    `;
    
    // Add notification styles if not exists
    if (!document.querySelector('.notification-styles')) {
        const styles = document.createElement('style');
        styles.className = 'notification-styles';
        styles.textContent = `
            .notification {
                position: fixed;
                top: 120px;
                right: 20px;
                background: rgba(114, 113, 113, 0.95);
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                border-left: 4px solid #ed6eba;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                z-index: 1001;
                display: flex;
                align-items: center;
                gap: 1rem;
                animation: slideIn 0.3s ease;
            }
            .notification-success { border-left-color: #2ecc71; }
            .notification-error { border-left-color: #e74c3c; }
            .notification-warning { border-left-color: #f1c40f; }
            .close-notification {
                background: none;
                border: none;
                color: white;
                font-size: 1.2rem;
                cursor: pointer;
                padding: 0;
            }
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(styles);
    }
    
    document.body.appendChild(notification);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
    
    // Close button handler
    notification.querySelector('.close-notification').addEventListener('click', () => {
        notification.remove();
    });
}

// Mobile sidebar toggle
function toggleSidebar() {
    const sidebar = document.querySelector('.admin-sidebar');
    sidebar.classList.toggle('open');
}

// Add mobile menu button for responsive design
if (window.innerWidth <= 768) {
    const header = document.querySelector('.admin-header .admin-nav');
    const menuButton = document.createElement('button');
    menuButton.className = 'mobile-menu-btn';
    menuButton.innerHTML = '<i class="ri-menu-line"></i>';
    menuButton.addEventListener('click', toggleSidebar);
    header.appendChild(menuButton);
}