/**
 * Transitions Optimizer
 * Dynamically optimizes CSS transitions for better responsiveness
 */

class TransitionsOptimizer {
    constructor() {
        this.optimizationEnabled = true;
        this.fastTransitionDuration = '0.1s';
        this.mediumTransitionDuration = '0.15s';
        this.slowTransitionDuration = '0.2s';
        
        this.init();
    }
    
    init() {
        // Optimize transitions on page load
        this.optimizeTransitions();
        
        // Optimize transitions on window resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.optimizeTransitions();
            }, 100);
        });
        
        // Optimize transitions on orientation change
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                this.optimizeTransitions();
            }, 100);
        });
    }
    
    optimizeTransitions() {
        if (!this.optimizationEnabled) return;
        
        // Get all elements with transitions
        const elementsWithTransitions = document.querySelectorAll('*[style*="transition"]');
        
        elementsWithTransitions.forEach(element => {
            this.optimizeElementTransitions(element);
        });
        
        // Also check computed styles
        this.optimizeComputedTransitions();
    }
    
    optimizeElementTransitions(element) {
        const style = element.style;
        const computedStyle = window.getComputedStyle(element);
        
        // Check if element has transition property
        if (style.transition || computedStyle.transition !== 'none') {
            // Optimize based on element type and context
            this.applyOptimizedTransitions(element, style);
        }
    }
    
    applyOptimizedTransitions(element, style) {
        const tagName = element.tagName.toLowerCase();
        const className = element.className;
        
        // Different optimization strategies for different element types
        if (tagName === 'img' || tagName === 'video') {
            // Media elements: very fast transitions
            style.transition = `opacity ${this.fastTransitionDuration} ease, transform ${this.fastTransitionDuration} ease`;
        } else if (tagName === 'button' || tagName === 'a' || className.includes('btn')) {
            // Interactive elements: fast transitions
            style.transition = `background-color ${this.fastTransitionDuration} ease, color ${this.fastTransitionDuration} ease, border-color ${this.fastTransitionDuration} ease`;
        } else if (tagName === 'div' && (className.includes('card') || className.includes('box'))) {
            // Container elements: medium transitions
            style.transition = `border-color ${this.mediumTransitionDuration} ease, box-shadow ${this.mediumTransitionDuration} ease, transform ${this.mediumTransitionDuration} ease`;
        } else if (className.includes('navbar') || className.includes('header')) {
            // Navigation elements: fast transitions
            style.transition = `background-color ${this.fastTransitionDuration} ease, padding ${this.fastTransitionDuration} ease`;
        } else {
            // Default: medium transitions
            style.transition = `all ${this.mediumTransitionDuration} ease`;
        }
    }
    
    optimizeComputedTransitions() {
        // Find elements that might have transitions in CSS classes
        const commonTransitionClasses = [
            '.fade', '.slide', '.collapse', '.expand',
            '.hover-effect', '.transition', '.animate'
        ];
        
        commonTransitionClasses.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(element => {
                this.optimizeElementTransitions(element);
            });
        });
    }
    
    // Method to temporarily disable transitions (useful for layout changes)
    disableTransitions() {
        document.body.style.setProperty('--transition-disabled', 'true');
        document.documentElement.style.setProperty('--transition-disabled', 'true');
    }
    
    // Method to re-enable transitions
    enableTransitions() {
        document.body.style.removeProperty('--transition-disabled');
        document.documentElement.style.removeProperty('--transition-disabled');
    }
    
    // Method to set custom transition duration
    setTransitionDuration(duration) {
        this.fastTransitionDuration = duration;
        this.mediumTransitionDuration = duration;
        this.slowTransitionDuration = duration;
        this.optimizeTransitions();
    }
    
    // Method to toggle optimization
    toggleOptimization() {
        this.optimizationEnabled = !this.optimizationEnabled;
        if (this.optimizationEnabled) {
            this.optimizeTransitions();
        }
    }
}

// Initialize the optimizer when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.transitionsOptimizer = new TransitionsOptimizer();
});

// Also initialize immediately if DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.transitionsOptimizer = new TransitionsOptimizer();
    });
} else {
    window.transitionsOptimizer = new TransitionsOptimizer();
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = TransitionsOptimizer;
}
