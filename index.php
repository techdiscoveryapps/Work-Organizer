<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Organizer • Daily Activity Tracker</title>
    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome 6.5.1 for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom dark theme styles -->
    <link rel="stylesheet" href="style.css">
    <style>
        .delete-btn-hover {
            transition: all 0.3s ease;
        }
        .delete-btn-hover:hover {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
        }
        .delete-btn-hover:active {
            background-color: #bb2d3b !important;
            border-color: #bb2d3b !important;
            color: white !important;
        }
    </style>
</head>
<body class="bg-dark">

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <i class="fas fa-tasks me-2"></i>
            <span class="text-gradient">Work Organizer</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="navigateTo('dashboard'); return false;">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="navigateTo('activities'); return false;">
                        <i class="fas fa-list me-1"></i> Activities
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="navigateTo('stats'); return false;">
                        <i class="fas fa-chart-bar me-1"></i> Statistics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#creditsModal">
                        <i class="fas fa-heart text-danger me-1"></i> About
                    </a>
                </li>
            </ul>
            <span class="navbar-text text-muted d-none d-lg-inline me-3" id="today-full-date"></span>
        </div>
    </div>
</nav>

<div class="container py-4">
    <!-- Bootstrap Alert Container -->
    <div id="alertContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1050; max-width: 350px;">
        <!-- Alerts will be dynamically added here -->
    </div>

    <!-- ==================== DASHBOARD SECTION ==================== -->
    <div id="dashboard" class="section">
        <div class="section-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="text-center text-md-start">
                    <h2 class="mb-2 fade-in">
                        <i class="fas fa-clipboard-check me-2 text-gradient"></i>
                        Today's Activities
                    </h2>
                    <p class="text-muted mb-0">Track your daily progress and stay productive</p>
                </div>
                <div class="d-flex flex-column gap-2 w-100 w-md-auto align-items-center align-items-md-end" style="max-width: 200px;">
                    <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#activityModal" onclick="resetActivityModal()">
                        <i class="fas fa-plus me-1"></i> Add Activity
                    </button>
                    <button class="btn btn-outline-primary btn-sm w-100" onclick="showMarkAllModal()">
                        <i class="fas fa-check-double me-1"></i> Mark All Complete
                    </button>
                    <button class="btn btn-outline-primary btn-sm w-100" onclick="refreshDashboard()">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Progress -->
        <div class="card mb-4 shadow-sm border-gradient scale-in progress-container">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="fw-semibold" id="progress-text">0 of 0 completed</span>
                        <div class="text-muted small mt-1">Keep up the great work!</div>
                    </div>
                    <div class="text-end">
                        <span id="progress-perc" class="text-success fw-bold fs-4">0%</span>
                        <div class="text-muted small">Progress</div>
                    </div>
                </div>
                <div class="progress" style="height: 16px;">
                    <div id="progress-bar" class="progress-bar bg-success" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Activities grid - fully responsive cards with animations -->
        <div class="row g-3" id="dashboard-grid">
            <!-- Populated by JavaScript -->
        </div>
        
            </div>

    <!-- ==================== MY ACTIVITIES SECTION ==================== -->
    <div id="activities" class="section d-none">
        <div class="section-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="text-center text-md-start">
                    <h2 class="mb-2">
                        <i class="fas fa-tasks me-2 text-gradient"></i>
                        My Activities
                    </h2>
                    <p class="text-muted mb-0">Manage your daily tasks and activities</p>
                </div>
                <div class="d-flex flex-column gap-2 w-100 w-md-auto align-items-center align-items-md-end" style="max-width: 200px;">
                    <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#activityModal" onclick="resetActivityModal()">
                        <i class="fas fa-plus me-1"></i> Add Activity
                    </button>
                    <div class="position-relative w-100">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y text-muted small ms-3"></i>
                        <input type="text" id="activities-search" class="form-control form-control-sm ps-5" placeholder="Search..." oninput="filterActivitiesList()">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-3" id="activities-list-grid">
            <!-- Populated by JavaScript -->
        </div>
    </div>

    <!-- ==================== STATS SECTION ==================== -->
    <div id="stats" class="section d-none">
        <div class="section-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="text-center text-md-start">
                    <h2 class="mb-2">
                        <i class="fas fa-chart-bar me-2 text-gradient"></i>
                        Monthly Statistics
                    </h2>
                    <p class="text-muted mb-0">Analyze your productivity patterns and trends</p>
                </div>
                <div class="d-flex flex-column flex-md-row gap-2 w-100 w-md-auto align-items-center align-items-md-end" style="max-width: 410px;">
                    <div class="w-100" style="max-width: 200px;">
                        <select id="stats-year" class="form-select form-select-sm w-100 text-center"></select>
                    </div>
                    <div class="w-100" style="max-width: 200px;">
                        <select id="stats-month" class="form-select form-select-sm w-100 text-center"></select>
                    </div>
                </div>
            </div>
        </div>

        <div id="stats-content">
            <!-- Populated by JavaScript -->
        </div>
    </div>

</div>

<!-- ==================== MODALS ==================== -->

<!-- Activity Add / Edit Modal -->
<div class="modal fade" id="activityModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">
                    <i class="fas fa-plus-circle me-2"></i>New Activity
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit-id">
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-tag me-1"></i>Activity Name
                    </label>
                    <input type="text" id="edit-name" class="form-control" placeholder="e.g. Create YouTube video" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-align-left me-1"></i>Description (optional)
                    </label>
                    <textarea id="edit-desc" class="form-control" rows="3" placeholder="Record & edit video..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-palette me-1"></i>Choose an icon
                    </label>
                    <div style="max-height: 200px; overflow-y: auto; overflow-x: hidden;">
                        <div class="row g-2 text-center">
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-video"><i class="fas fa-video"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-code"><i class="fas fa-code"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-book"><i class="fas fa-book"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-dumbbell"><i class="fas fa-dumbbell"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-music"><i class="fas fa-music"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-paint-brush"><i class="fas fa-paint-brush"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-heart"><i class="fas fa-heart"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-star"><i class="fas fa-star"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-pen"><i class="fas fa-pen"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-graduation-cap"><i class="fas fa-graduation-cap"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-users"><i class="fas fa-users"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-laptop-code"><i class="fas fa-laptop-code"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-calendar-check"><i class="fas fa-calendar-check"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-envelope"><i class="fas fa-envelope"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-comments"><i class="fas fa-comments"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-coffee"><i class="fas fa-coffee"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-brain"><i class="fas fa-brain"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-chart-line"><i class="fas fa-chart-line"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-shopping-cart"><i class="fas fa-shopping-cart"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-phone"><i class="fas fa-phone"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-camera"><i class="fas fa-camera"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-gamepad"><i class="fas fa-gamepad"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-utensils"><i class="fas fa-utensils"></i></button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary icon-option" data-icon="fas fa-car"><i class="fas fa-car"></i></button></div>
                        </div>
                    </div>
                    <input type="hidden" id="edit-icon" value="fas fa-tasks">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="deleteBtn" class="btn btn-outline-danger d-none" onclick="deleteFromActivityModal()">
                    <i class="fas fa-trash me-1"></i>Delete
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="saveActivity()">
                    <i class="fas fa-save me-1"></i>Save Activity
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Complete / Undo Modal -->
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i id="completeModalIcon" class="fas fa-question-circle fa-3x text-gradient"></i>
                </div>
                <p id="completeModalText" class="fs-5"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" id="confirmBtn" class="btn btn-success" onclick="confirmCompletion()">
                    <i class="fas fa-check me-1"></i>Yes, mark done today
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trash me-2"></i>Delete Activity
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i id="deleteModalIcon" class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                </div>
                <p id="deleteModalText" class="fs-5 mb-3"></p>
                <div id="deleteActivityInfo" class="text-start">
                    <!-- Populated by JS -->
                </div>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone and will remove all completion history for this activity.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" id="deleteConfirmBtn" class="btn btn-danger" onclick="confirmDeleteActivity()">
                    <i class="fas fa-trash me-1"></i>Delete Activity
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Mark All Complete Modal -->
<div class="modal fade" id="markAllModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-double me-2"></i>Mark All Complete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-tasks fa-3x text-gradient"></i>
                </div>
                <p id="markAllModalText" class="fs-5 mb-3"></p>
                <div id="markAllActivityList" class="text-start">
                    <!-- Populated by JS -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" id="markAllConfirmBtn" class="btn btn-success" onclick="confirmMarkAllComplete()">
                    <i class="fas fa-check-double me-1"></i>Mark All Complete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==================== ABOUT MODAL ==================== -->
<div class="modal fade" id="creditsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-astronaut me-2 text-gradient"></i>About the Developer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3 shadow-glow" style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--accent-primary), var(--accent-success)) !important;">
                        <i class="fas fa-rocket fa-3x text-white"></i>
                    </div>
                    <h4>Tech Discovery Apps</h4>
                    <p class="text-muted">Developing tools to make life easier.</p>
                </div>
                
                <div class="row g-2 mb-4" id="social-links-container">
                    <!-- Populated by JS -->
                </div>

                <div class="pt-3 border-top">
                    <h6 class="mb-3 text-muted">Support the Project</h6>
                    <a href="https://buymeacoffee.com/techdiscoveryapps" target="_blank" class="bmc-button">
                        <img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" alt="Buy Me A Coffee" style="height: 50px !important; width: 180px !important;">
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
// ====================== GLOBAL VARIABLES ======================
const creditsData = [
    {
        "name": "Google Play",
        "url": "https://play.google.com/store/apps/developer?id=Tech+Discovery+Apps",
        "icon": "fa-brands fa-google-play",
        "title": "Google Play Store",
        "color": "#607d8b"
    },
    {
        "name": "GitHub",
        "url": "https://github.com/techdiscoveryapps",
        "icon": "fa-brands fa-github",
        "title": "GitHub",
        "color": "#ffffff"
    },
    {
        "name": "Telegram",
        "url": "https://t.me/TechDiscoveryCommunity",
        "icon": "fa-brands fa-telegram",
        "title": "Telegram Community Group",
        "color": "#0088cc"
    },
    {
        "name": "YouTube",
        "url": "https://www.youtube.com/@techdiscoverylinks",
        "icon": "fa-brands fa-youtube",
        "title": "Canale YouTube",
        "color": "#ff0000"
    },
    {
        "name": "Instagram",
        "url": "https://www.instagram.com/techdiscoverylinks",
        "icon": "fa-brands fa-instagram",
        "title": "Instagram",
        "color": "#e1306c"
    },
    {
        "name": "X",
        "url": "https://x.com/techdiscoveryl",
        "icon": "fa-brands fa-x-twitter",
        "title": "X (Twitter)",
        "color": "#ffffff"
    },
    {
        "name": "Pinterest",
        "url": "https://it.pinterest.com/techdiscoverylinks",
        "icon": "fa-brands fa-pinterest",
        "title": "Pinterest",
        "color": "#bd081c"
    },
    {
        "name": "Coffee",
        "url": "https://buymeacoffee.com/techdiscoveryapps",
        "icon": "fas fa-coffee",
        "title": "Buy Me a Coffee",
        "color": "#ffdd00"
    }
];
let allActivities = [];
let todayCompletions = [];
let currentDateStr = '';
let selectedIcon = 'fas fa-tasks';

// ====================== HELPER FUNCTIONS ======================
function getToday() {
    const today = new Date();
    const day = String(today.getDate()).padStart(2, '0');
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const year = today.getFullYear();
    return `${day}-${month}-${year}`;
}

function formatDate(dateString) {
    // Handle both old YYYY-MM-DD and new DD-MM-YYYY formats
    let date;
    if (dateString.includes('-') && dateString.split('-').length === 3) {
        const parts = dateString.split('-');
        if (parts[0].length === 4) {
            // Old format: YYYY-MM-DD
            date = new Date(parts[0], parts[1] - 1, parts[2]);
        } else {
            // New format: DD-MM-YYYY
            date = new Date(parts[2], parts[1] - 1, parts[0]);
        }
    } else {
        date = new Date(dateString);
    }
    
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

function formatFullDate(dateString) {
    // Handle both old YYYY-MM-DD and new DD-MM-YYYY formats
    let date;
    if (dateString.includes('-') && dateString.split('-').length === 3) {
        const parts = dateString.split('-');
        if (parts[0].length === 4) {
            // Old format: YYYY-MM-DD
            date = new Date(parts[0], parts[1] - 1, parts[2]);
        } else {
            // New format: DD-MM-YYYY
            date = new Date(parts[2], parts[1] - 1, parts[0]);
        }
    } else {
        date = new Date(dateString);
    }
    
    const options = { 
        weekday: 'long', 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric' 
    };
    return new Intl.DateTimeFormat('en-GB', options).format(date);
}

function getMonthName() {
    return new Intl.DateTimeFormat('en-US', { month: 'long' }).format(new Date());
}

function getCurrentYear() {
    return new Date().getFullYear();
}

function showLoading(element) {
    element.classList.add('loading');
}

function hideLoading(element) {
    element.classList.remove('loading');
}

function animateCard(card, animation = 'fade-in') {
    card.classList.add(animation);
    setTimeout(() => card.classList.remove(animation), 500);
}

// ====================== ALERT SYSTEM ======================
function showAlert(message, type = 'info', autoHide = true) {
    const alertContainer = document.getElementById('alertContainer');
    const alertId = 'alert-' + Date.now();
    
    const iconMap = {
        'success': 'fas fa-check-circle',
        'warning': 'fas fa-exclamation-triangle',
        'danger': 'fas fa-times-circle',
        'info': 'fas fa-info-circle'
    };
    
    const alertHTML = `
    <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="${iconMap[type]} me-2"></i>
            <div class="flex-grow-1">${message}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>`;
    
    alertContainer.insertAdjacentHTML('beforeend', alertHTML);
    
    // Auto-hide after 5 seconds if enabled
    if (autoHide) {
        setTimeout(() => {
            const alertElement = document.getElementById(alertId);
            if (alertElement) {
                const bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
            }
        }, 5000);
    }
}

// ====================== API CALLS ======================
async function apiCall(action, method = 'GET', body = null) {
    let url;
    if (method === 'GET' && body) {
        url = `api.php?action=${action}&${new URLSearchParams(body)}`;
    } else {
        url = `api.php?action=${action}`;
    }
    
    const options = {
        method: method,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    };
    if (body && method === 'POST') {
        options.body = new URLSearchParams(body);
    }
    
    try {
        const res = await fetch(url, options);
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return await res.json();
    } catch (error) {
        console.error('API call failed:', error);
        throw error;
    }
}

// ====================== NAVIGATION ======================
function navigateTo(section) {
    document.querySelectorAll('.section').forEach(s => s.classList.add('d-none'));
    document.getElementById(section).classList.remove('d-none');
    
    if (section === 'activities') renderActivitiesList();
    if (section === 'stats') loadStats();
}

// ====================== DASHBOARD ======================
async function loadDashboard() {
    const dashboard = document.getElementById('dashboard');
    showLoading(dashboard);
    
    try {
        // 1. Load all activities
        allActivities = await apiCall('get_activities');
        
        // 2. Load today's completions
        todayCompletions = await apiCall('get_completions', 'GET', { date: getToday() });
        
        currentDateStr = getToday();
        
        // Update header
        const dateEl = document.getElementById('today-full-date');
        dateEl.textContent = formatFullDate(getToday());
        
        renderDashboardGrid();
    } catch (error) {
        console.error('Error loading dashboard:', error);
    } finally {
        hideLoading(dashboard);
    }
}

async function refreshDashboard() {
    await loadDashboard();
    // Show refresh animation
    const btn = event.target.closest('button');
    const icon = btn.querySelector('i');
    icon.classList.add('fa-spin');
    setTimeout(() => icon.classList.remove('fa-spin'), 1000);
}

function renderDashboardGrid() {
    const container = document.getElementById('dashboard-grid');
    container.innerHTML = '';
    
    if (allActivities.length === 0) {
        container.innerHTML = `
        <div class="col-12 text-center py-5 text-muted fade-in">
            <i class="fas fa-inbox fa-3x mb-3"></i>
            <h4>No activities yet</h4>
            <p>Start by adding your first activity!</p>
            <button class="btn btn-primary" onclick="navigateTo('activities')">
                <i class="fas fa-plus me-1"></i>Add First Activity
            </button>
        </div>`;
        return;
    }
    
    let completedCount = 0;
    
    allActivities.forEach((act, index) => {
        const isDone = todayCompletions.some(c => c.activity_id === act.id);
        if (isDone) completedCount++;
        
        const icon = act.icon || 'fas fa-tasks';
        const delay = index * 100; // Stagger animation
        
        const cardHTML = `
        <div class="col-12 col-sm-6 col-lg-4 fade-in" style="animation-delay: ${delay}ms">
            <div class="card h-100 shadow-sm activity-card ${isDone ? 'completed border-success' : ''} hover-lift" 
                 onclick="showCompleteModal(${act.id}, '${act.name.replace(/'/g, "\\'")}')">
                <div class="card-body d-flex flex-column h-100">
                    <!-- Icon Row -->
                    <div class="text-center mb-3">
                        <div class="icon-wrapper">
                            <i class="${icon} fa-3x ${isDone ? 'text-success' : 'text-primary'}"></i>
                        </div>
                    </div>
                    
                    <!-- Title Row -->
                    <div class="text-center mb-2">
                        <h6 class="card-title mb-0 fw-bold">${act.name}</h6>
                    </div>
                    
                    <!-- Description Row -->
                    ${act.description ? `
                    <div class="text-center mb-2">
                        <p class="card-text text-muted small mb-0">${act.description}</p>
                    </div>` : ''}
                    
                    <!-- Status Badge Row -->
                    <div class="text-center mb-2">
                        ${isDone ? 
                            `<span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i>Done today
                            </span>` : 
                            `<span class="badge bg-light text-dark border">
                                <i class="fas fa-clock me-1"></i>Pending
                            </span>`
                        }
                    </div>
                    
                    <!-- Action Text Row -->
                    <div class="text-center mt-auto">
                        <small class="text-muted">
                            ${isDone ? 'Completed today' : 'Click to mark complete'}
                        </small>
                    </div>
                </div>
            </div>
        </div>`;
        container.innerHTML += cardHTML;
    });
    
    // Progress with animation
    const total = allActivities.length;
    const perc = total > 0 ? Math.round((completedCount / total) * 100) : 0;
    
    setTimeout(() => {
        document.getElementById('progress-text').innerHTML = `<strong>${completedCount}</strong> of <strong>${total}</strong> completed`;
        document.getElementById('progress-perc').textContent = perc + '%';
        document.getElementById('progress-bar').style.width = perc + '%';
    }, 300);
}

// ====================== COMPLETE / UNDO MODAL ======================
let modalActivityId = null;
let deleteActivityId = null;

function showCompleteModal(id, name) {
    modalActivityId = id;
    const isDone = todayCompletions.some(c => c.activity_id === id);
    const activity = allActivities.find(a => a.id === id);
    const icon = activity ? (activity.icon || 'fas fa-tasks') : 'fas fa-tasks';
    
    document.getElementById('completeModalTitle').innerHTML = `<i class="${icon} me-2"></i>${name}`;
    
    const modalIcon = document.getElementById('completeModalIcon');
    const modalText = document.getElementById('completeModalText');
    const confirmBtn = document.getElementById('confirmBtn');
    
    if (isDone) {
        modalIcon.className = `${icon} fa-3x text-warning`;
        modalText.innerHTML = `✅ You already completed <strong>${name}</strong> today.<br>Would you like to <strong>undo</strong> this completion?`;
        confirmBtn.innerHTML = '<i class="fas fa-undo me-1"></i>Undo completion';
        confirmBtn.className = 'btn btn-danger';
    } else {
        modalIcon.className = `${icon} fa-3x text-gradient`;
        modalText.innerHTML = `Did you complete <strong>${name}</strong> today?`;
        confirmBtn.innerHTML = '<i class="fas fa-check me-1"></i>Yes, mark done today';
        confirmBtn.className = 'btn btn-success';
    }
    
    new bootstrap.Modal(document.getElementById('completeModal')).show();
}

async function confirmCompletion() {
    const isDone = todayCompletions.some(c => c.activity_id === modalActivityId);
    const action = isDone ? 'unmark_completed' : 'mark_completed';
    
    const result = await apiCall(action, 'POST', {
        activity_id: modalActivityId,
        date: getToday(),
        completion_source: isDone ? 'manual_undo' : 'manual_click'
    });
    
    if (result.success) {
        // Refresh dashboard
        todayCompletions = await apiCall('get_completions', 'GET', { date: getToday() });
        renderDashboardGrid();
    }
    
    bootstrap.Modal.getInstance(document.getElementById('completeModal')).hide();
}

// ====================== QUICK ACTIONS ======================
function showMarkAllModal() {
    if (allActivities.length === 0) {
        showAlert('No activities to mark complete!', 'warning');
        return;
    }
    
    const incompleteActivities = allActivities.filter(act => 
        !todayCompletions.some(c => c.activity_id === act.id)
    );
    
    if (incompleteActivities.length === 0) {
        showAlert('All activities are already completed today! 🎉', 'success');
        return;
    }
    
    // Update modal content
    const modalText = document.getElementById('markAllModalText');
    const activityList = document.getElementById('markAllActivityList');
    
    modalText.innerHTML = `Mark <strong>${incompleteActivities.length}</strong> remaining activities as complete today?`;
    
    // Create activity list
    let listHTML = '<div class="mt-3"><h6 class="mb-2">Activities to be marked complete:</h6><ul class="list-group">';
    incompleteActivities.forEach(act => {
        const icon = act.icon || 'fas fa-tasks';
        listHTML += `
        <li class="list-group-item d-flex align-items-center">
            <i class="${icon} me-2 text-primary"></i>
            <span>${act.name}</span>
            ${act.description ? `<small class="text-muted ms-auto">${act.description}</small>` : ''}
        </li>`;
    });
    listHTML += '</ul></div>';
    activityList.innerHTML = listHTML;
    
    // Show modal
    new bootstrap.Modal(document.getElementById('markAllModal')).show();
}

async function confirmMarkAllComplete() {
    const incompleteActivities = allActivities.filter(act => 
        !todayCompletions.some(c => c.activity_id === act.id)
    );
    
    if (incompleteActivities.length === 0) return;
    
    // Disable button and show loading
    const confirmBtn = document.getElementById('markAllConfirmBtn');
    const originalHTML = confirmBtn.innerHTML;
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Marking...';
    
    try {
        for (const act of incompleteActivities) {
            await apiCall('mark_completed', 'POST', {
                activity_id: act.id,
                date: getToday(),
                completion_source: 'mark_all_complete'
            });
        }
        
        await loadDashboard();
        bootstrap.Modal.getInstance(document.getElementById('markAllModal')).hide();
    } catch (error) {
        console.error('Error marking all complete:', error);
        showAlert('Error marking activities complete. Please try again.', 'danger');
    } finally {
        // Restore button
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = originalHTML;
    }
}

// ====================== ICON SELECTION ======================
function setupIconSelection() {
    document.querySelectorAll('.icon-option').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove previous selection
            document.querySelectorAll('.icon-option').forEach(b => {
                b.classList.remove('btn-success');
                b.classList.add('btn-outline-secondary');
            });
            
            // Select this icon
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-success');
            selectedIcon = this.dataset.icon;
            document.getElementById('edit-icon').value = selectedIcon;
        });
    });
}
function filterActivitiesList() {
    renderActivitiesList(document.getElementById('activities-search').value);
}

function renderActivitiesList(filter = '') {
    const container = document.getElementById('activities-list-grid');
    container.innerHTML = '';
    
    // Filter activities by name or description
    const searchTerm = filter.toLowerCase().trim();
    const filtered = searchTerm
        ? allActivities.filter(act =>
            act.name.toLowerCase().includes(searchTerm) ||
            (act.description && act.description.toLowerCase().includes(searchTerm))
          )
        : allActivities;
    
    if (filtered.length === 0) {
        container.innerHTML = `
        <div class="col-12 text-center py-5 text-muted fade-in">
            <i class="fas fa-search fa-3x mb-3"></i>
            <h4>${allActivities.length === 0 ? 'No activities yet' : 'No matching activities'}</h4>
            <p>${allActivities.length === 0 ? 'Add your first activity to get started!' : 'Try a different search term'}</p>
        </div>`;
        return;
    }
    
    filtered.forEach((act, index) => {
        const icon = act.icon || 'fas fa-tasks';
        const delay = index * 50;
        
        const html = `
        <div class="col-12 col-md-6 col-lg-4 fade-in" style="animation-delay: ${delay}ms">
            <div class="card h-100 hover-lift">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3 flex-grow-1">
                        <div class="icon-wrapper me-3">
                            <i class="${icon} fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${act.name}</h6>
                            ${act.description ? `<p class="text-muted small mb-0">${act.description}</p>` : ''}
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-auto">
                        <button onclick="editActivity(${act.id})" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                        <button onclick="deleteActivityConfirm(${act.id})" class="btn btn-sm btn-outline-primary flex-fill delete-btn-hover">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
        container.innerHTML += html;
    });
}

function resetActivityModal() {
    document.getElementById('edit-id').value = '';
    document.getElementById('edit-name').value = '';
    document.getElementById('edit-desc').value = '';
    document.getElementById('edit-icon').value = 'fas fa-tasks';
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle me-2"></i>New Activity';
    document.getElementById('deleteBtn').classList.add('d-none');
    
    // Reset icon selection
    document.querySelectorAll('.icon-option').forEach(btn => {
        btn.classList.remove('btn-primary', 'btn-success');
        btn.classList.add('btn-outline-secondary');
    });
    selectedIcon = 'fas fa-tasks';
}

async function editActivity(id) {
    const act = allActivities.find(a => a.id === id);
    if (!act) return;
    
    document.getElementById('edit-id').value = act.id;
    document.getElementById('edit-name').value = act.name;
    document.getElementById('edit-desc').value = act.description || '';
    document.getElementById('edit-icon').value = act.icon || 'fas fa-tasks';
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Activity';
    document.getElementById('deleteBtn').classList.remove('d-none');
    
    // Set icon selection
    selectedIcon = act.icon || 'fas fa-tasks';
    document.querySelectorAll('.icon-option').forEach(btn => {
        const btnIcon = btn.dataset.icon;
        if (btnIcon === selectedIcon) {
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
        } else {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }
    });
    
    new bootstrap.Modal(document.getElementById('activityModal')).show();
}

async function saveActivity() {
    const id = document.getElementById('edit-id').value;
    const name = document.getElementById('edit-name').value.trim();
    const description = document.getElementById('edit-desc').value.trim();
    const icon = document.getElementById('edit-icon').value;
    
    if (!name) {
        showAlert('Activity name is required!', 'warning');
        return;
    }
    
    const result = await apiCall('save_activity', 'POST', {
        id: id || '',
        name: name,
        description: description,
        icon: icon
    });
    
    if (result.success) {
        bootstrap.Modal.getInstance(document.getElementById('activityModal')).hide();
        // Refresh everything
        await loadDashboard();
        renderActivitiesList();
    }
}

function deleteActivityConfirm(id) {
    const activity = allActivities.find(a => a.id === id);
    if (!activity) return;
    
    deleteActivityId = id;
    
    // Update modal content
    const modalText = document.getElementById('deleteModalText');
    const activityInfo = document.getElementById('deleteActivityInfo');
    const icon = activity.icon || 'fas fa-tasks';
    
    modalText.innerHTML = `Are you sure you want to delete <strong>${activity.name}</strong>?`;
    
    // Create activity info display
    let infoHTML = '<div class="mt-3"><h6 class="mb-2">Activity to be deleted:</h6>';
    infoHTML += `
    <div class="card border-danger">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <i class="${icon} fa-2x me-3 text-danger"></i>
                <div>
                    <h6 class="mb-1">${activity.name}</h6>
                    ${activity.description ? `<p class="text-muted mb-0">${activity.description}</p>` : '<p class="text-muted mb-2">No description</p>'}
                </div>
            </div>
        </div>
    </div>`;
    infoHTML += '</div>';
    activityInfo.innerHTML = infoHTML;
    
    // Show modal
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

async function confirmDeleteActivity() {
    if (!deleteActivityId) return;
    
    // Disable button and show loading
    const confirmBtn = document.getElementById('deleteConfirmBtn');
    const originalHTML = confirmBtn.innerHTML;
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Deleting...';
    
    try {
        const result = await apiCall('delete_activity', 'POST', { id: deleteActivityId });
        
        if (result.success) {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
            
            // If we're in the activity modal, close it too
            const activityModal = document.getElementById('activityModal');
            if (bootstrap.Modal.getInstance(activityModal)) {
                bootstrap.Modal.getInstance(activityModal).hide();
            }
            
            // Refresh data
            await loadDashboard();
            renderActivitiesList();
        }
    } catch (error) {
        console.error('Error deleting activity:', error);
        showAlert('Error deleting activity. Please try again.', 'danger');
    } finally {
        // Restore button
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = originalHTML;
        deleteActivityId = null;
    }
}

function deleteFromActivityModal() {
    const activityId = parseInt(document.getElementById('edit-id').value);
    if (!activityId) return;
    
    deleteActivityConfirm(activityId);
}

// ====================== STATS ======================
async function loadStats() {
    const month = document.getElementById('stats-month').value;
    const year = document.getElementById('stats-year').value;
    
    const stats = await apiCall('get_stats', 'GET', { month: month, year: year });
    
    const container = document.getElementById('stats-content');
    
    let html = '';
    
    // Show subtitle when viewing all year
    if (month === 'all') {
        const monthsList = stats.months_used && stats.months_used.length > 0
            ? stats.months_used.join(', ')
            : 'No data';
        html += `<p class="text-muted mb-3"><i class="fas fa-calendar-alt me-1"></i>Showing stats for <strong>${year}</strong> — Months with data: ${monthsList}</p>`;
    }
    
    html += `<div class="row g-3 mb-4">`;
    
    // Summary cards
    html += `
    <div class="col-12 col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-number">${stats.total_completed}</div>
                <div class="stat-label">Total completed</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-number">${stats.active_days}</div>
                <div class="stat-label">Active days</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-number">${stats.average_per_day}</div>
                <div class="stat-label">Average per day</div>
            </div>
        </div>
    </div>`;
    
    html += `</div>`;
    
    // Per activity breakdown
    html += `<div class="col-12"><h5 class="mt-4 mb-3">Most completed activities</h5></div>`;
    
    if (Object.keys(stats.per_activity).length === 0) {
        html += `<div class="col-12 text-center text-muted py-4">No data yet</div>`;
    } else {
        html += `<div class="row g-3">`;
        Object.keys(stats.per_activity).forEach(name => {
            const count = stats.per_activity[name];
            const activity = allActivities.find(a => a.name === name);
            const icon = activity ? (activity.icon || 'fas fa-tasks') : 'fas fa-tasks';
            
            html += `
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="${icon} fa-2x text-primary mb-2"></i>
                        <h6 class="mb-1">${name}</h6>
                        <span class="badge bg-success">${count} times</span>
                    </div>
                </div>
            </div>`;
        });
        html += `</div>`;
    }
    
    container.innerHTML = html;
}

// ====================== HELPER FUNCTIONS ======================
async function populateMonthSelects() {
    const allMonths = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    const currentMonth = getMonthName();
    const currentYear = getCurrentYear();
    
    const statsYear = document.getElementById('stats-year');
    const statsMonth = document.getElementById('stats-month');
    
    // Helper: populate month dropdown based on selected year
    async function populateMonthsForYear(year) {
        if (!statsMonth) return;
        try {
            const availableMonths = await apiCall('get_available_months', 'GET', { year: year });
            statsMonth.innerHTML = '';
            
            // Add "All Year" option at the top
            const allOpt = document.createElement('option');
            allOpt.value = 'all';
            allOpt.textContent = '📅 All Year';
            statsMonth.appendChild(allOpt);
            
            if (availableMonths.length === 0) {
                // If no logs exist for this year, show all months after "All Year"
                allMonths.forEach(m => {
                    const opt = document.createElement('option');
                    opt.value = m;
                    opt.textContent = m;
                    if (m === currentMonth) opt.selected = true;
                    statsMonth.appendChild(opt);
                });
            } else {
                // Show only months that exist in logs for this year
                availableMonths.forEach(m => {
                    const opt = document.createElement('option');
                    opt.value = m;
                    opt.textContent = m;
                    if (m === currentMonth) opt.selected = true;
                    statsMonth.appendChild(opt);
                });
            }
        } catch (error) {
            console.error('Error loading available months:', error);
            // Fallback: show all months
            statsMonth.innerHTML = '';
            const allOpt = document.createElement('option');
            allOpt.value = 'all';
            allOpt.textContent = '📅 All Year';
            statsMonth.appendChild(allOpt);
            
            allMonths.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m;
                opt.textContent = m;
                if (m === currentMonth) opt.selected = true;
                statsMonth.appendChild(opt);
            });
        }
    }
    
    // Populate year select
    if (statsYear) {
        try {
            const availableYears = await apiCall('get_available_years', 'GET');
            statsYear.innerHTML = '';
            
            if (availableYears.length === 0) {
                // If no logs exist, show current year
                const opt = document.createElement('option');
                opt.value = currentYear;
                opt.textContent = currentYear;
                opt.selected = true;
                statsYear.appendChild(opt);
            } else {
                // Show only years that exist in logs
                availableYears.forEach(year => {
                    const opt = document.createElement('option');
                    opt.value = year;
                    opt.textContent = year;
                    if (year == currentYear) opt.selected = true;
                    statsYear.appendChild(opt);
                });
            }
            
            // Populate months for the selected year
            const selectedYear = statsYear.value || currentYear;
            await populateMonthsForYear(selectedYear);
            
            // Update months when year changes
            statsYear.addEventListener('change', async function() {
                await populateMonthsForYear(this.value);
                // Auto-load stats when year changes
                if (!document.getElementById('stats').classList.contains('d-none')) {
                    loadStats();
                }
            });
            
            // Auto-load stats when month changes
            statsMonth.addEventListener('change', function() {
                if (!document.getElementById('stats').classList.contains('d-none')) {
                    loadStats();
                }
            });
            
        } catch (error) {
            console.error('Error loading available years:', error);
            // Fallback to current year if API fails
            statsYear.innerHTML = '';
            const opt = document.createElement('option');
            opt.value = currentYear;
            opt.textContent = currentYear;
            opt.selected = true;
            statsYear.appendChild(opt);
            
            await populateMonthsForYear(currentYear);
        }
    }
}

// ====================== INITIAL LOAD ======================
function renderCredits() {
    const container = document.getElementById('social-links-container');
    if (!container) return;
    
    container.innerHTML = creditsData.map(social => `
        <div class="col-6 col-sm-3">
            <a href="${social.url}" target="_blank" class="social-link-card text-decoration-none" title="${social.title}">
                <div class="social-icon-wrapper mb-2" style="color: ${social.color};">
                    <i class="${social.icon} fa-2x"></i>
                </div>
                <div class="small fw-bold">${social.name}</div>
            </a>
        </div>
    `).join('');
}

window.onload = async function () {
    console.log('Page loaded, calling loadDashboard');
    try {
        await loadDashboard();
        console.log('loadDashboard completed');
        populateMonthSelects();
        setupIconSelection();
        renderActivitiesList();
        renderCredits();
    } catch (error) {
        console.error('Error during initial load:', error);
        alert('Error loading application: ' + error.message);
    }
};

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + N: New activity
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        navigateTo('activities');
        setTimeout(() => resetActivityModal(), 100);
        setTimeout(() => new bootstrap.Modal(document.getElementById('activityModal')).show(), 200);
    }
    
    // Ctrl/Cmd + R: Refresh dashboard
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        if (document.getElementById('dashboard').classList.contains('d-none')) {
            navigateTo('dashboard');
        }
        refreshDashboard();
    }
});
</script>

</body>
</html>
