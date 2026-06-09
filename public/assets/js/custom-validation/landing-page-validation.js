(function ($) {
    "use strict";

    $('#landing_PageForm').validate({
        rules: {
            // Header Tab Key
            "header[logo]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "header[favicon]": {
               required: false,
                // extension: "png|jpg|jpeg"
            },
            "header[btn_url]": {
                required: true,
                url: true,
            },
            "header[btn_text]": {
                required: true,
            },
            "header[links][portfolio][url]": {
                required: true,
                url: true,
            },
            "header[links][portfolio][text]": {
                required: true,
            },
            "header[links][hire_us][url]": {
                required: true,
                url: true,
            },
            "header[links][hire_us][text]": {
                required: true,
            },
            "header[links][documentation][url]": {
                required: true,
                url: true,
            },
            "header[links][documentation][text]": {
                required: true,
            },

            // Home Tab Key
            "home[title]": {
                required: true,
            },
            "home[description]": {
                required: true,
            },
            "home[bg_image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[sidebar_cuts_image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[left_down_image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[left_top_image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[star_image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[right_top_image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[right_down_image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[vector_image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[laravel_crud][title]": {
                required: true,
            },
            "home[laravel_crud][description]": {
                required: true,
            },

            // Role
            "home[laravel_crud][role][title]": {
                required: true,
            },
            "home[laravel_crud][role][description]": {
                required: true,
            },
            "home[laravel_crud][role][image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[laravel_crud][role][redirect_url]": {
                required: true,
                url: true,
            },

             // users
             "home[laravel_crud][users][title]": {
                required: true,
            },
            "home[laravel_crud][users][description]": {
                required: true,
            },
            "home[laravel_crud][users][image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[laravel_crud][users][redirect_url]": {
                required: true,
                url: true,
            },

             // blogs
             "home[laravel_crud][blogs][title]": {
                required: true,
            },
            "home[laravel_crud][blogs][description]": {
                required: true,
            },
            "home[laravel_crud][blogs][image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[laravel_crud][blogs][redirect_url]": {
                required: true,
                url: true,
            },

             // categories
             "home[laravel_crud][categories][title]": {
                required: true,
            },
            "home[laravel_crud][categories][description]": {
                required: true,
            },
            "home[laravel_crud][categories][image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[laravel_crud][categories][redirect_url]": {
                required: true,
                url: true,
            },

             // tags
             "home[laravel_crud][tags][title]": {
                required: true,
            },
            "home[laravel_crud][tags][description]": {
                required: true,
            },
            "home[laravel_crud][tags][image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[laravel_crud][tags][redirect_url]": {
                required: true,
                url: true,
            },

             // pages
             "home[laravel_crud][pages][title]": {
                required: true,
            },
            "home[laravel_crud][pages][description]": {
                required: true,
            },
            "home[laravel_crud][pages][image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "home[laravel_crud][pages][redirect_url]": {
                required: true,
                url: true,
            },

            // Layout Tab Key
            "layouts[title]": {
                required: true,
            },
            "layouts[description]": {
                required: true,
            },
            // Dashboard
            "layouts[dashboard][default][title]": {
                required: true,
            },
            "layouts[dashboard][default][url]": {
                required: true,
                url: true,
            },
            "layouts[dashboard][default][image]": {
                 required: false,
                // extension: "png|jpg|jpeg"
            },

            "layouts[dashboard][ecommerce][title]": {
                required: true,
            },
            "layouts[dashboard][ecommerce][url]": {
                required: true,
                url: true,
            },
            "layouts[dashboard][ecommerce][image]": {
                 required: false,
                // extension: "png|jpg|jpeg"
            },

            "layouts[dashboard][project][title]": {
                required: true,
            },
            "layouts[dashboard][project][url]": {
                required: true,
                url: true,
            },
            "layouts[dashboard][project][image]": {
                 required: false,
                // extension: "png|jpg|jpeg"
            },

            // frameworks
            "layouts[frameworks][title]": {
                required: true,
           },
           "layouts[frameworks][description]": {
                required: true,
            },

            "layouts[frameworks][poster][title]": {
                required: true,
           },
            "layouts[frameworks][poster][image]": {
                 required: false,
                // extension: "png|jpg|jpeg"
            },

            
            "layouts[applications][title]": {
                required: true,
           },
           "layouts[applications][description]": {
                required: true,
            },

            "layouts[applications][poster][image]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "layouts[applications][poster][button_text]": {
                required: true,
           },
           "layouts[applications][poster][url]": {
                required: true,
                url: true,
            },

            // Feature Tab Key
            "feature[laravel_feature][sub_title]": {
                required: true,
           },
           "feature[laravel_feature][title]": {
                required: true,
            },
            "feature[laravel_feature][svg_icon][title]": {
                required: true,
            },
            "feature[laravel_feature][svg_icon][description]": {
                required: true,
            },

            // Footer Tab Key
            "footer[logo]": {
                required: false,
                // extension: "png|jpg|jpeg"
            },
            "footer[description]": {
                required: true,
            },
            "footer[copyright_text]": {
                required: true,
            },
            "footer[left_button_text]": {
                required: true,
            },
            "footer[left_button_url]": {
                required: true,
                url: true,
            },
            "footer[right_button_text]": {
                required: true,
            },
            "footer[right_button_url]": {
                required: true,
                url: true,
            },
            "footer[middle_button_text]": {
                required: true,
            },
            "footer[middle_button_url]": {
                required: true,
                url: true,
            },
        },
        messages: {
            // "header[logo]": {
            //     extension: "Only JPG, JPEG, and PNG files are allowed.",
            // },
            // "header[favicon]": {
            //     extension: "Only JPG, JPEG, and PNG files are allowed.",
            // },
        //     "header[menus][]": {
        //         required: "Please select at least one menu.",
        //     },
        },
        errorPlacement: function (error, element) {
            if (element.attr("name").includes("menus")) {
                error.insertAfter(element.closest(".form-group")); // Place errors below menus
            } else {
                error.insertAfter(element); // Default placement
            }
        },
        onfocusout: function (element) {
            this.element(element);
        },
        onkeyup: function (element) {
            this.element(element);
        },
        submitHandler: function (form) {
            form.submit();
        },
    });
})(jQuery);
