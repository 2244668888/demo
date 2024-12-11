$.sidebarMenu = function (menu) {
    var animationSpeed = 300;

    $(menu).on("click", "li a", function (e) {
        var $this = $(this);
        var checkElement = $this.next();

        if (checkElement.is(".treeview-menu") && checkElement.is(":visible")) {
            checkElement.slideUp(animationSpeed, function () {
                checkElement.removeClass("menu-open");
            });
            checkElement.parent("li").removeClass("active");
        }

        //If the menu is not visible
        else if (
            checkElement.is(".treeview-menu") &&
            !checkElement.is(":visible")
        ) {
            //Get the parent menu
            var parent = $this.parents("ul").first();
            //Close all open menus within the parent
            var ul = parent.find("ul:visible").slideUp(animationSpeed);
            //Remove the menu-open class from the parent
            ul.removeClass("menu-open");
            //Get the parent li
            var parent_li = $this.parent("li");

            //Open the target menu and add the menu-open class
            checkElement.slideDown(animationSpeed, function () {
                //Add the class active to the parent li
                checkElement.addClass("menu-open");
                parent.find("li.active").removeClass("active");
                parent_li.addClass("active");
            });
        }
        //if this isn't a link, prevent the page from being redirected
        if (checkElement.is(".treeview-menu")) {
            e.preventDefault();
        }
    });
};
$.sidebarMenu($(".sidebar-menu"));

// Custom Sidebar JS
jQuery(function ($) {
    //toggle sidebar
    $("#toggle-sidebar").on("click", function () {
        $(".page-wrapper").toggleClass("toggled");
    });

    // Pin sidebar on click
    $("#pin-sidebar").on("click", function () {
        if ($(".page-wrapper").hasClass("pinned")) {
            // unpin sidebar when hovered
            $(".page-wrapper").removeClass("pinned");
            $("#sidebar").unbind("hover");
        } else {
            $(".page-wrapper").addClass("pinned");
            $("#sidebar").hover(
                function () {
                    console.log("mouseenter");
                    $(".page-wrapper").addClass("sidebar-hovered");
                },
                function () {
                    console.log("mouseout");
                    $(".page-wrapper").removeClass("sidebar-hovered");
                }
            );
        }
    });

    // Pinned sidebar
    $(function () {
        $(".page-wrapper").hasClass("pinned");
        $("#sidebar").hover(
            function () {
                // console.log("mouseenter");
                $(".page-wrapper").addClass("sidebar-hovered");
            },
            function () {
                // console.log("mouseout");
                $(".page-wrapper").removeClass("sidebar-hovered");
            }
        );
    });

    // Toggle sidebar overlay
    $("#overlay").on("click", function () {
        $(".page-wrapper").toggleClass("toggled");
    });

    // Added by Srinu
    $(function () {
        // When the window is resized,
        $(window).resize(function () {
            // When the width and height meet your specific requirements or lower
            if ($(window).width() <= 768) {
                $(".page-wrapper").removeClass("pinned");
            }
        });
        // When the window is resized,
        $(window).resize(function () {
            // When the width and height meet your specific requirements or lower
            if ($(window).width() >= 768) {
                $(".page-wrapper").removeClass("toggled");
            }
        });
    });
});

// Day Filter
$(function () {
    $(".day-filters .btn").click(function () {
        $(".day-filters .btn").removeClass("btn-info");
        $(this).addClass("btn-info");
    });
});

// Card Loading
$(function () {
    $(".card-loader").fadeOut(2000);
});

/***********
***********
***********
	Bootstrap JS
***********
***********
***********/

// Tooltip
var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
);
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Popover
var popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
);
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
});

initializeTableSearch('myTable');

function initializeTableSearch(tableId) {
    let thead = $(`#${tableId} thead tr`).clone(true);
    $(`#${tableId} thead`).after('<tfoot></tfoot>');
    $(`#${tableId} tfoot`).append(thead);
    // Initialize DataTable
    $('#' + tableId).DataTable({
        columnDefs: [{
                width: '10%',
                targets: 0
            },
            {
                width: '10%',
                targets: -1
            }
        ],
        initComplete: function () {
            this.api()
                .columns()
                .every(function () {
                    let column = this;
                    let title = column.footer().textContent;

                    let input = document.createElement('input');
                    input.placeholder = title;
                    input.style.background = 'transparent';
                    input.style.color = 'white';
                    column.footer().replaceChildren(input);

                    input.addEventListener('keyup', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                    });
                });
        }
    });
}

$('.form-select').select2();
