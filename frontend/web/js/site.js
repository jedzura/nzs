$(function()
{
    var htmlHandler = $('html');

    htmlHandler.on('change', '#group-city_id', function()
    {
        var that = $(this);
        if (that.val() !== '')
        {
            $.ajax({
                type     :'post',
                cache    : false,
                url  : 'site/get-universities',
                data : {city_id : that.val()},
                success  : function(response) {
                    response = JSON.parse(response);
                    var options = '';
                    $.each(response, function(key, value)
                    {
                        options += '<option value="' + key + '">' + value + '</option>';
                    });
                    $('#group-university_id').html('<option value=""></option>' + options);
                }
            });
        } else {
            $('#group-university_id').html('<option value=""></option>');
        }
    });
});