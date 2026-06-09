(function ($) {
    "use strict";

    $.validator.addMethod("validContent", function (value, element) {
        return $.trim($(element).val().replace(/(<([^>]+)>)/gi, "")) !== "";
    }, "This field is required.");

    $('#userForm, #editUserForm').each(function () {
        $(this).validate({
            rules: {
                name: {
                    required: true
                },
                role_id: {
                    required: true
                },
                gender: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                confirm_email: {
                    required: true,
                    email: true,
                    equalTo: "#email"
                },
                phone: {
                    required: true,
                    minlength: 6,
                    maxlength: 15
                },
                status: {
                    required: true
                },
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                dob: {
                    date: true
                },
                password: {
                    required: $(this).attr("id") === "userForm", // Checks if form is userForm
                    minlength: 8
                },
                confirm_password: {
                    required: $(this).attr("id") === "userForm",
                    minlength: 8,
                    equalTo: "#password"
                }
            }
        });
    });

    $('#roleForm').validate({
        rules: {
            name: {
                required: true
            },
            'permissions[]': {
                required: true
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") === "permissions[]") {
                error.insertAfter(element.closest('.mb-3').find('.validation'));
            } else {
                error.insertAfter(element);
            }
        }
    });

    $('#pageForm').validate({
        ignore: [],
        rules: {
            title: {
                required: true
            },
            content: {
                required: true,
                validContent: true
            },
            meta_title: {
                required: true
            },
            status: {
                required: true
            },
        }
    });
    $('#categoryForm').validate({
        rules: {
            name: {
                required: true,
            },
            meta_title: {
                required: true,
            },
            status: {
                required: true
            },
        }
    });
    $('#tagForm').validate({
        rules: {
            name: {
                required: true,
            },
            status: {
                required: true
            },
        }
    });
    $('#editBlogForm').validate({
        ignore: [],
        rules: {
            title: {
                required: true,
            },
            description: {
                minlength: 10
            },
            content: {
                required: true,
                validContent: true
            },
            meta_title: {
                required: true,
            },
            status: {
                required: true
            },
        }
    });

    $('#createBlogForm').validate({
        ignore: [],
        rules: {
            title: {
                required: true,
            },
            description: {
                minlength: 10
            },
            content: {
                required: true,
                validContent: true
            },
            meta_title: {
                required: true,
            },
            status: {
                required: true
            },
            thumbnail: {
                required: true
            }
        }
    });

    $('#loginForm').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            }
        }
    });

    $('#registerForm').validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            }
        }
    });

    $('#resetPasswordLinkForm').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
        }
    });

    $('#resetPasswordFrom').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            }
        }
    });

    $('#editProfile').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            phone: {
                required: true,
                minlength: 6,
                maxlength: 15
            },
            bio: {
                required: true,
                maxlength: 255
            }
        }
    });

    $('#updatePassword').validate({
        rules: {
            current_password: {
                required: true,
            },
            new_password: {
                required: true,
                minlength: 8,
            },
            confirm_password: {
                required: true,
                equalTo: "#new_password",
                minlength: 8,
            }
        }
    });
})(jQuery);