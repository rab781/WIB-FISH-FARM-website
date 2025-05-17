/**
 * WIB Fish Farm Landing Page JS
 * File ini berisi kode JavaScript untuk landing page
 */

// Product slider functionality
function productSlider() {
    return {
        products: productData || [], // Data produk dari backend with fallback
        currentPage: 0,
        pageSize: 4, // Jumlah produk per halaman
        slideDirection: 'right',
        isAnimating: false,

        // Hitung total halaman
        totalPages() {
            return Math.ceil(this.products.length / this.pageSize) || 1;
        },

        // Cek apakah ada lebih dari satu halaman produk
        hasMultiplePages() {
            return this.totalPages() > 1;
        },

        // Produk yang terlihat di halaman saat ini
        visibleProducts() {
            const start = this.currentPage * this.pageSize;
            return this.products.slice(start, start + this.pageSize);
        },

        // Navigasi ke halaman berikutnya
        nextPage() {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.slideDirection = 'right';

            // Use requestAnimationFrame for better performance
            requestAnimationFrame(() => {
                setTimeout(() => {
                    if (this.currentPage < this.totalPages() - 1) {
                        this.currentPage++;
                    } else {
                        this.currentPage = 0;
                    }
                    this.isAnimating = false;
                }, 400);
            });
        },

        // Navigasi ke halaman sebelumnya
        prevPage() {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.slideDirection = 'left';

            // Use requestAnimationFrame for better performance
            requestAnimationFrame(() => {
                setTimeout(() => {
                    if (this.currentPage > 0) {
                        this.currentPage--;
                    } else {
                        this.currentPage = this.totalPages() - 1;
                    }
                    this.isAnimating = false;
                }, 400);
            });
        },

        // Navigasi ke halaman tertentu
        goToPage(page) {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.slideDirection = page > this.currentPage ? 'right' : 'left';

            // Use requestAnimationFrame for better performance
            requestAnimationFrame(() => {
                setTimeout(() => {
                    this.currentPage = page;
                    this.isAnimating = false;
                }, 400);
            });
        }
    };
}

// Testimonial slider functionality
function testimonialSlider() {
    return {
        testimonials: testimonialData || [],
        currentPage: 0,
        pageSize: 3,
        slideDirection: 'right',
        isAnimating: false,

        // Calculate total pages
        totalPages() {
            return Math.ceil(this.testimonials.length / this.pageSize) || 1;
        },

        // Navigate to next page
        nextPage() {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.slideDirection = 'right';

            // Use requestAnimationFrame for better performance
            requestAnimationFrame(() => {
                setTimeout(() => {
                    if (this.currentPage < this.totalPages() - 1) {
                        this.currentPage++;
                    } else {
                        this.currentPage = 0;
                    }
                    this.isAnimating = false;
                }, 400);
            });
        },

        // Navigate to previous page
        prevPage() {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.slideDirection = 'left';

            // Use requestAnimationFrame for better performance
            requestAnimationFrame(() => {
                setTimeout(() => {
                    if (this.currentPage > 0) {
                        this.currentPage--;
                    } else {
                        this.currentPage = this.totalPages() - 1;
                    }
                    this.isAnimating = false;
                }, 400);
            });
        },

        // Jump to specific page
        goToPage(page) {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.slideDirection = page > this.currentPage ? 'right' : 'left';

            // Use requestAnimationFrame for better performance
            requestAnimationFrame(() => {
                setTimeout(() => {
                    this.currentPage = page;
                    this.isAnimating = false;
                }, 400);
            });
        },

        // Get visible testimonials for current page
        visibleTestimonials() {
            const start = this.currentPage * this.pageSize;
            return this.testimonials.slice(start, start + this.pageSize);
        },

        // Get next page testimonials
        nextPageTestimonials() {
            const nextPage = (this.currentPage + 1) % this.totalPages();
            const start = nextPage * this.pageSize;
            return this.testimonials.slice(start, start + this.pageSize);
        },

        // Get previous page testimonials
        prevPageTestimonials() {
            let prevPage = this.currentPage - 1;
            if (prevPage < 0) prevPage = this.totalPages() - 1;
            const start = prevPage * this.pageSize;
            return this.testimonials.slice(start, start + this.pageSize);
        }
    };
}

// Main app state
function appState() {
    return {
        scrolled: false,
        showAuthModal: false,
        modalMessage: '',
        observerInitialized: false,

        // Initialize
        init() {
            // Initialize AOS animation library with optimized settings
            AOS.init({
                duration: 800,
                easing: 'ease-out',
                once: true, // Changed to true for better performance
                mirror: false, // Changed to false for better performance
                disable: window.innerWidth < 768 ? true : false // Disable on mobile for better performance
            });

            // Add passive scroll event listener
            window.addEventListener('scroll', () => {
                this.scrolled = window.pageYOffset > 10;
            }, { passive: true });

            // Setup mobile menu toggle
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Initialize lazy loading for images that don't have loading="lazy"
            this.initLazyLoading();
        },

        // Initialize lazy loading for images
        initLazyLoading() {
            if ('IntersectionObserver' in window && !this.observerInitialized) {
                this.observerInitialized = true;

                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const src = img.getAttribute('data-src');

                            if (src) {
                                img.src = src;
                                img.removeAttribute('data-src');
                            }

                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '0px 0px 50px 0px'
                });

                // Get all images that need lazy loading
                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        },

        // Show authentication modal with message
        showAuthWithMessage(message) {
            const modal = document.getElementById('authGlobalModal');
            if (modal && typeof Alpine !== 'undefined') {
                const data = Alpine.$data(modal);
                if (data) {
                    if (message) {
                        data.modalMessage = message;
                    }
                    data.showAuthModal = true;
                }
            }
        }
    };
}

// Initialize when DOM content is loaded - using requestIdleCallback if available
if ('requestIdleCallback' in window) {
    requestIdleCallback(() => {
        // Any additional initialization can go here
    });
} else {
    // Fallback for browsers that don't support requestIdleCallback
    setTimeout(() => {
        // Any additional initialization can go here
    }, 200);
}
