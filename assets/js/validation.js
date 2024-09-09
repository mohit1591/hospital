$(document).on('keypress', '.alpha', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[a-zA-Z\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});

$(document).on('keypress', '.numeric', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[0-9\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});


$(document).on('keypress', '.price_float', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[0-9\.\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});

$(document).on('keypress', '.alpha_space', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[a-zA-Z \b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});

$(document).on('keypress', '.alpha_space_name', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[a-zA-Z,. \b]+$"); 
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});
$(document).on('keypress', '.alpha_numeric_space', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[a-zA-Z0-9 \b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});

$(document).on('keypress', '.alpha_numeric', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[a-zA-Z0-9\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});

$(document).on('keypress', '.alpha_numeric_slash', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[a-zA-Z0-9/\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});

$(document).on('keypress', '.numeric_slash', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[0-9/\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});

$(document).on('keypress', '.username', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[a-zA-Z0-9-_\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});

$(document).on('keypress', '.address', function (event) {
    /*var code = event.keyCode || event.which;
    var regex = new RegExp("^[a-zA-Z0-9-_:, \b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }*/
});


$(document).on('keypress', '.email_address', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[a-zA-Z0-9-_@.\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});


$(document).on('keypress', '.alpha_numeric_hyphen', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[a-zA-Z0-9-/\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});


$(document).on('keypress', '.unique_ids', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[a-zA-Z0-9{}/\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});

$(document).on('batch_no_input', '.unique_ids', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^\w+([\s-_]\w+)*$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});


$(document).on('keypress', '.landline', function (event) {
    var code = event.keyCode || event.which;
    var regex = new RegExp("^[0-9-\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) && code!=9 && event.which!=0 && event.which!=8) {
        event.preventDefault();
        return false;
    }
});