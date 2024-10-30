jQuery(document).ready(function ($) {

    var formatRepo = function (repo) {
        if (repo.loading)
            return repo.text;

        var markup = '';
        markup += '<div class="select2-result-repository clearfix>';
        markup += '<div class="select2-result-repository__meta>';
        markup += '<div class="select2-result-repository__title">' + repo.first_name + ' ' + repo.last_name + '</div>';
        markup += '</div>';
        markup += '</div>';

        return markup;
    };

    var formatRepoSelection = function (repo) {
        if (repo.hasOwnProperty('first_name') && repo.hasOwnProperty('last_name'))
            return repo.first_name + ' ' + repo.last_name;
        else
            return repo.text;
    };

    $("form#zleadscrm_leads_search_form").submit(function () {
        $('div.zleadscrm_loading_screen').css('display', 'flex');
        $('div.zleadscrm_loading_screen').css('align-items', 'baseline');
    });

    $('#zleadscrm_brands_select').select2({
        placeholder: 'Select brands...',
        width: '90%'
    });

    $('#zleadscrm_copy_shipping_from_billing').click(function () {
        $('#zleadscrm_shipping_first_name').val($('#zleadscrm_billing_first_name').val());
        $('#zleadscrm_shipping_last_name').val($('#zleadscrm_billing_last_name').val());
        $('#zleadscrm_shipping_company').val($('#zleadscrm_billing_company').val());
        $('#zleadscrm_shipping_address_1').val($('#zleadscrm_billing_address_1').val());
        $('#zleadscrm_shipping_address_2').val($('#zleadscrm_billing_address_2').val());
        $('#zleadscrm_shipping_city').val($('#zleadscrm_billing_city').val());
        $('#zleadscrm_shipping_postcode').val($('#zleadscrm_billing_postcode').val());
        $('#zleadscrm_shipping_state').val($('#zleadscrm_billing_state').val());
        $('#zleadscrm_shipping_country').val($('#zleadscrm_billing_country').val());
    });


    $('body').on('change', 'select#zleadscrm_account_manager_filter', function () {
        var manager_id = $(this).val();
        var link = $(this).attr("data-link");

        if (manager_id === 0)
            window.location.href = link;
        else
            window.location.href = link + '&manager_filter=' + manager_id;
    });

    $('body').on('change', 'select#zleadscrm_leads_sort_by', function () {
        var sort_by = $(this).val();
        var link = $(this).attr("data-link");

        if (sort_by === 'last_name')
            window.location.href = link;
        else
            window.location.href = link + '&sort_by=' + sort_by;
    });

    $('div.acm_lead_notification .notice-dismiss').click(function () {
        let parent = $(this).parent();

        let ajax_url = parent.attr("data-link");
        let user = parent.attr("data-user");
        let redirect = parent.attr("data-redirect");

        $.post({
            url: ajax_url,
            data: {
                'action': 'remove_lead_notification',
                'lead_id': user
            },
            success: function () {
                window.location.href = redirect;
            }
        })
    });

    $('a.acm_lead_notification_link').click(function () {
        let ajax_url = $(this).attr("data-link");
        let user = $(this).attr("data-user");
        let redirect = $(this).attr("data-redirect");

        $.post({
            url: ajax_url,
            data: {
                'action': 'remove_lead_notification',
                'lead_id': user
            },
            success: function () {
                window.location.href = redirect;
            }
        })
    });


    $('body').on('change', 'div.audit_paging input#current-page-selector', function () {
        var paged = $(this)[0].value;

        window.location.href = window.location.href + '&paged=' + paged;
    });

    $('body').on('change', 'div.lead_paging input#current-page-selector', function () {
        var paged = $(this)[0].value;

        window.location.href = window.location.href + '&paged=' + paged;
    });


    $('.zleadscrm_zacctmgr_notice button.notice-dismiss').click(function () {
        let ajax_url = $('.zleadscrm_zacctmgr_notice').attr('data-link');

        $.post({
            url: ajax_url,
            data: {
                'action': 'zacctmgr_notice_dismissed'
            },
            success: function () {
            }
        })
    });

    $('.zleadscrm_wc_notice button.notice-dismiss').click(function () {
        let ajax_url = $('.zleadscrm_wc_notice').attr('data-link');

        $.post({
            url: ajax_url,
            data: {
                'action': 'zleadscrm_wc_notice_dismissed'
            },
            success: function () {
            }
        })
    });


    $('a#zleadscrm_lead_change_archive_status').click(function () {
        let ajax_url = $(this).attr("data-link");
        let lead = $(this).attr("data-lead");

        $('div.zleadscrm_loading_screen').css('display', 'flex');
        $('div.zleadscrm_loading_screen').css('align-items', 'center');

        $.post({
            url: ajax_url,
            data: {
                'action': 'change_lead_archive_status',
                'lead_id': lead
            },
            success: function (result) {
                window.location.href = window.location.href + '&upd=1';
            }
        })
    });

    if ($('#lead_updated_notice')[0] !== undefined) {
        $('.zleadscrm_lead_fields').on('click', function (e) {
            $('#lead_updated_notice').hide();
            let link = window.location.href.replace(window.location.search, '?' + insertParam('upd', '0'));
            window.history.pushState({}, document.title, link);
        });
    }
});

function insertParam(key, value) {
    key = encodeURI(key);
    value = encodeURI(value);

    var kvp = document.location.search.substr(1).split('&');

    var i = kvp.length;
    var x;
    while (i--) {
        x = kvp[i].split('=');

        if (x[0] == key) {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }

    if (i < 0) {
        kvp[kvp.length] = [key, value].join('=');
    }

    //this will reload the page, it's likely better to store this until finished
    return kvp.join('&');
}