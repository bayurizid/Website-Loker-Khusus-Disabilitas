document.addEventListener('DOMContentLoaded', function() {

    // Mobile Navigation Toggle
    const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
    const mainNav = document.querySelector('.main-nav'); 
    if (mobileNavToggle && mainNav) {
        mobileNavToggle.addEventListener('click', () => {
            mainNav.classList.toggle('active');
            const isExpanded = mainNav.classList.contains('active');
            mobileNavToggle.setAttribute('aria-expanded', isExpanded);
            if(mobileNavToggle.querySelector('i')) { 
                mobileNavToggle.querySelector('i').classList.toggle('fa-bars');
                mobileNavToggle.querySelector('i').classList.toggle('fa-times');
            }
        });
    }

    // Dropdown untuk filter "Lainnya"
    const filterLainnyaBtn = document.getElementById('filterLainnyaBtn');
    const filterLainnyaDropdown = document.getElementById('filterLainnyaDropdown');
    if (filterLainnyaBtn && filterLainnyaDropdown) {
        filterLainnyaBtn.addEventListener('click', function(event) {
            event.stopPropagation(); 
            filterLainnyaDropdown.classList.toggle('show');
        });
        window.addEventListener('click', function(event) {
            if (filterLainnyaDropdown.classList.contains('show') && !filterLainnyaBtn.contains(event.target)) {
                filterLainnyaDropdown.classList.remove('show');
            }
        });
    }
    
    // Logika untuk filter kategori utama di halaman utama
    const categoryFilterButtons = document.querySelectorAll('.job-category-filters .filter-btn:not(.filter-dropdown-toggle)');
    const jobCards = document.querySelectorAll('.popular-jobs-section .job-card-popular');

    function applyJobCardFilter(filterValue) {
        jobCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            if (filterValue.toLowerCase() === 'semua' || (cardCategory && cardCategory.toLowerCase() === filterValue.toLowerCase())) {
                card.style.display = 'flex'; 
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    categoryFilterButtons.forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.job-category-filters .filter-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            const filterValue = button.getAttribute('data-filter');
            applyJobCardFilter(filterValue);
        });
    });
    
    if (filterLainnyaDropdown) {
        filterLainnyaDropdown.querySelectorAll('a.filter-option').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); 
                document.querySelectorAll('.job-category-filters .filter-btn').forEach(btn => btn.classList.remove('active'));
                if(filterLainnyaBtn) filterLainnyaBtn.classList.add('active'); 
                const filterValue = this.getAttribute('data-filter');
                applyJobCardFilter(filterValue);
                filterLainnyaDropdown.classList.remove('show');
            });
        });
    }

    // Fungsi Simpan Loker (AJAX)
    document.body.addEventListener('click', function(event) {
        if (event.target.closest('.job-card-favorite-btn')) {
            const button = event.target.closest('.job-card-favorite-btn');
            event.preventDefault();
            event.stopPropagation();
            const jobId = button.dataset.jobId;
            const icon = button.querySelector('i');
            fetch('toggle_save_job.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ job_id: jobId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.action === 'saved') {
                        button.classList.add('active');
                        icon.classList.replace('far', 'fas');
                    } else {
                        button.classList.remove('active');
                        icon.classList.replace('fas', 'far');
                    }
                } else {
                    alert(data.message);
                    if (data.message && data.message.toLowerCase().includes('login')) {
                        window.location.href = 'login.php';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });

    // ====================================================================
    // === LOGIKA SLIDER TESTIMONI - VERSI TIDAK BISA BERHENTI ===
    // ====================================================================
    const sliderContainer = document.querySelector('.testimonial-slider-container');
    if (sliderContainer) {
        const track = sliderContainer.querySelector('.testimonial-track');
        const dotsNav = sliderContainer.querySelector('.slider-dots');
        
        // Hapus dots karena tidak relevan
        if(dotsNav) dotsNav.remove();

        const cards = Array.from(track.children);
        if (cards.length > 0) {
            
            // Gandakan kartu untuk efek loop
            cards.forEach(card => {
                const clone = card.cloneNode(true);
                track.appendChild(clone);
            });

            let currentIndex = 0;
            const originalCardCount = cards.length;
            const transitionSpeed = 500;
            const slideDelay = 3500;

            function getCardWidth() {
                if (track.children.length > 0) {
                    const cardElement = track.children[0];
                    const style = window.getComputedStyle(cardElement);
                    const margin = parseFloat(style.marginLeft) + parseFloat(style.marginRight);
                    return cardElement.offsetWidth + margin;
                }
                return 0;
            }

            function startSlider() {
                setInterval(() => {
                    const cardWidth = getCardWidth();
                    if (cardWidth === 0) return;

                    currentIndex++;
                    track.style.transition = `transform ${transitionSpeed / 1000}s ease-in-out`;
                    track.style.transform = `translateX(-${currentIndex * cardWidth}px)`;

                    // Reset ke awal setelah melewati semua kartu asli
                    if (currentIndex >= originalCardCount) {
                        setTimeout(() => {
                            track.style.transition = 'none';
                            currentIndex = 0;
                            track.style.transform = 'translateX(0)';
                        }, transitionSpeed);
                    }
                }, slideDelay);
            }
            
            // Langsung mulai slider tanpa event listener untuk berhenti
            startSlider();
        }
    }

    // ====================================================================
    // === CUSTOM DISABILITY SELECT DROPDOWN DENGAN IKON (REUSABLE) ===
    // ====================================================================
    document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
        const trigger      = wrapper.querySelector('.custom-select-trigger');
        const options      = wrapper.querySelector('.custom-select-options');
        const hiddenInput  = wrapper.querySelector('input[type="hidden"]');
        const selectedText = wrapper.querySelector('.selected-text');

        if (trigger && options) {
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                document.querySelectorAll('.custom-select-options').forEach(opt => {
                    if (opt !== options) opt.classList.remove('open');
                });
                options.classList.toggle('open');
            });

            options.querySelectorAll('.custom-option').forEach(option => {
                option.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const value = this.getAttribute('data-value');
                    const label = this.getAttribute('data-label');
                    const icon  = this.getAttribute('data-icon');

                    if (hiddenInput) hiddenInput.value = value;
                    if (selectedText) {
                        if (icon) {
                            selectedText.innerHTML = `<img src="${icon}" class="select-opt-icon" alt=""> ${label}`;
                        } else {
                            selectedText.innerHTML = label;
                        }
                    }

                    options.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    options.classList.remove('open');
                });
            });
        }
    });

    document.addEventListener('click', function() {
        document.querySelectorAll('.custom-select-options').forEach(opt => opt.classList.remove('open'));
    });
});