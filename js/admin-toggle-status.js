jQuery(document).ready(function ($) {
    $(".toggle-status").on("click", function () {
        var postId = $(this).data("post-id");
        var $element = $(this);

        $.ajax({
            type: "POST",
            url: ajax_object.ajax_url,
            data: {
                action: "toggle_post_status",
                post_id: postId
            },
            beforeSend: function () {
                $element.text("Обновление...");
            },
            success: function (response) {
                if (response.success) {
                    $element.text(response.data.status_label);
                } else {
                    alert("Ошибка: " + response.data.message);
                }
            },
            error: function () {
                alert("Ошибка при выполнении запроса.");
            }
        });
    });
});
