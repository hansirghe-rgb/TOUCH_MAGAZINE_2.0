document.addEventListener("DOMContentLoaded", () => {
    // Reveal Animations on Scroll
    const observers = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
                if (entry.target.classList.contains("text-reveal")) {
                    entry.target.classList.add("visible");
                }
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll(".fade-up, .fade-in, .text-reveal").forEach((el) => {
        observers.observe(el);
    });

    // Mobile Menu Toggle
    const menuBtn = document.getElementById("mobile-menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");
    const closeMenuBtn = document.getElementById("close-menu-btn");

    if (menuBtn && mobileMenu && closeMenuBtn) {
        menuBtn.addEventListener("click", () => {
            mobileMenu.classList.remove("translate-x-full");
            document.body.style.overflow = "hidden";
        });

        closeMenuBtn.addEventListener("click", () => {
            mobileMenu.classList.add("translate-x-full");
            document.body.style.overflow = "";
        });
    }

    // Navbar Scrolled State
    const navbar = document.getElementById("navbar");
    if (navbar) {
        window.addEventListener("scroll", () => {
            if (window.scrollY > 50) {
                navbar.classList.add("glass-panel");
                navbar.classList.remove("bg-transparent");
                navbar.classList.add("py-4");
                navbar.classList.remove("py-6");
            } else {
                navbar.classList.remove("glass-panel");
                navbar.classList.add("bg-transparent");
                navbar.classList.remove("py-4");
                navbar.classList.add("py-6");
            }
        });
    }

    // Magnetic Buttons
    const magneticButtons = document.querySelectorAll(".btn-magnetic");
    magneticButtons.forEach((btn) => {
        btn.addEventListener("mousemove", (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            btn.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
        });

        btn.addEventListener("mouseleave", () => {
            btn.style.transform = `translate(0px, 0px)`;
        });
    });

    // ----------------------------------------------------
    // PORTAL UPLOAD FUNCTIONALITY
    // ----------------------------------------------------

    // 1. Footer Campus Logo Upload
    const logoUpload = document.getElementById('logo-upload');
    const campusLogo = document.getElementById('campus-logo');

    if (logoUpload && campusLogo) {
        logoUpload.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    campusLogo.src = event.target.result;
                    // Switch to cover or contain based on dimensions dynamically or just clear placeholder sizing
                    campusLogo.classList.remove("bg-white/5", "p-2");
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // 2. Magazine Cover Upload
    const magazineUpload = document.getElementById('magazine-upload');
    const magazineCover = document.getElementById('magazine-cover');

    if (magazineUpload && magazineCover) {
        magazineUpload.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    magazineCover.src = event.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // 3. Podcast Video Upload
    const podcastUpload = document.getElementById('podcast-upload');
    const podcastVideo = document.getElementById('main-podcast-video');
    const podcastThumb = document.getElementById('main-podcast-thumb');
    const podcastPlayBtn = document.getElementById('podcast-play-btn');
    const podcastOverlay = document.getElementById('podcast-overlay');
    const podcastInfo = document.getElementById('podcast-info');

    if (podcastUpload && podcastVideo && podcastThumb && podcastPlayBtn) {
        podcastUpload.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const fileURL = URL.createObjectURL(file);

                // If the user uploads a video
                if (file.type.startsWith('video/')) {
                    podcastVideo.src = fileURL;
                    podcastVideo.classList.remove('hidden');
                    podcastThumb.classList.add('hidden');
                }
                // If they just upload a thumbnail image
                else if (file.type.startsWith('image/')) {
                    podcastThumb.src = fileURL;
                    podcastVideo.classList.add('hidden');
                    podcastThumb.classList.remove('hidden');
                }
            }
        });

        // Click to play video mock
        podcastPlayBtn.addEventListener('click', () => {
            if (!podcastVideo.classList.contains('hidden') && podcastVideo.src) {
                podcastVideo.play();
                podcastPlayBtn.classList.add('hidden');
                podcastOverlay.classList.add('hidden');
                podcastInfo.classList.add('hidden');
            } else {
                alert("Please upload a video file first using the portal!");
            }
        });

        // Show button again if paused
        podcastVideo.addEventListener('pause', () => {
            podcastPlayBtn.classList.remove('hidden');
            podcastOverlay.classList.remove('hidden');
            podcastInfo.classList.remove('hidden');
        });
    }
});
