function statepin() { //change the body according to user selected
    if (document.getElementById('yesCheck').checked) {
        document.getElementById('searchState').style.display = 'block';
        $("#slotlist").empty()
    }
    else document.getElementById('searchState').style.display = 'none';
    if (document.getElementById('noCheck').checked) {
        document.getElementById('searchpin').style.display = 'block';
        $("#slotlist").empty()
    }
    else document.getElementById('searchpin').style.display = 'none';
}
//Get the button
var mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () { scrollFunction() };

function scrollFunction() {
    if (document.body.scrollTop > 30 || document.documentElement.scrollTop > 30) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    $("html,body").animate({
        scrollTop: $("body").offset().top
    }, 1000);
}
function validateForm() { //validate pincode
    var x = document.getElementById("pincode").value.length;
    if (x < 6) {
        return false;
    }
}
function validateForm2() { //validate city name is choosen
    var x = document.getElementById("dist").value;
    if (x == "") {
        return false;
    }
}
$('#pinsearch').click(function () { //show result using pincode
    if (validateForm() == false) {
        $('#slotlist').html("<div class='error'>Please Enter Correct Pincode.</div>");
    } else {
        $(document).on('click', '#pinsearch', function () {
            var pincode = document.getElementById("pincode").value;
            var date = document.getElementById("pdate").value;
            var newdate = changeDateFormatTo(date);
            var removeIcon = $('#pinsearch').find('i');
            removeIcon.addClass('fa fa-refresh fa-spin');
                $.ajax({
                    type: 'POST',
                    url: 'includes/pin.php',
                    data: { pin: pincode, date: newdate },
                    success: function (html) {
                        removeIcon.removeClass('fa fa-refresh fa-spin');
                        $("#slotlist").empty().append(html);
                    }
                });
        });
    }
});
$(document).on('click', '.nextpin', function () { //get next day result using pincode
    var pincode = document.getElementById("pincode").value;
    var ndate = $(".nextpin").attr("ID");
    if(ndate == ""){
        $('#slotlist').html("<div class='error'>Something went error, Try Again.</div>");
    }
    var nextdate = nextDate(ndate);
    var removeIcon = $('.nextpin').find('i');
    removeIcon.addClass('fa fa-refresh fa-spin');
        $.ajax({
            type: 'POST',
            url: 'includes/pin.php',
            data: { pin: pincode, date: nextdate },
            success: function (html) {
                removeIcon.removeClass('fa fa-refresh fa-spin');
                $("#slotlist").empty().append(html);
                $("html,body").animate({
                    scrollTop: $("#slotlist").offset().top
                }, 1000);
            }
    });
});
$('#stsearch').click(function () { //show result using state and city name
    if (validateForm2() == false) {
        $('#slotlist').html("<div class='error'>Please Choose Your City.</div>");
    } else {
        $(document).on('click', '#stsearch', function () {
            var dist = document.getElementById("dist").value;
            var date = document.getElementById("date").value;
            var newdate = changeDateFormatTo(date);
            var removeIcon = $('#stsearch').find('i');
            removeIcon.addClass('fa fa-refresh fa-spin');
                $.ajax({
                    type: 'POST',
                    url: 'includes/state.php',
                    data: { dist: dist, date: newdate },
                    success: function (html) {
                        removeIcon.removeClass('fa fa-refresh fa-spin');
                        $("#slotlist").empty().append(html);
                    }
                });
        });
    }
});
$(document).on('click', '.nextst', function () { //get next day result using state and city name
    var dist = document.getElementById("dist").value;
    var ndate = $(".nextst").attr("ID");
    if(ndate == ""){
        $('#slotlist').html("<div class='error'>Something went error, Try Again.</div>");
    }
    var nextdate = nextDate(ndate);
    var removeIcon = $('.nextst').find('i');
    removeIcon.addClass('fa fa-refresh fa-spin');
        $.ajax({
            type: 'POST',
            url: 'includes/state.php',
            data: { dist: dist, date: nextdate },
            success: function (html) {
                removeIcon.removeClass('fa fa-refresh fa-spin');
                $("#slotlist").empty().append(html);
                $("html,body").animate({
                    scrollTop: $("#slotlist").offset().top
                }, 1000);
            }
    });
});
$(document).on('click', '#show-all', function () { //filter for show all
    $('#show-all').addClass('clicked');
    $('#18plus').removeClass('clicked');
    $('#45plus').removeClass('clicked');
    $('.45').show();
    $('.18').show();
    $('.message').hide();;
});
$(document).on('click', '#18plus', function () { //filter for 18+
    $('#18plus').addClass('clicked');
    $('#show-all').removeClass('clicked');
    $('#45plus').removeClass('clicked');
    if($('.18').length >= 1) {
        $('.45').hide();
        $('.18').show();
    }else{
        $('.message').html('<h4>Sorry no slots available for 18+.</h4>');
        $('.message').show();
        $('.45').hide();
        // $('.18').show();
    }
});
$(document).on('click', '#45plus', function () { //filter for 45+
    $('#45plus').addClass('clicked');
    $('#show-all').removeClass('clicked');
    $('#18plus').removeClass('clicked');
    $('.45').show();
    $('.18').hide();
    $('.message').hide();
});
function nextDate(s) { //add +1 to date
    let d = new Date(s);
    d.setUTCDate(d.getUTCDate() + 1);
    var date = d.toISOString().substr(0, 10);
    const [yy, mm, dd] = date.split(/-/g);
    return `${dd}-${mm}-${yy}`;
}
const changeDateFormatTo = date => { //change date format to dd-mm-yyyy
    const [yy, mm, dd] = date.split(/-/g);
    return `${dd}-${mm}-${yy}`;
};
