$(function () {

    $('.chat_message_messageFile_file').on('click', function (){
        $('.js-upload-file').trigger('click');
    })

    $(".custom-file-input").on("change", function () {

        let fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);

        let i = $(this).prev('label').clone();
        let file = $(".custom-file-input")[0].files[0].name;
        let label = $(".label");
        label.text(file);
    });

    if ($('#message-container').length) {
        let messageContainer = document.getElementById('message-container');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
});
