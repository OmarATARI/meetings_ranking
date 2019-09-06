$('#star1').starrr({
    change: function(e, value){
        if (value) {
            $('.your-choice-was').show();
            $('.choice').text(value);
        } else {
            $('.your-choice-was').hide();
        }
    }
});
var s2input = $('#star2');
$('#star2').starrr({
    max: 10,
    rating: s2input.val(),
    change: function(e, value){
        s2input.val(value).trigger('input');
    }
});


