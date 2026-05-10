/**
 * Système d'alertes pour Smart Pizzeria
 * Gère l'affichage des messages de succès, d'erreur et d'information
 */

class SmartPizzeriaAlerts {
    constructor() {
        this.container = null;
        this.init();
    }
    
    init() {
        // Créer le conteneur d'alertes
        this.createAlertContainer();
        
        // Afficher les alertes en attente au chargement
        this.showPendingAlerts();
    }
    
    createAlertContainer() {
        this.container = document.createElement('div');
        this.container.id = 'smart-alerts-container';
        this.container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        `;
        document.body.appendChild(this.container);
    }
    
    showAlert(message, type = 'info', duration = 5000) {
        const alert = document.createElement('div');
        alert.className = `smart-alert smart-alert-${type}`;
        alert.innerHTML = `
            <div class="smart-alert-content">
                <span class="smart-alert-icon">${this.getIcon(type)}</span>
                <span class="smart-alert-message">${message}</span>
                <button class="smart-alert-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;
        
        this.container.appendChild(alert);
        
        // Animation d'entrée
        setTimeout(() => {
            alert.classList.add('smart-alert-show');
        }, 10);
        
        // Auto-suppression
        if (duration > 0) {
            setTimeout(() => {
                this.removeAlert(alert);
            }, duration);
        }
    }
    
    removeAlert(alert) {
        if (alert && alert.parentElement) {
            alert.classList.add('smart-alert-hide');
            setTimeout(() => {
                alert.remove();
            }, 300);
        }
    }
    
    getIcon(type) {
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };
        return icons[type] || icons.info;
    }
    
    showPendingAlerts() {
        // Afficher les alertes stockées en session
        const alerts = this.getPendingAlerts();
        alerts.forEach(alert => {
            this.showAlert(alert.message, alert.type, alert.duration);
        });
        
        // Vider les alertes en attente
        this.clearPendingAlerts();
    }
    
    getPendingAlerts() {
        try {
            const alerts = sessionStorage.getItem('smart_pizzeria_alerts');
            return alerts ? JSON.parse(alerts) : [];
        } catch (e) {
            return [];
        }
    }
    
    clearPendingAlerts() {
        sessionStorage.removeItem('smart_pizzeria_alerts');
    }
    
    // Méthodes statiques pour faciliter l'utilisation
    static success(message, duration = 5000) {
        if (!window.smartAlerts) {
            window.smartAlerts = new SmartPizzeriaAlerts();
        }
        window.smartAlerts.showAlert(message, 'success', duration);
    }
    
    static error(message, duration = 7000) {
        if (!window.smartAlerts) {
            window.smartAlerts = new SmartPizzeriaAlerts();
        }
        window.smartAlerts.showAlert(message, 'error', duration);
    }
    
    static warning(message, duration = 6000) {
        if (!window.smartAlerts) {
            window.smartAlerts = new SmartPizzeriaAlerts();
        }
        window.smartAlerts.showAlert(message, 'warning', duration);
    }
    
    static info(message, duration = 5000) {
        if (!window.smartAlerts) {
            window.smartAlerts = new SmartPizzeriaAlerts();
        }
        window.smartAlerts.showAlert(message, 'info', duration);
    }
}

// Styles CSS pour les alertes
const alertStyles = `
#smart-alerts-container {
    pointer-events: none;
}

.smart-alert {
    margin-bottom: 10px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-left: 4px solid #ddd;
    overflow: hidden;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.3s ease;
    pointer-events: auto;
}

.smart-alert-show {
    opacity: 1;
    transform: translateX(0);
}

.smart-alert-hide {
    opacity: 0;
    transform: translateX(100%);
}

.smart-alert-success {
    border-left-color: #28a745;
}

.smart-alert-error {
    border-left-color: #dc3545;
}

.smart-alert-warning {
    border-left-color: #ffc107;
}

.smart-alert-info {
    border-left-color: #17a2b8;
}

.smart-alert-content {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    gap: 10px;
}

.smart-alert-icon {
    font-size: 18px;
    flex-shrink: 0;
}

.smart-alert-message {
    flex: 1;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 14px;
    color: #333;
}

.smart-alert-close {
    background: none;
    border: none;
    font-size: 18px;
    color: #666;
    cursor: pointer;
    padding: 0;
    margin-left: 10px;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.smart-alert-close:hover {
    background: #f0f0f0;
    color: #333;
}

@media (max-width: 768px) {
    #smart-alerts-container {
        top: 10px;
        right: 10px;
        left: 10px;
        max-width: none;
    }
    
    .smart-alert {
        margin-bottom: 8px;
    }
    
    .smart-alert-content {
        padding: 10px 12px;
    }
    
    .smart-alert-message {
        font-size: 13px;
    }
}
`;

// Injecter les styles dans la page
const styleSheet = document.createElement('style');
styleSheet.textContent = alertStyles;
document.head.appendChild(styleSheet);

// Initialiser le système d'alertes
document.addEventListener('DOMContentLoaded', function() {
    window.smartAlerts = new SmartPizzeriaAlerts();
});

// Fonctions globales pour compatibilité
window.showAlert = function(message, type, duration) {
    SmartPizzeriaAlerts[typeof type === 'string' ? type : 'info'](message, duration);
};

window.showSuccess = function(message, duration) {
    SmartPizzeriaAlerts.success(message, duration);
};

window.showError = function(message, duration) {
    SmartPizzeriaAlerts.error(message, duration);
};

window.showWarning = function(message, duration) {
    SmartPizzeriaAlerts.warning(message, duration);
};
