function activateDropdown(selector) {
    const $select = $(selector);

    $select.select2({
        minimumResultsForSearch: 0,
        dropdownParent: $select.closest(".vs-select-wrapper"),
    });

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

$(document).ready(function () {
    activateDropdown("#makeSelect");
    activateDropdown("#modelSelect");
    activateDropdown("#gearSelect");
    activateDropdown("#yearMin");
    activateDropdown("#yearMax");

    for (let year = 2000; year <= 2025; year++) {
        $("#yearMin").append(`<option value="${year}">${year}</option>`);
        $("#yearMax").append(`<option value="${year}">${year}</option>`);
    }

    $(".vs-select-wrapper").on("click", function (e) {
        if ($(e.target).closest(".select2-container").length) return;
        const $select = $(this).find("select");
        $select.select2("open");
    });

    //model select category and sub category
    $("#makeSelect").on("change", function (e) {
        var select_val = $(e.currentTarget).val();
        for (cat = 0; cat < models.length; cat++) {
            if (models[cat].category_name == select_val) {
                $("#modelSelect").empty();
                var sub_category = models[cat].children;
                if (sub_category.length > 1) {
                    $("#modelSelect").append(
                        '<option value="">' + "Any" + "</option>"
                    );
                    for (sub = 0; sub < sub_category.length; sub++) {
                        $("#modelSelect").append(
                            '<option value="' +
                                sub_category[sub] +
                                '">' +
                                sub_category[sub] +
                                "</option>"
                        );
                    }
                } else {
                    $("#modelSelect").append(
                        '<option value="">' + "Any" + "</option>"
                    );
                }
            }
        }
    });
});

const input = document.querySelector(".vs-search-input input");
const form = document.querySelector(".vs-search-input");

function updateSearchStyle() {
    if (input.value.trim() !== "") {
        form.classList.add("active");
        input.classList.add("has-text");
    } else {
        form.classList.remove("active");
        input.classList.remove("has-text");
    }
}

input.addEventListener("input", updateSearchStyle);
input.addEventListener("blur", updateSearchStyle);
input.addEventListener("focus", updateSearchStyle);

const searchByTypeBtn = document.getElementById("searchByTypeBtn");
const searchByMakeBtn = document.getElementById("searchByMakeBtn");
const leftArrow = document.getElementById("leftArrow");
const rightArrow = document.getElementById("rightArrow");
const paginationDots = document.getElementById("paginationDots");

const vehicleTypesBlock = document.querySelector(".vehicle-types");
const vehicleMakesBlock = document.querySelector(".vehicle-makes");

let activeBlock = vehicleTypesBlock;
let currentPage = 0;
let itemsPerPage = 9;

function getItems(block) {
    return Array.from(block.querySelectorAll(".grid-item"));
}

function updatePagination() {
    // Make sure only one block is visible at a time
    vehicleTypesBlock.classList.add("hidden");
    vehicleMakesBlock.classList.add("hidden");
    activeBlock.classList.remove("hidden");

    // Hide all items in the active block
    const items = getItems(activeBlock);
    items.forEach((item) => item.classList.add("hidden"));

    // Show only items for the current page
    const start = currentPage * itemsPerPage;
    const end = start + itemsPerPage;
    items.slice(start, end).forEach((item) => item.classList.remove("hidden"));

    // Arrow visibility
    const totalPages = Math.ceil(items.length / itemsPerPage);
    leftArrow.classList.toggle("hidden", currentPage === 0);
    rightArrow.classList.toggle("hidden", currentPage >= totalPages - 1);

    // Pagination dots
    paginationDots.innerHTML = "";
    for (let i = 0; i < totalPages; i++) {
        const dot = document.createElement("div");
        dot.className = `dot ${i === currentPage ? "active" : ""}`;
        dot.addEventListener("click", () => {
            currentPage = i;
            updatePagination();
        });
        paginationDots.appendChild(dot);
    }
}

searchByTypeBtn.addEventListener("click", () => {
    searchByTypeBtn.classList.add("active");
    searchByMakeBtn.classList.remove("active");

    activeBlock = vehicleTypesBlock;
    currentPage = 0;
    updatePagination();
});

searchByMakeBtn.addEventListener("click", () => {
    searchByMakeBtn.classList.add("active");
    searchByTypeBtn.classList.remove("active");

    activeBlock = vehicleMakesBlock;
    currentPage = 0;
    updatePagination();
});

rightArrow.addEventListener("click", () => {
    const items = getItems(activeBlock);
    const totalPages = Math.ceil(items.length / itemsPerPage);
    if (currentPage < totalPages - 1) {
        currentPage++;
        updatePagination();
    }
});

leftArrow.addEventListener("click", () => {
    if (currentPage > 0) {
        currentPage--;
        updatePagination();
    }
});

function calculateItemsPerPage() {
    itemsPerPage = window.innerWidth <= 1024 ? 12 : 12;
}

window.addEventListener("resize", () => {
    calculateItemsPerPage();
    currentPage = 0;
    updatePagination();
});

window.onload = () => {
    calculateItemsPerPage();
    updatePagination();
};

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
