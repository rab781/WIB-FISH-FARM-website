/**
 * WIB Fish Farm Landing Page JS
 * File ini berisi kode JavaScript untuk landing page
 */

// Product slider functionality
function productSlider() {
    return {
        products: productData, // Data produk dari backend
        currentPage: 0,
        pageSize: 4, // Jumlah produk per halaman
        slideDirection: 'right',
        isAnimating: false,

        // Hitung total halaman
        totalPages() {
            return Math.ceil(this.products.length / this.pageSize);
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

            setTimeout(() => {
                if (this.currentPage < this.totalPages() - 1) {
                    this.currentPage++;
                } else {
                    this.currentPage = 0;
                }
                this.isAnimating = false;
            }, 400);
        },

        // Navigasi ke halaman sebelumnya
        prevPage() {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.slideDirection = 'left';

            setTimeout(() => {
                if (this.currentPage > 0) {
                    this.currentPage--;
                } else {
                    this.currentPage = this.totalPages() - 1;
                }
                this.isAnimating = false;
            }, 400);
        },

        // Navigasi ke halaman tertentu
        goToPage(page) {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.slideDirection = page > this.currentPage ? 'right' : 'left';

            setTimeout(() => {
                this.currentPage = page;
                this.isAnimating = false;
            }, 400);
        }
    };
}

// Testimonial slider functionality
function testimonialSlider() {
    return {
        testimonials: testimonialData,
        currentPage: 0,
        pageSize: 3,
        slideDirection: 'right',
        isAnimating: false,

        // Calculate total pages
        totalPages() {
            return Math.ceil(this.testimonials.length / this.pageSize);
        },

        // Navigate to next page
        nextPage() {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.slideDirection = 'right';

            setTimeout(() => {
                if (this.currentPage < this.totalPages() - 1) {
                    this.currentPage++;
                } else {
                    this.currentPage = 0;
                }
                this.isAnimating = false;
            }, 400);
        },

        // Navigate to previous page
        prevPage() {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.slideDirection = 'left';

            setTimeout(() => {
                if (this.currentPage > 0) {
                    this.currentPage--;
                } else {
                    this.currentPage = this.totalPages() - 1;
                }
                this.isAnimating = false;
            }, 400);
        },

        // Jump to specific page
        goToPage(page) {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.slideDirection = page > this.currentPage ? 'right' : 'left';

            setTimeout(() => {
                this.currentPage = page;
                this.isAnimating = false;
            }, 400);
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

        // Initialize
        init() {
            // Initialize AOS animation library
            AOS.init({
                duration: 800,
                easing: 'ease',
                once: false,
                mirror: true
            });

            // Add scroll event listener
            window.addEventListener('scroll', () => {
                this.scrolled = window.pageYOffset > 10;
            });

            // Setup mobile menu toggle
            document.querySelector('.mobile-menu-button')?.addEventListener('click', function() {
                document.querySelector('.mobile-menu').classList.toggle('hidden');
            });
        },

        // Show authentication modal with message
        showAuthWithMessage(message) {
            this.modalMessage = message;
            this.showAuthModal = true;
        }
    };
}

// Initialize when DOM content is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Any additional initialization can go here
});
