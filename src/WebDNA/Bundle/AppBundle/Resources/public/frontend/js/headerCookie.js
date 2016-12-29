var headerCookie = {
    cookie_name: 'cookie_approval',
    isset: function() {
        return document.cookie.indexOf(headerCookie.cookie_name)!==-1;
    },
    set: function() {
        document.cookie = headerCookie.cookie_name + '=1';
        $('#header_cookies').remove();
    },
    show: function() {
        $('#header_cookies').show();
    }
};

$(document).ready(function() {
    if (headerCookie.isset() === false) {
        headerCookie.show();
    }
});