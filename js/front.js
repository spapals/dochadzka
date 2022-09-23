$(function () {

    // ------------------------------------------------------- //
    // Tooltips init
    // ------------------------------------------------------ //    

    $('[data-toggle="tooltip"]').tooltip();

    // ------------------------------------------------------- //
    // Universal Form Validation
    // ------------------------------------------------------ //

    $('.form-validate').each(function () {
        $(this).validate({
            errorElement: "div",
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            ignore: ':hidden:not(.summernote),.note-editable.card-block',
            errorPlacement: function (error, element) {
                // Add the `invalid-feedback` class to the error element
                error.addClass("invalid-feedback");
                //console.log(element);
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.siblings("label"));
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });

    // ------------------------------------------------------- //
    // Material Inputs
    // ------------------------------------------------------ //

    var materialInputs = $('input.input-material');

    // activate labels for prefilled values
    materialInputs.filter(function () {
        return $(this).val() !== "";
    }).siblings('.label-material').addClass('active');

    // move label on focus
    materialInputs.on('focus', function () {
        $(this).siblings('.label-material').addClass('active');
    });

    // remove/keep label on blur
    materialInputs.on('blur', function () {
        $(this).siblings('.label-material').removeClass('active');

        if ($(this).val() !== '') {
            $(this).siblings('.label-material').addClass('active');
        } else {
            $(this).siblings('.label-material').removeClass('active');
        }
    });

    // ------------------------------------------------------- //
    // Footer 
    // ------------------------------------------------------ //   

    var pageContent = $('.page-content');

    $(document).on('sidebarChanged', function () {
        adjustFooter();
    });

    $(window).on('resize', function () {
        adjustFooter();
    });

    function adjustFooter() {
        var footerBlockHeight = $('.footer__block').outerHeight();
        pageContent.css('padding-bottom', footerBlockHeight + 'px');
    }

    // ------------------------------------------------------- //
    // Adding fade effect to dropdowns
    // ------------------------------------------------------ //
    $('.dropdown').on('show.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).fadeIn(100).addClass('active');
    });
    $('.dropdown').on('hide.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).fadeOut(100).removeClass('active');
    });


    // ------------------------------------------------------- //
    // Search Popup
    // ------------------------------------------------------ //
    $('.search-open').on('click', function (e) {
        e.preventDefault();
        $('.search-panel').fadeIn(100);
    });
    $('.search-panel .close-btn').on('click', function () {
        $('.search-panel').fadeOut(100);
    });


    // ------------------------------------------------------- //
    // Sidebar Functionality
    // ------------------------------------------------------ //
    $('.sidebar-toggle').on('click', function () {
        $(this).toggleClass('active');

        $('#sidebar').toggleClass('shrinked');
        $('.page-content').toggleClass('active');
        $(document).trigger('sidebarChanged');

        if ($('.sidebar-toggle').hasClass('active')) {
            $('.navbar-brand .brand-sm').addClass('visible');
            $('.navbar-brand .brand-big').removeClass('visible');
            $(this).find('i').attr('class', 'fa fa-long-arrow-right');
        } else {
            $('.navbar-brand .brand-sm').removeClass('visible');
            $('.navbar-brand .brand-big').addClass('visible');
            $(this).find('i').attr('class', 'fa fa-long-arrow-left');
        }
    });


    // ------------------------------------------------------ //
    // For demo purposes, can be deleted
    // ------------------------------------------------------ //

    if ($('#style-switch').length > 0) {
        var stylesheet = $('link#theme-stylesheet');
        $("<link id='new-stylesheet' rel='stylesheet'>").insertAfter(stylesheet);
        var alternateColour = $('link#new-stylesheet');

        if ($.cookie("theme_csspath")) {
            alternateColour.attr("href", $.cookie("theme_csspath"));
        }

        $("#colour").change(function () {
            if ($(this).val() !== '') {
                var theme_csspath = 'css/style.' + $(this).val() + '.css';
                alternateColour.attr("href", theme_csspath);
                $.cookie("theme_csspath", theme_csspath, {
                    expires: 365,
                    path: document.URL.substr(0, document.URL.lastIndexOf('/'))
                });

            }

            return false;
        });
    }

});

// ------------------------------------------------------ //
// CUSTOM BRO
// ------------------------------------------------------ //
$('#type').on('change', function (e) {
    var type = document.getElementById("type").value;
    $('.date_to').show();
    $('.time').hide();
    $('.time_to').hide();
    if (type == 'PD') {
        $('.date_to').hide();
        $('.time').show();
        $('.time').prop('required', true);
        $('.time_to').show();
        $('.time_to').prop('required', true);
    }
    if (type == 'SC') {
        $('.date_to').hide();
        $('.time').show();
        $('.time').prop('required', true);
        $('.time_to').show();
        $('.time_to').prop('required', true);
    }
    if (type == 'L' || type == 'LC') {
        $('.date_to').hide();
        $('.time').show();
        $('.time_to').show();
        $('.time').prop('required', true);
    }
    if (type == 'H' || type == 'S') {
        $('.date_to').hide();
        $('.time').show();
        $('.time').prop('required', true);
        $('.time_to').show();
        $('.time_to').prop('required', true);
    }
}).change();

$(".sw-delete").on("click", function (e) {
    $flag = $(this).attr('data-flag');
    var $id = $(this).parent().find('#uid').val();
    var completeData = $(this).serialize();
    console.log(completeData);

    if ($flag == 0) {
        e.preventDefault(); //to prevent submitting
        swal({
            title: "Vymazať?",
            text: "Naozaj chcete vymazať?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#cc3f44",
            cancelButtonColor: "#cc3f44",
            confirmButtonText: "Áno",
            cancelButtonText: "Nie",
            closeOnConfirm: true,
            html: false
        }, function (confirmed) {
            if (confirmed) {
                $.ajax({
                    type: "POST",
                    url: "",
                    data: {'submit': 1, id: $id},
                    cache: false,
                    success: function (response) {
                        swal(
                            "Úspešné!",
                            "Požiadavka bola vymazaná!",
                            "success"
                        );
                        setTimeout(function () {
                            location.reload();
                        }, 5000);
                    },
                    failure: function (response) {
                        swal(
                            "Chyba",
                            "Požiadavka nebola vymazaná!",
                            "error"
                        );
                        setTimeout(function () {
                            location.reload();
                        }, 5000);
                    }
                });
            }
        });
    }

    return true;
});

$(document).ready(function () {
    $('#dochadzka-zamestnanci').DataTable({
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
});
