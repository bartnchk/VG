jQuery(document).ready(function(){

    jQuery('#city').selectize({
        maxItems: 1,
        load: function (query, callback) {
            if (!query.length) return callback();
            jQuery.ajax({
                type: 'POST',
                url: window.location.protocol + '//' + window.location.host + '/index.php?option=com_plants&task=plantsettings.getCities',
                data: {query: query},
                success: function (result) {
                    callback(JSON.parse(result));
                }
            });
        }
    });

    var isUserLoggedIn = ( parseInt(jQuery('#user_id').val()) ) ? true : false;

    //load more plant items (+6)
    jQuery('#lookmore').on('click', function(){

        var plantsCounter = jQuery('#plants-counter').val();
        var segment = jQuery('#segment').val();
        var city = jQuery('#city option:selected').val();
        var price = jQuery('input[name="price"]');
        var from = price.data('from');
        var to = price.data('to');

        var data = 'counter=' + plantsCounter;

        if(segment)
            data += '&segment=' + segment;

        if(city)
            data += '&city=' + city;

        data += '&price=' + from + '-' + to;

        jQuery.ajax({
            type: 'POST',
            url: 'index.php?option=com_plants&task=catalog.loadPlants',
            data: data,
            success: function (result) {

                if(result != '0') {

                    var plants = JSON.parse(result);

                    jQuery.each(plants, function( index, value ) {
                        addPlantItem(value);
                    });

                    jQuery('#plants-counter').val(plantsCounter + 6);
                }
                else {
                    jQuery('#lookmore').hide();
                }
            }
        });
    });

    function addPlantItem(item) {

        var html = '<div class="plant-item">' +
                      '<div class="links-wrapper">' +
                         '<a href="/plant?id=' + item.id + '" class="plant-item-upper">';

        if(item.photo)
            html += '<img class="plant-image" src="http://' + location.hostname + '/images/plants/' + item.photo + '" alt="plant image">';
        else
            html += '<img class="plant-image" src="http://' + location.hostname + '/images/plants/cover.png" alt="plant image">';

        html += '</a>';

        if(isUserLoggedIn)
            html += '<a href="/profile?id=' + item.user_id + '" class="author-wrap">';
        else
            html += '<span class="author-wrap">';

        if(item.user_photo)
            html += '<img src="http://' + location.hostname + '/images/user_photos/' + item.user_photo + '" alt="author avatar">';
        else
            html += '<img src="/images/user_photos/user.svg" alt="author avatar">';

        if(item.user_first_name && item.user_last_name)
            html += '<p class="author-name">' + item.user_first_name + ' ' + item.user_last_name + '</p>';
        else
            html += '<p class="author-name">' + item.user_first_name + '</p>';

        if(isUserLoggedIn)
            html += '</a></div>';
        else
            html += '</span></div>';

        html +=
        '<div class="plant-item-bottom">' +
            '<h3 class="plant-title">' +
                '<a href="/plant?id=' + item.id + '">' +
                    item.sort_name +
                '</a>';
            '</h3>';
        if(item.manufactured)
            html += '<p class="plant-location">' + item.manufactured + '</p>';
        html +=
            '<p class="plant-descr">' +
            item.description +
            '</p>' +
            '<ul class="plant-rating">' +
            '<li>' +
            '<span class="rating-title">Germinability</span>' +
            '<div class="rating-value">';

        for(var i = item.germinability; i >= 1; i--) {
            html += '<span class="fa fa-star checked"></span> ';
        }

        for(var i = 5 - item.germinability; i >= 1; i--) {
            html += '<span class="fa fa-star"></span> ';
        }
        html += '</div>' +
            '</li>' +

            '<li>' +
            '<span class="rating-title">Yield</span>' +
            '<div class="rating-value">';

        for(var i = item.yield; i >= 1; i--) {
            html += '<span class="fa fa-star checked"></span> ';
        }

        for(var i = 5 - item.yield; i >= 1; i--) {
            html += '<span class="fa fa-star"></span> ';
        }
        html += '</div>' +
            '</li>' +

            '<li>' +
            '<span class="rating-title">Easy-care</span>' +
            '<div class="rating-value">';

        for(var i = item.easy_care; i >= 1; i--) {
            html += '<span class="fa fa-star checked"></span> ';
        }

        for(var i = 5 - item.easy_care; i >= 1; i--) {
            html += '<span class="fa fa-star"></span> ';
        }
        html += '</div>' +
            '</li>' +

            '<li>' +
            '<span class="rating-title">Author recommends</span>' +
            '<div class="rating-value">';

        for(var i = item.author_recomends; i >= 1; i--) {
            html += '<span class="fa fa-star checked"></span> ';
        }

        for(var i = 5 - item.author_recomends; i >= 1; i--) {
            html += '<span class="fa fa-star"></span> ';
        }
        html += '</div>' +
            '</li>' +
            '</ul>' +
            '</div>' +
            '</div>';

        jQuery('.plants-list:eq(1)').append(html);
    }

    jQuery('#sortby').on('change', function(){
        var sortby = jQuery('#sortby option:selected').val();
        jQuery('#sortby-input').val(sortby);
        jQuery('#filter-form').submit();
    });

    jQuery('#subscription-form').on('submit', function(){

        var email = jQuery('#email1').val();

        if(email)
            subscribe(email);
        else
        {
            alert('Fill the form correctly');
            return false;
        }

        jQuery('#email1').val('');
        return false;
    });

    jQuery('#clean').on('click', function(){
        jQuery( location ).attr("href", '/catalog');
        return false;
    });
});