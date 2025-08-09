/**
 * Chart Performance Optimizer
 * This script optimizes all Chart.js instances for better performance and responsiveness
 */

class ChartOptimizer {
  constructor() {
    this.optimizedCharts = new Map();
    this.init();
  }

  init() {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => this.optimizeAllCharts());
    } else {
      this.optimizeAllCharts();
    }

    // Force chart resize after a short delay to ensure proper initial sizing
    setTimeout(() => this.forceChartResize(), 100);
    setTimeout(() => this.forceChartResize(), 500);
    setTimeout(() => this.forceChartResize(), 1000);

    // Listen for window resize events
    window.addEventListener('resize', this.debounce(() => {
      this.optimizeAllCharts();
      // Re-render visible charts after resize
      setTimeout(() => this.reRenderVisibleCharts(), 200);
    }, 100));
    
    // Listen for orientation change
    window.addEventListener('orientationchange', () => {
      setTimeout(() => {
        this.optimizeAllCharts();
        this.reRenderVisibleCharts();
      }, 200);
    });

    // Set up intersection observer for charts
    this.setupIntersectionObserver();
  }

  optimizeAllCharts() {
    // Optimize existing charts
    this.optimizeExistingCharts();
    
    // Override Chart.js constructor to optimize new charts
    this.overrideChartConstructor();
    
    // Optimize chart containers
    this.optimizeChartContainers();
  }

  optimizeExistingCharts() {
    // Get all existing charts
    const charts = Chart.instances || [];
    
    charts.forEach(chart => {
      if (chart && !this.optimizedCharts.has(chart.id)) {
        this.optimizeChart(chart);
        this.optimizedCharts.set(chart.id, chart);
      }
    });
  }

  overrideChartConstructor() {
    // Store original Chart constructor
    if (!Chart._originalConstructor) {
      Chart._originalConstructor = Chart;
    }

    // Override Chart constructor
    const originalChart = Chart;
    const self = this;

    Chart = function(ctx, config) {
      // Create chart with original constructor
      const chart = new originalChart._originalConstructor(ctx, config);
      
      // Optimize the new chart
      if (chart && chart.id) {
        self.optimizeChart(chart);
        self.optimizedCharts.set(chart.id, chart);
        
        // Observe the canvas element for visibility changes
        if (ctx && ctx.canvas) {
          self.observeNewChart(ctx.canvas);
        }
      }
      
      return chart;
    };

    // Copy static methods and properties
    Object.setPrototypeOf(Chart, originalChart._originalConstructor);
    Object.assign(Chart, originalChart._originalConstructor);
  }

  optimizeChart(chart) {
    if (!chart || !chart.options) return;

    // Disable all animations
    if (chart.options.animation) {
      chart.options.animation.duration = 0;
      chart.options.animation.easing = 'linear';
    }

    // Optimize responsive behavior
    if (chart.options.responsive !== false) {
      chart.options.responsive = true;
      chart.options.maintainAspectRatio = false;
    }

    // Optimize interaction
    if (chart.options.interaction) {
      chart.options.interaction.mode = 'index';
      chart.options.interaction.intersect = false;
    }

    // Optimize plugins
    if (chart.options.plugins) {
      // Optimize legend
      if (chart.options.plugins.legend) {
        chart.options.plugins.legend.display = true;
        chart.options.plugins.legend.position = 'top';
      }

      // Optimize tooltip
      if (chart.options.plugins.tooltip) {
        chart.options.plugins.tooltip.enabled = true;
        chart.options.plugins.tooltip.animation = false;
      }
    }

    // Force chart update
    if (chart.update) {
      chart.update('none'); // Update without animation
    }
  }

  optimizeChartContainers() {
    // Optimize chart containers
    const chartContainers = document.querySelectorAll('.chart-container, .chart-wrapper, .visitorChart');
    
    chartContainers.forEach(container => {
      // Remove any existing transitions
      container.style.transition = 'none';
      container.style.animation = 'none';
      container.style.willChange = 'auto';
      
      // Force hardware acceleration
      container.style.transform = 'translateZ(0)';
      container.style.backfaceVisibility = 'hidden';
      container.style.perspective = '1000px';
    });

    // Optimize canvas elements
    const chartCanvases = document.querySelectorAll('canvas[id*="Chart"], .chart-canvas');
    
    chartCanvases.forEach(canvas => {
      // Remove transitions
      canvas.style.transition = 'none';
      canvas.style.animation = 'none';
      canvas.style.willChange = 'auto';
      
      // Optimize canvas rendering
      canvas.style.imageRendering = 'crisp-edges';
      
      // Force hardware acceleration
      canvas.style.transform = 'translateZ(0)';
      canvas.style.backfaceVisibility = 'hidden';
      
      // Ensure proper sizing for responsive charts
      if (canvas.chart) {
        setTimeout(() => {
          canvas.chart.resize();
        }, 50);
      }
    });
  }

  // Utility function to debounce resize events
  debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // Method to manually optimize a specific chart
  optimizeChartById(chartId) {
    const chart = Chart.getChart(chartId);
    if (chart) {
      this.optimizeChart(chart);
    }
  }

  // Method to observe new charts
  observeNewChart(canvas) {
    if (this.intersectionObserver && canvas) {
      this.intersectionObserver.observe(canvas);
    }
  }

  // Method to disable all chart animations globally
  disableAllAnimations() {
    if (Chart.defaults) {
      Chart.defaults.animation = {
        duration: 0,
        easing: 'linear'
      };
    }
  }

  // Method to enable/disable optimization
  setOptimizationEnabled(enabled) {
    if (enabled) {
      this.optimizeAllCharts();
    } else {
      // Restore original Chart constructor if needed
      if (Chart._originalConstructor) {
        Chart = Chart._originalConstructor;
      }
    }
  }

  // Method to force chart resize for proper initial sizing
  forceChartResize() {
    const chartCanvases = document.querySelectorAll('canvas[id*="Chart"], .chart-canvas');
    
    chartCanvases.forEach(canvas => {
      if (canvas.chart) {
        console.log('Forcing resize for chart:', canvas.id);
        // Force chart to recalculate dimensions
        canvas.chart.resize();
        canvas.chart.update('none'); // Update without animation
      }
    });
  }

  // Method to re-render charts when they become visible
  reRenderVisibleCharts() {
    const chartCanvases = document.querySelectorAll('canvas[id*="Chart"], .chart-canvas');
    
    chartCanvases.forEach(canvas => {
      if (canvas.chart && this.isElementVisible(canvas)) {
        console.log('Re-rendering visible chart:', canvas.id);
        setTimeout(() => {
          canvas.chart.resize();
          canvas.chart.update('none');
        }, 100);
      }
    });
  }

  // Helper method to check if element is visible
  isElementVisible(element) {
    const rect = element.getBoundingClientRect();
    const style = window.getComputedStyle(element);
    
    return rect.width > 0 && 
           rect.height > 0 && 
           style.display !== 'none' && 
           style.visibility !== 'hidden' &&
           style.opacity !== '0';
  }

  // Set up intersection observer to watch for charts becoming visible
  setupIntersectionObserver() {
    if ('IntersectionObserver' in window) {
      this.intersectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting && entry.target.chart) {
            console.log('Chart became visible:', entry.target.id);
            // Re-render chart when it becomes visible
            setTimeout(() => {
              if (entry.target.chart) {
                entry.target.chart.resize();
                entry.target.chart.update('none');
              }
            }, 100);
          }
        });
      }, {
        threshold: 0.1,
        rootMargin: '50px'
      });

      // Observe all chart canvases
      const chartCanvases = document.querySelectorAll('canvas[id*="Chart"], .chart-canvas');
      chartCanvases.forEach(canvas => {
        this.intersectionObserver.observe(canvas);
      });
    }
  }
}

// Initialize chart optimizer when script loads
const chartOptimizer = new ChartOptimizer();

// Export for global access
window.ChartOptimizer = ChartOptimizer;
window.chartOptimizer = chartOptimizer;

// Auto-optimize charts after a short delay to catch any late-loading charts
setTimeout(() => {
  chartOptimizer.optimizeAllCharts();
}, 500);

// Re-optimize charts after all resources are loaded
window.addEventListener('load', () => {
  setTimeout(() => {
    chartOptimizer.optimizeAllCharts();
  }, 100);
});

console.log('Chart Optimizer initialized - All charts will be optimized for performance');
