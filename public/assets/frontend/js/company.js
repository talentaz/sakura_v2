document.addEventListener("DOMContentLoaded", () => {
    const slidesWrapper = document.getElementById("slidesWrapper");
    const prevButton = document.getElementById("prevButton");
    const nextButton = document.getElementById("nextButton");
    const dotNavigation = document.getElementById("dotNavigation");
    const slides = document.querySelectorAll(".slide");
    let currentIndex = 0;

    const updateSlider = () => {
        const offset = -currentIndex * 100;
        slidesWrapper.style.transform = `translateX(${offset}%)`;
        updateDots();
    };

    const createDots = () => {
        dotNavigation.innerHTML = ""; // Clear existing dots
        slides.forEach((_, index) => {
            const dot = document.createElement("div");
            dot.classList.add("dot-hero");
            if (index === currentIndex) {
                dot.classList.add("active");
            }
            dot.addEventListener("click", () => {
                currentIndex = index;
                updateSlider();
            });
            dotNavigation.appendChild(dot);
        });
    };

    const updateDots = () => {
        const dots = document.querySelectorAll(".dot-hero");
        dots.forEach((dot, index) => {
            if (index === currentIndex) {
                dot.classList.add("active");
            } else {
                dot.classList.remove("active");
            }
        });
    };

    prevButton.addEventListener("click", () => {
        currentIndex = currentIndex > 0 ? currentIndex - 1 : slides.length - 1;
        updateSlider();
    });

    nextButton.addEventListener("click", () => {
        currentIndex = currentIndex < slides.length - 1 ? currentIndex + 1 : 0;
        updateSlider();
    });

    createDots();
    updateSlider();
});

const spans = document.querySelectorAll(".m-head h1 .word");

function animateWords() {
    spans.forEach((span) => {
        span.classList.remove("show");
    });

    spans.forEach((span, index) => {
        setTimeout(() => {
            span.classList.add("show");
        }, index * 800); // timing between words
    });
}

animateWords();
setInterval(animateWords, 4000 + 2000); // 4s cycle + 2s pause
