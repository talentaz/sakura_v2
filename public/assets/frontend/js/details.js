document.addEventListener("DOMContentLoaded", () => {
    const mainImage = document.getElementById("mainImage");
    const mainVideo = document.getElementById("mainVideo");
    const prevButton = document.getElementById("prevButton");
    const nextButton = document.getElementById("nextButton");
    const thumbnailCarousel = document.getElementById("thumbnailCarousel");
    const paginationDots = document.getElementById("paginationDots");
    const carouselPrev = document.getElementById("carouselPrev");
    const carouselNext = document.getElementById("carouselNext");
    const mainDownloadBtn = document.getElementById("mainDownloadBtn");

    const overlaySlider = document.getElementById("overlaySlider");
    const overlayMainImage = document.getElementById("overlayMainImage");
    const overlayMainVideo = document.getElementById("overlayMainVideo");
    const overlayPrevBtn = document.getElementById("overlayPrevBtn");
    const overlayNextBtn = document.getElementById("overlayNextBtn");
    const overlayImageCounter = document.getElementById("overlayImageCounter");
    const overlayThumbnails = document.getElementById("overlayThumbnails");
    const overlayPaginationDots = document.getElementById(
        "overlayPaginationDots"
    );
    const closeOverlayBtn = document.getElementById("closeOverlay");
    const overlayShareIcon = document.getElementById("overlayShareIcon");
    const overlayDownloadIcon = document.getElementById("overlayDownloadIcon");

    const thumbsPrev = document.getElementById("thumbsPrev");
    const thumbsNext = document.getElementById("thumbsNext");

    let currentImageIndex = 0;
    let images = [];

    function fetchImageData() {
        const imageDataDiv = document.getElementById("imageData");
        const dataElements = imageDataDiv.children;
        for (let i = 0; i < dataElements.length; i++) {
            const src = dataElements[i].getAttribute("data-src");
            const type = dataElements[i].getAttribute("data-type") || "image";
            const poster = dataElements[i].getAttribute("data-poster") || "";
            images.push({ src, type, poster });
        }
        const imageCount = images.filter(
            (item) => item.type === "image"
        ).length;
        mainDownloadBtn.innerHTML = `<i class="fa-regular fa-arrow-down-to-bracket"></i> Download all ${imageCount} images`;
    }

    function updateNavButtons() {
        prevButton.classList.toggle("disabled-main", currentImageIndex === 0);
        nextButton.classList.toggle(
            "disabled-main",
            currentImageIndex === images.length - 1
        );
        overlayPrevBtn.classList.toggle(
            "disabled-overlay",
            currentImageIndex === 0
        );
        overlayNextBtn.classList.toggle(
            "disabled-overlay",
            currentImageIndex === images.length - 1
        );
    }

    function updateThumbnailNavButtons() {
        // Main thumbs nav
        carouselPrev.classList.toggle(
            "disabled-thumbs",
            thumbnailCarousel.scrollLeft <= 0
        );
        carouselNext.classList.toggle(
            "disabled-thumbs",
            thumbnailCarousel.scrollLeft + thumbnailCarousel.offsetWidth >=
                thumbnailCarousel.scrollWidth
        );

        // Overlay thumbs nav
        const canScroll =
            overlayThumbnails.scrollWidth > overlayThumbnails.offsetWidth;
        thumbsPrev.classList.toggle(
            "disabled-overlay-thumbs",
            !canScroll || overlayThumbnails.scrollLeft <= 0
        );
        thumbsNext.classList.toggle(
            "disabled-overlay-thumbs",
            !canScroll ||
                overlayThumbnails.scrollLeft + overlayThumbnails.offsetWidth >=
                    overlayThumbnails.scrollWidth
        );
    }

    function displayMainMedia(index) {
        if (images.length === 0) return;
        const media = images[index];
        mainImage.style.display = "none";
        mainVideo.style.display = "none";
        mainVideo.pause();

        if (media.type === "image") {
            mainImage.src = media.src;
            mainImage.style.display = "block";
        } else {
            mainVideo.src = media.src;
            mainVideo.poster = media.poster;
            mainVideo.style.display = "block";
        }

        updateThumbnails(index);
        // updatePaginationDots(index);
        updateNavButtons();
        updateThumbnailNavButtons();
    }

    function displayOverlayMedia(index) {
        if (images.length === 0) return;
        const media = images[index];
        overlayMainImage.style.display = "none";
        overlayMainVideo.style.display = "none";
        overlayMainVideo.pause();

        if (media.type === "image") {
            overlayMainImage.src = media.src;
            overlayMainImage.style.display = "block";
        } else {
            overlayMainVideo.src = media.src;
            overlayMainVideo.poster = media.poster;
            overlayMainVideo.style.display = "block";
            overlayMainVideo.play();
        }

        overlayImageCounter.textContent = `${index + 1} / ${images.length}`;
        updateOverlayThumbnails(index);
        // updateOverlayPaginationDots(index);
        updateNavButtons();
        updateThumbnailNavButtons();
    }

    function createThumbnail(media, index) {
        const wrapper = document.createElement("div");
        wrapper.classList.add("thumbnail-wrapper");

        const img = document.createElement("img");
        img.src = media.poster || media.src;
        img.alt = `Thumbnail ${index + 1}`;

        if (media.type === "video") {
            wrapper.classList.add("is-video");
        }

        wrapper.appendChild(img);

        wrapper.addEventListener("click", () => {
            currentImageIndex = index;
            displayMainMedia(currentImageIndex);
        });

        return wrapper;
    }

    function createOverlayThumbnail(media, index) {
        const wrapper = document.createElement("div");
        wrapper.classList.add("thumbnail-wrapper");

        if (media.type === "video") {
            wrapper.classList.add("is-video");
        }

        const img = document.createElement("img");
        img.src = media.poster || media.src;
        img.alt = `Thumbnail ${index + 1}`;

        wrapper.appendChild(img);

        wrapper.addEventListener("click", () => {
            currentImageIndex = index;
            displayOverlayMedia(currentImageIndex);
        });

        return wrapper;
    }

    function populateThumbnails() {
        thumbnailCarousel.innerHTML = "";
        overlayThumbnails.innerHTML = "";
        images.forEach((media, index) => {
            thumbnailCarousel.appendChild(createThumbnail(media, index));
            overlayThumbnails.appendChild(createOverlayThumbnail(media, index));
        });
        updateThumbnailNavButtons();
    }

    function updateThumbnails(activeIndex) {
        Array.from(thumbnailCarousel.children).forEach((thumb, index) => {
            thumb.classList.toggle("active", index === activeIndex);
        });

        // Auto-scroll thumbnail carousel to keep active thumbnail visible
        scrollThumbnailToActive(activeIndex);
    }

    function scrollThumbnailToActive(activeIndex) {
        const activeThumbnail = thumbnailCarousel.children[activeIndex];
        if (!activeThumbnail) return;

        const carouselRect = thumbnailCarousel.getBoundingClientRect();
        const thumbnailRect = activeThumbnail.getBoundingClientRect();

        // Calculate if thumbnail is visible in the carousel with some margin
        const margin = 20; // Add some margin for better visibility
        const isVisible =
            thumbnailRect.left >= carouselRect.left + margin &&
            thumbnailRect.right <= carouselRect.right - margin;

        if (!isVisible) {
            // Calculate scroll position to center the active thumbnail
            const thumbnailWidth = activeThumbnail.offsetWidth;
            const carouselWidth = thumbnailCarousel.offsetWidth;
            const scrollPosition =
                activeThumbnail.offsetLeft -
                carouselWidth / 2 +
                thumbnailWidth / 2;

            // Ensure we don't scroll beyond the boundaries
            const maxScroll = thumbnailCarousel.scrollWidth - carouselWidth;
            const finalScrollPosition = Math.max(
                0,
                Math.min(scrollPosition, maxScroll)
            );

            thumbnailCarousel.scrollTo({
                left: finalScrollPosition,
                behavior: "smooth",
            });
        }
    }

    function updateOverlayThumbnails(activeIndex) {
        Array.from(overlayThumbnails.children).forEach((thumb, index) => {
            thumb.classList.toggle("active", index === activeIndex);
        });

        // Auto-scroll overlay thumbnail carousel to keep active thumbnail visible
        scrollOverlayThumbnailToActive(activeIndex);
    }

    function scrollOverlayThumbnailToActive(activeIndex) {
        const activeThumbnail = overlayThumbnails.children[activeIndex];
        if (!activeThumbnail) return;

        const carouselRect = overlayThumbnails.getBoundingClientRect();
        const thumbnailRect = activeThumbnail.getBoundingClientRect();

        // Calculate if thumbnail is visible in the carousel with some margin
        const margin = 20; // Add some margin for better visibility
        const isVisible =
            thumbnailRect.left >= carouselRect.left + margin &&
            thumbnailRect.right <= carouselRect.right - margin;

        if (!isVisible) {
            // Calculate scroll position to center the active thumbnail
            const thumbnailWidth = activeThumbnail.offsetWidth;
            const carouselWidth = overlayThumbnails.offsetWidth;
            const scrollPosition =
                activeThumbnail.offsetLeft -
                carouselWidth / 2 +
                thumbnailWidth / 2;

            // Ensure we don't scroll beyond the boundaries
            const maxScroll = overlayThumbnails.scrollWidth - carouselWidth;
            const finalScrollPosition = Math.max(
                0,
                Math.min(scrollPosition, maxScroll)
            );

            overlayThumbnails.scrollTo({
                left: finalScrollPosition,
                behavior: "smooth",
            });
        }
    }

    function updatePaginationDots(activeIndex) {
        paginationDots.innerHTML = "";

        for (let i = 0; i < 3; i++) {
            const dot = document.createElement("span");
            dot.classList.add("dot");
            if (i === activeIndex % 3) dot.classList.add("active");
            dot.addEventListener("click", () => {
                currentImageIndex = i;
                displayMainMedia(currentImageIndex);
            });
            paginationDots.appendChild(dot);
        }
    }

    function updateOverlayPaginationDots(activeIndex) {
        overlayPaginationDots.innerHTML = "";
        // Same note as above for main pagination dots.
        for (let i = 0; i < 3; i++) {
            const dot = document.createElement("span");
            dot.classList.add("dot");
            if (i === activeIndex % 3) dot.classList.add("active");
            dot.addEventListener("click", () => {
                currentImageIndex = i;
                displayOverlayMedia(currentImageIndex);
            });
            overlayPaginationDots.appendChild(dot);
        }
    }

    function showNextMedia() {
        if (currentImageIndex < images.length - 1) {
            currentImageIndex++;
            displayMainMedia(currentImageIndex);
        }
    }

    function showPrevMedia() {
        if (currentImageIndex > 0) {
            currentImageIndex--;
            displayMainMedia(currentImageIndex);
        }
    }

    function showNextOverlayMedia() {
        overlayMainVideo.pause();
        if (currentImageIndex < images.length - 1) {
            currentImageIndex++;
            displayOverlayMedia(currentImageIndex);
        }
    }

    function showPrevOverlayMedia() {
        overlayMainVideo.pause();
        if (currentImageIndex > 0) {
            currentImageIndex--;
            displayOverlayMedia(currentImageIndex);
        }
    }

    // Thumbs nav scrolls
    carouselPrev.addEventListener("click", () =>
        thumbnailCarousel.scrollBy({ left: -120, behavior: "smooth" })
    );
    carouselNext.addEventListener("click", () =>
        thumbnailCarousel.scrollBy({ left: 120, behavior: "smooth" })
    );
    thumbsPrev.addEventListener("click", () =>
        overlayThumbnails.scrollBy({ left: -80, behavior: "smooth" })
    );
    thumbsNext.addEventListener("click", () =>
        overlayThumbnails.scrollBy({ left: 80, behavior: "smooth" })
    );

    // Re-check nav buttons on scroll
    thumbnailCarousel.addEventListener("scroll", updateThumbnailNavButtons);
    overlayThumbnails.addEventListener("scroll", updateThumbnailNavButtons);

    mainImage.addEventListener("click", () => {
        overlaySlider.style.display = "flex";
        displayOverlayMedia(currentImageIndex);
    });
    mainVideo.addEventListener("click", () => {
        overlaySlider.style.display = "flex";
        displayOverlayMedia(currentImageIndex);
    });

    closeOverlayBtn.addEventListener("click", () => {
        overlaySlider.style.display = "none";
        overlayMainVideo.pause();
        displayMainMedia(currentImageIndex);
    });

    document.addEventListener("keydown", (e) => {
        if (overlaySlider.style.display === "flex") {
            if (e.key === "Escape") {
                overlaySlider.style.display = "none";
                overlayMainVideo.pause();
                displayMainMedia(currentImageIndex);
            } else if (e.key === "ArrowLeft") {
                showPrevOverlayMedia();
            } else if (e.key === "ArrowRight") {
                showNextOverlayMedia();
            }
        } else {
            // Keyboard navigation for main slider when overlay is not open
            if (e.key === "ArrowLeft") {
                e.preventDefault();
                showPrevMedia();
            } else if (e.key === "ArrowRight") {
                e.preventDefault();
                showNextMedia();
            }
        }
    });

    mainDownloadBtn.addEventListener("click", downloadAllImages);
    overlayDownloadIcon.addEventListener("click", downloadAllImages);

    async function downloadAllImages() {
        if (typeof JSZip === "undefined" || typeof saveAs === "undefined") {
            console.error(
                "JSZip or FileSaver.js is not loaded. Please include them in your HTML."
            );
            alert(
                "Download functionality is not available. Please check the console for details."
            );
            return;
        }

        const zip = new JSZip();
        const folder = zip.folder("Gallery_Images");
        const imageFiles = images.filter((item) => item.type === "image");
        for (const item of imageFiles) {
            try {
                const response = await fetch(item.src);
                const blob = await response.blob();
                const filename = item.src.substring(
                    item.src.lastIndexOf("/") + 1
                );
                folder.file(filename, blob);
            } catch (err) {
                console.error(`Failed to download ${item.src}:`, err);
            }
        }
        zip.generateAsync({ type: "blob" }).then((content) => {
            saveAs(content, "gallery_images.zip");
        });
    }

    overlayShareIcon.addEventListener("click", () => {
        if (navigator.share) {
            navigator
                .share({
                    title: "Check out this media!",
                    url: images[currentImageIndex].src,
                })
                .catch((err) => {
                    if (err.name !== "AbortError") {
                        console.error("Error sharing:", err);
                    }
                });
        } else {
            alert(
                "Web Share API not supported. You can manually copy the URL: " +
                    images[currentImageIndex].src
            );
        }
    });

    prevButton.addEventListener("click", showPrevMedia);
    nextButton.addEventListener("click", showNextMedia);
    overlayPrevBtn.addEventListener("click", showPrevOverlayMedia);
    overlayNextBtn.addEventListener("click", showNextOverlayMedia);

    fetchImageData();
    if (images.length > 0) {
        populateThumbnails();
        displayMainMedia(currentImageIndex);
    }
});

document.querySelectorAll(".share-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        navigator.clipboard.writeText(window.location.href).catch((err) => {});
    });
});

function copyLink() {
    const currentLink = window.location.href;
    navigator.clipboard.writeText(currentLink).catch((err) => {
        console.error("Copy failed: ", err);
    });
}

// filter js
// 👉 Custom format for Salaam dropdown
function formatOption(data) {
    if (!data.id) return data.text;

    const price = $(data.element).data("price") || "";
    const subtitle = $(data.element).data("subtitle") || "";
    const selectedValue = $("#salaamSelect").val();
    const isActive = data.id == selectedValue ? "active" : "";

    const html = `
  <div class="custom-option-card ${isActive}">
    <label class="checkbox">
      <input type="checkbox" disabled ${isActive ? "checked" : ""} />
      <span class="custom-checkmark"></span>
    </label>
    <div class="middle">
      <div class="title">${data.text}</div>
      <div class="subtitle">${subtitle}</div>
    </div>
    <div class="price">${price}</div>
  </div>
`;

    return html;
}

function formatSelection(data) {
    return data.text;
}

function activateDropdown(selector, useCustom) {
    const $select = $(selector);

    if (useCustom) {
        $select.select2({
            minimumResultsForSearch: 0,
            dropdownParent: $select.closest(".vs-select-wrapper"),
            templateResult: formatOption,
            templateSelection: formatSelection,
            escapeMarkup: function (m) {
                return m;
            },
        });
    } else {
        $select.select2({
            minimumResultsForSearch: 0,
            dropdownParent: $select.closest(".vs-select-wrapper"),
        });
    }

    $select.on("select2:open", function () {
        let $dropdown = $(".select2-container--open .select2-dropdown");
        setTimeout(function () {
            $dropdown.css({
                top: "100%",
                bottom: "auto",
                transform: "none",
                position: "absolute",
                left: "0",
            });
        }, 0);
        $select.closest(".vs-select-wrapper").addClass("select-open");
    });

    $select.on("select2:closing", function () {
        $select.closest(".vs-select-wrapper").removeClass("select-open");
    });

    if (useCustom) {
        $select.on("select2:select", function (e) {
            $(this).trigger("change.select2");

            const container = $(this)
                .next(".select2-container")
                .find(".select2-selection--single");
            if (e.params.data.id !== "") {
                container.addClass("red-selected");
            } else {
                container.removeClass("red-selected");
            }
        });
    } else {
        $select.on("select2:select", function (e) {
            const container = $(this)
                .next(".select2-container")
                .find(".select2-selection--single");
            if (e.params.data.id !== "") {
                container.addClass("red-selected");
            } else {
                container.removeClass("red-selected");
            }
        });
    }
}

$(document).ready(function () {
    activateDropdown("#salaamSelect", true);

    activateDropdown("#makeSelect", false);
    activateDropdown("#makeSelectCountry", false);

    $(".vs-select-wrapper").on("click", function (e) {
        if ($(e.target).closest(".select2-container").length) return;
        const $select = $(this).find("select");
        $select.select2("open");
    });
});

const inputs = document.querySelectorAll(
    "#contactForm input, #contactForm textarea"
);

inputs.forEach((input) => {
    input.addEventListener("input", () => {
        if (input.value.trim() !== "") {
            input.classList.add("filled");
        } else {
            input.classList.remove("filled");
        }
    });
});

// Set the countdown duration (in hours)
const countdownHours = 20;
const now = new Date().getTime();
const endTime = now + countdownHours * 60 * 60 * 1000;

const countdownElements = document.querySelectorAll(".countdown");

function updateCountdown() {
    const currentTime = new Date().getTime();
    const remaining = endTime - currentTime;

    let display = "";

    if (remaining <= 0) {
        display = "Offer expired";
        clearInterval(timer);
    } else {
        const hours = Math.floor(remaining / (1000 * 60 * 60));
        const minutes = Math.floor(
            (remaining % (1000 * 60 * 60)) / (1000 * 60)
        );
        const seconds = Math.floor((remaining % (1000 * 60)) / 1000);

        display =
            `${String(hours).padStart(2, "0")}<span class="unit">h</span> ` +
            `${String(minutes).padStart(2, "0")}<span class="unit">m</span> ` +
            `${String(seconds).padStart(2, "0")}<span class="unit">s</span>`;
    }

    countdownElements.forEach((el) => {
        el.innerHTML = display;
    });
}

// Initial call
updateCountdown();

// Update every second
const timer = setInterval(updateCountdown, 1000);

document.addEventListener("DOMContentLoaded", function () {
    const showOfferBox = localStorage.getItem("showOfferBox");

    if (showOfferBox === "true") {
        document.querySelectorAll(".offer-box").forEach((box) => {
            box.classList.add("show");
        });

        // Remove the flag so it only runs once
        localStorage.removeItem("showOfferBox");
    }
});
