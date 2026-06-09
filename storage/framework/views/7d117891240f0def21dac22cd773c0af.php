<!-- latest jquery-->
<script src="<?php echo e(asset('assets/js/jquery.min.js')); ?>"></script>
<!-- Bootstrap js-->
<script src="<?php echo e(asset('assets/js/bootstrap/bootstrap.bundle.min.js')); ?>"></script>
<!-- feather icon js-->
<script src="<?php echo e(asset('assets/js/icons/feather-icon/feather.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/icons/feather-icon/feather-icon.js')); ?>"></script>
<!-- scrollbar js-->
<script src="<?php echo e(asset('assets/js/scrollbar/simplebar.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/scrollbar/custom.js')); ?>"></script>
<!-- Sidebar jquery-->
<script src="<?php echo e(asset('assets/js/config.js')); ?>"></script>
<!-- Plugins JS start-->
<script src="<?php echo e(asset('assets/js/sidebar-menu.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sidebar-pin.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/slick/slick.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/slick/slick.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/header-slick.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<!-- movable category menu -->
<script src="<?php echo e(asset('assets/js/nestable/jquery.nestable.min.js')); ?>"></script>
<!-- validation -->
<script src="<?php echo e(asset('assets/js/jquery.validate.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/script.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/theme-customizer/customizer.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/toastr.min.js')); ?>"></script>

<!-- Editor js -->

<script src="<?php echo e(asset('assets/js/tinymce/jquery.tinymce.min.js')); ?>" async></script>
<script src="<?php echo e(asset('assets/js/tinymce/tinymce.min.js')); ?>" async></script>

<?php echo $__env->yieldContent('scripts'); ?>
<!-- Plugins JS Ends-->
<!-- Theme js-->
<script>
    $(document).ready(function() {
        $(document).on('change', '.toggle-status', function() {

            let status = $(this).prop('checked') ? 1 : 0;
            let url = $(this).data('route');
            let clickedToggle = $(this);
            $.ajax({
                type: "PUT",
                url: url,
                data: {
                    status: status,
                    _token: '<?php echo e(csrf_token()); ?>',
                },
                success: function(data) {
                    clickedToggle.prop('checked', status);
                    toastr.success("Status Updated Successfully");
                },
                error: function(xhr, status, error) {
                    console.log(error)
                }
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInputs = document.querySelectorAll("#sidebar-search-input, #search-input");
        const demoLinks = document.querySelectorAll(".menu-link");

        // You can target one or both result containers if needed
        const resultsContainers = document.querySelectorAll("#search-results, #results");

        function handleSearch(event) {
            let filter = event.target.value.toLowerCase();

            resultsContainers.forEach(container => {
                container.innerHTML = "";
            });

            if (filter !== "") {
                resultsContainers.forEach(container => {
                    $(container).removeClass("d-none").addClass("d-block");
                });

                let hasMatches = false;
                let resultsList = document.createElement("ul");
                resultsList.classList.add("list-group");

                demoLinks.forEach((link) => {
                    let linkText = link.textContent.toLowerCase();
                    if (linkText.includes(filter)) {
                        let listItem = document.createElement("li");
                        listItem.classList.add("list-group-item");

                        let anchorTag = document.createElement("a");
                        anchorTag.href = link.href;
                        anchorTag.textContent = link.textContent;
                        anchorTag.classList.add("dropdown-item");

                        listItem.appendChild(anchorTag);
                        resultsList.appendChild(listItem);
                        hasMatches = true;
                    }
                });

                resultsContainers.forEach(container => {
                    if (hasMatches) {
                        container.appendChild(resultsList.cloneNode(true));
                    } else {
                        container.innerHTML = '<div>No result found.</div>';
                    }
                });

            } else {
                resultsContainers.forEach(container => {
                    $(container).removeClass("d-block").addClass("d-none");
                });
            }
        }

        searchInputs.forEach((input) => {
            input.addEventListener("keyup", handleSearch);
        });
    });
</script>
<?php /**PATH /Users/dimitar/Desktop/Clients/FreelanceProjects/Croatia-Admin/themeforest-XMp4qKjR-riho-laravel-admin-template/riho-laravel-1.0.1/resources/views/layouts/simple/script.blade.php ENDPATH**/ ?>