var feedbackSubmitting = false;

var $feedbackButton = $('#feedback-form button[type=submit]'),
    $feedbackSpin = $('#feedback-spin');

$feedbackButton.on('mousedown', function() {
    $feedbackSpin.removeClass('hidden');
});

$('#feedback-button').on('click', function(el) {
    $(el.currentTarget).addClass('hidden');
    $('#feedback-form-container, #feedback-form, .feedback-header, .feedback-form').removeClass('hidden');
});

$('body, .feedback-close').on('click', function() {
    if(feedbackSubmitting === true) {
        return false;
    }
    $("#feedback-form-container").addClass('hidden');
    $('#feedback-button').removeClass('hidden');
    $('.feedback-success').addClass('hidden');
    $('.feedback-header').removeClass('hidden');
});

$("#feedback-form-container,#feedback-button").click(function(e){
    e.stopPropagation();
});

$('#feedback-message').on('keyup', function (el) {
    var $submit = $feedbackButton;
    if ($(el.target).val().length > 2) {
        $submit.removeAttr('disabled');
    } else {
        $submit.attr('disabled', true);
    }
});

$('#feedback-form').on('submit', function (el) {
    feedbackSubmitting = true;
    $feedbackButton.attr('disabled', true);
    $.post($(el.target).attr('action'), {message: $('#feedback-message').val()}, function () {
    }).done(function(data) {
        if (data.sent === true) {
            feedbackSubmitting = false;
            $('.feedback-error, .feedback-header, #feedback-form').addClass('hidden');
            $('.feedback-success').removeClass('hidden');
        } else {
            $('.feedback-error').removeClass('hidden');
        }
        $feedbackButton.removeAttr('disabled');
        $feedbackSpin.addClass('hidden');
    });;

    return false;
});
