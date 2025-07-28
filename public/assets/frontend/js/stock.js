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
    var local_url = new URL(window.location.href);
    var body_type = local_url.searchParams.get("body_type");
    var make_type = local_url.searchParams.get("make_type");
    var gear = local_url.searchParams.get("gear");

    activateDropdown("#salaamSelect", false);

    activateDropdown("#makeSelect", false);
    activateDropdown("#modelSelect", false);
    activateDropdown("#gearSelect", false);
    activateDropdown("#yearMin", false);
    activateDropdown("#yearMax", false);

    activateDropdown("#filterMake", false);
    activateDropdown("#filterModel", false);
    activateDropdown("#filterGear", false);
    activateDropdown("#filterYearMin", false);
    activateDropdown("#filterYearMax", false);
    activateDropdown("#filterPriceFrom", false);
    activateDropdown("#filterPriceTo", false);
    activateDropdown("#sortSelect", false);

    // Check if we have stored values in localStorage
    const storedCountry = localStorage.getItem("country");
    const storedPort = localStorage.getItem("port_name");
    const storedInspection = localStorage.getItem("inspection_price");
    const storedInsurance = localStorage.getItem("insurance_price");

    // Set up the makeSelect change handler (works for all cases)
    $("#makeSelect").on("change", function (e) {
        e.preventDefault();
        e.stopPropagation();
        var id = $(e.currentTarget).val();

        // Debug: Check if variables are defined
        console.log("select_port URL:", select_port);
        console.log("Selected ID:", id);

        if (typeof select_port === "undefined") {
            console.error("select_port variable is not defined!");
            alert(
                "Error: select_port URL is not defined. Please check if the script variables are loaded correctly."
            );
            return;
        }

        $.ajax({
            url: select_port,
            data: {
                id: id,
            },
            type: "get",
        })
            .done(function (response) {
                var port_list = response.port_list;
                var port_list_array = $.map(port_list, function (value, index) {
                    return [[index, value]];
                });
                var html = ""; // Properly declare variable
                if (port_list_array) {
                    for (var i = 0; i < port_list_array.length; i++) {
                        html += `<option value='${JSON.stringify(
                            port_list_array[i][1]
                        )}'>${capitalizeFirstLetter(
                            port_list_array[i][0]
                        )}</option>`;
                    }
                }
                html += '<option value="0"></option>';
                $("#salaamSelect").find("option").remove().end().append(html);

                // After ports are loaded, set the stored values and calculate price (only if they exist)
                console.log("Stored values:", {
                    storedPort: storedPort,
                    storedInspection: storedInspection,
                    storedInsurance: storedInsurance,
                });

                if (storedPort && storedInspection && storedInsurance) {
                    // Try to select the stored port
                    let portFound = false;
                    $("#salaamSelect option").each(function () {
                        if ($(this).text() === storedPort) {
                            $("#salaamSelect")
                                .val($(this).val())
                                .trigger("change");
                            portFound = true;
                            return false; // Break the loop
                        }
                    });

                    // If port not found by text, try the first available option
                    if (!portFound && $("#salaamSelect option").length > 0) {
                        $("#salaamSelect")
                            .val($("#salaamSelect option:first").val())
                            .trigger("change");
                    }

                    // Set inspection value and update UI
                    console.log("Setting inspection to:", storedInspection);
                    console.log(
                        "Available inspection options:",
                        $(".inspection option")
                            .map(function () {
                                return $(this).val() + " - " + $(this).text();
                            })
                            .get()
                    );

                    $(".inspection").val(storedInspection).trigger("change");
                    if (storedInspection == "0") {
                        $('input[name="inspection"][value="0"]').prop(
                            "checked",
                            true
                        );
                        console.log("Set inspection radio to No");
                    } else {
                        $('input[name="inspection"]:not([value="0"])').prop(
                            "checked",
                            true
                        );
                        console.log("Set inspection radio to Yes");
                    }

                    // Set insurance value and update UI
                    console.log("Setting insurance to:", storedInsurance);
                    console.log(
                        "Available insurance options:",
                        $(".insurance option")
                            .map(function () {
                                return $(this).val() + " - " + $(this).text();
                            })
                            .get()
                    );

                    $(".insurance").val(storedInsurance).trigger("change");
                    if (storedInsurance == "0") {
                        $('input[name="insurance"][value="0"]').prop(
                            "checked",
                            true
                        );
                        console.log("Set insurance radio to No");
                    } else {
                        $('input[name="insurance"]:not([value="0"])').prop(
                            "checked",
                            true
                        );
                        console.log("Set insurance radio to Yes");
                    }

                    // Calculate price with the restored values
                    price_calc();
                }
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.error("AJAX Error:", {
                    status: jqXHR.status,
                    statusText: jqXHR.statusText,
                    responseText: jqXHR.responseText,
                    thrownError: thrownError,
                });
            });
    });

    // If we have stored values, set the form fields and trigger the change event
    if (storedCountry && storedPort && storedInspection && storedInsurance) {
        // Set country and trigger change to load ports via AJAX
        $("#makeSelect").val(storedCountry).trigger("change");
    }
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
    $(".select-category").on("change", function (e) {
        var select_val = $(e.currentTarget).val();
        for (cat = 0; cat < models.length; cat++) {
            if (models[cat].category_name == select_val) {
                $(".subcategory").empty();
                var sub_category = models[cat].children;
                if (sub_category.length > 1) {
                    $(".subcategory").append(
                        '<option value="">' + "Any" + "</option>"
                    );
                    for (sub = 0; sub < sub_category.length; sub++) {
                        $(".subcategory").append(
                            '<option value="' +
                                sub_category[sub] +
                                '">' +
                                sub_category[sub] +
                                "</option>"
                        );
                    }
                } else {
                    $(".subcategory").append(
                        '<option value="">' + "Any" + "</option>"
                    );
                }
            }
        }
    });

    // Note: #makeSelect change handler is now consolidated above in the localStorage condition block
    var page = 1;
    var sort_by = "new_arriaval";
    // load more button
    $(document).on("click", "#load_more_button", function () {
        $("#load_more_button").html("<b>Loading...</b>");
        page++;
        infinteLoadMore(page);
    });
    $(document).on("click", "#price-calc", function () {
        localStorage.setItem("country", $("#makeSelect").val());
        localStorage.setItem(
            "port_name",
            $("#salaamSelect option:selected").text()
        );
        localStorage.setItem(
            "inspection_price",
            $(".inspection option:selected").val()
        );
        localStorage.setItem(
            "insurance_price",
            $(".insurance option:selected").val()
        );

        port_array = JSON.parse($("#salaamSelect option:selected").val());
        localStorage.setItem("port_array", JSON.stringify(port_array));
        price_calc();
    });
    $(".sort-by").on("change", function (e) {
        e.preventDefault();
        e.stopPropagation();
        sort_by = $(e.currentTarget).val();
        $.ajax({
            url: sock_page,
            data: {
                body_type: body_type,
                make_type: make_type,
                search_keyword: search_keyword,
                maker: maker,
                model_name: model_name,
                from_year: from_year,
                to_year: to_year,
                from_price: from_price,
                to_price: to_price,
                sort_by: sort_by,
                gear: gear,
            },
            type: "get",
        })
            .done(function (response) {
                $("#car-listings-container").empty();
                $("#car-listings-container").append(response);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log("Server error occured");
            });
    });
    function infinteLoadMore(page) {
        $.ajax({
            url: sock_page + "?page=" + page,
            data: {
                body_type: body_type,
                make_type: make_type,
                search_keyword: search_keyword,
                maker: maker,
                model_name: model_name,
                from_year: from_year,
                to_year: to_year,
                from_price: from_price,
                to_price: to_price,
                sort_by: sort_by,
                gear: gear,
                // price_country:price_country,
                // price_port:price_port,
                // inspection:inspection,
                // insurance:insurance,
            },
            type: "get",
        })
            .done(function (response) {
                $("#load_more_button").remove();
                if (response.length <= 24) {
                    $("#load_more_button").hide();
                    //$('.auto-load').html("We don't have more data to display :(");
                    return;
                }
                $("#car-listings-container").append(response);

                if (page > 1) {
                    price_calc();
                }
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log("Server error occured");
            });
    }
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

document.addEventListener("DOMContentLoaded", () => {
    const radioButtons = document.querySelectorAll(
        '.button-group input[type="radio"]'
    );

    radioButtons.forEach((radio) => {
        radio.addEventListener("change", (event) => {
            const groupName = event.target.name;
            const selectedValue = event.target.value;
            console.log(`${groupName} selection changed to: ${selectedValue}`);
        });
    });
});

const toggleBtn = document.getElementById("toggleBtn");
const moreOptions = document.getElementById("moreOptions");

toggleBtn.addEventListener("click", () => {
    const currentDisplay = window.getComputedStyle(moreOptions).display;

    if (currentDisplay === "none") {
        moreOptions.style.display = "block";
        toggleBtn.textContent = "View less";
    } else {
        moreOptions.style.display = "none";
        toggleBtn.textContent = "View more search options";
    }
});

window.addEventListener("resize", () => {
    const width = window.innerWidth;

    if (width >= 1025) {
        // Desktop: show by default, clear inline style
        moreOptions.style.display = "";
        toggleBtn.textContent = "View less"; // Desktop default is visible
    } else {
        // Mobile/tablet: hide by default
        moreOptions.style.display = "none";
        toggleBtn.textContent = "View more search options";
    }
});

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

function price_calc() {
    port_array = JSON.parse($("#salaamSelect option:selected").val());
    port_price = 0;
    port_name = $("#salaamSelect option:selected").text();
    inspection_price = parseInt($(".inspection option:selected").val());
    insurance_price = parseInt($(".insurance option:selected").val());
    country = $("#makeSelect").val();
    $(".car-card").each(function (e) {
        body_type = $(this).find(".body_type").text();
        for (i = 0; i < port_array.length; i++) {
            if (body_type == Object.keys(port_array[i])) {
                port_price = port_array[i][body_type];
            }
        }
        total_price = parseInt($(this).find(".price").val());
        cubic_meter = $(this).find(".cubic-meter").val();
        price_shipping = port_price * cubic_meter;
        if (port_price == 0) {
            cif = "( C & F )";
            final_price = "ASK";
            port_name = "Port";
        } else {
            if (inspection_price == 0) {
                cif = "( CIF )";
            }
            if (insurance_price == 0) {
                cif = "(  C&F Inspect )";
            }
            if (insurance_price == 0 && inspection_price == 0) {
                cif = "( C & F )";
            }
            if (!insurance_price == 0 && !inspection_price == 0) {
                cif = "( CIF Inspect )";
            }
            final_price =
                "$" +
                Math.round(
                    total_price +
                        price_shipping +
                        inspection_price +
                        insurance_price
                ).toLocaleString();
            console.log(
                "final",
                total_price +
                    price_shipping +
                    inspection_price +
                    insurance_price
            );
        }
        if (final_price == "$NaN") {
            final_price = "ASK";
        }
        current_url = $(this).find(".detail-inquire a").attr("data-contents");
        new_url =
            current_url +
            "?country=" +
            country +
            "&port=" +
            port_price +
            "&inspection=" +
            inspection_price +
            "&insurance=" +
            insurance_price +
            "&total_price=" +
            final_price;
        $(this).find(".detail-inquire a").attr("href", new_url);
        $(this).find(".stock-contents a").attr("href", new_url);
        $(this).find(".description-text").text(cif);
        $(this).find(".final-country").text(port_name);
        $(this).find(".final-price-value").text(final_price);
    });
}

leftArrow.addEventListener("click", () => {
    if (currentPage > 0) {
        currentPage--;
        updatePagination();
    }
});

function calculateItemsPerPage() {
    itemsPerPage = window.innerWidth <= 1024 ? 12 : 12;
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
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
