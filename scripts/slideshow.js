$(document).ready(function() {
    function rotate() {
        var $slides = $('.w3-half.w3-padding.w3-center .w3-card-4 .my_absolute');
        $slides.last().fadeOut(3000, function() {
            $(this).insertBefore($slides.first()).show();
        });
    };
    setInterval(rotate, 3000);
});
