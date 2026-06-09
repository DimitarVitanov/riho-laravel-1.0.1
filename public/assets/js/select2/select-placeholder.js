setTimeout(function () {
    (function ($) {
        "use strict";
        // With Placeholder - role
        $(".role-placeholder").select2({
            placeholder: "Select Role",
        });

        // With Placeholder - status
        $(".status-placeholder").select2({
            placeholder: "Select Status",
        });

        // With Placeholder - country
        $(".country-placeholder").select2({
            placeholder: "Select Country",
        });

        // With Placeholder - gender
        $(".gender-placeholder").select2({
            placeholder: "Select Gender",
        });

        // With Placeholder - state
        $(".state-placeholder").select2({
            placeholder: "Select State",
        });

        // With Placeholder - parent-category
        $(".parent-category-placeholder").select2({
            placeholder: "Select Parent",
        });
    })(jQuery);
}, 350);
