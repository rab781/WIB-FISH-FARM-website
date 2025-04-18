/**
 * WIB Fish Farm Landing Page JS
 * File ini berisi kode JavaScript untuk landing page
 */

// Data produk
const productData = [
    { id: 1, name: 'Ikan Koki Merah', price: 150000, type: 'koki', image: '/images/placeholder.jpg', popularity: 5, stock: 10 },
    { id: 2, name: 'Ikan Koki Calico', price: 175000, type: 'koki', image: '/images/placeholder.jpg', popularity: 3, stock: 8 },
    { id: 3, name: 'Koi Kohaku', price: 250000, type: 'koi', image: '/images/placeholder.jpg', popularity: 4, stock: 5 },
    { id: 4, name: 'Koi Showa', price: 350000, type: 'koi', image: '/images/placeholder.jpg', popularity: 5, stock: 3 },
    { id: 5, name: 'Ikan Koki Oranda', price: 200000, type: 'koki', image: '/images/placeholder.jpg', popularity: 4, stock: 7 },
    { id: 6, name: 'Koi Sanke', price: 275000, type: 'koi', image: '/images/placeholder.jpg', popularity: 4, stock: 6 },
    { id: 7, name: 'Ikan Koki Ranchu', price: 225000, type: 'koi', image: '/images/placeholder.jpg', popularity: 3, stock: 9 },
    { id: 8, name: 'Koi Bekko', price: 230000, type: 'koi', image: '/images/placeholder.jpg', popularity: 2, stock: 4 }
];

// Data testimonial
const testimonialData = [
    { id: 1, text: 'Saya sangat senang dengan pelayanan perusahaan ini. Harga terjangkau dan kualitas terbaik.', name: 'Adit Prasetyo', location: 'Jakarta, Indonesia' },
    { id: 2, text: 'Produk sangat berkualitas dan pengiriman tepat waktu. Saya akan berlangganan lagi.', name: 'Edi Kusnadi', location: 'Malang, Jawa Timur' },
    { id: 3, text: 'Pelayanan customer service yang sangat responsif dan ramah. Terimakasih!', name: 'Yudi Santoso', location: 'DKI, Jakarta Selatan' },
    { id: 4, text: 'Kualitas ikan yang saya beli sangat bagus, pengiriman cepat dan ikan tetap sehat saat sampai.', name: 'Rini Wulandari', location: 'Bandung, Jawa Barat' },
    { id: 5, text: 'Pilihan ikan hias yang lengkap dengan harga bersaing. Rekomendasi untuk pemula!', name: 'Teguh Sugiarto', location: 'Surabaya, Jawa Timur' }
];

// Product slider functionality
function productSlider() {
    return {
        products: productData,
        currentPage: 0,
        pageSize: 4,
        slideDirection: 'right',
        isAnimating: false,

        // Calculate total pages
        totalPages() {
            return Math.ceil(this.products.length / this.pageSize);
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

        // Get visible products for current page
        visibleProducts() {
            const start = this.currentPage * this.pageSize;
            return this.products.slice(start, start + this.pageSize);
        },

        // Get next page products
        nextPageProducts() {
            const nextPage = (this.currentPage + 1) % this.totalPages();
            const start = nextPage * this.pageSize;
            return this.products.slice(start, start + this.pageSize);
        },

        // Get previous page products
        prevPageProducts() {
            let prevPage = this.currentPage - 1;
            if (prevPage < 0) prevPage = this.totalPages() - 1;
            const start = prevPage * this.pageSize;
            return this.products.slice(start, start + this.pageSize);
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
                duration: 8,
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
