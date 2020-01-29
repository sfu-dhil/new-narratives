(function ($, window) {

    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: typeahead_path + "?q=%QUERY",
            wildcard: '%QUERY'
        }
    });

    var opts = {
        highlight: true,
        classNames: {
            highlight: 'tt-match',
            menu: 'list-group',
            dataset: 'list-group-item',
            suggestion: 'list-group-item',
            cursor: 'list-group-item-info',
        }
    };
    var sets = {
        display: 'full_name',
        source: bloodhound,
        templates: {
            empty: function (query) {
                return '<div class="empty-message">Cannot find a match for <i>' + query.query + '</i>.</div>';
            },
            pending: function (query) {
                return '<div class="pending-message">Searching for matches for <i>' + query.query + '</i>.</div>';
            },
            header: function (query) {
                return '<div class="tt-header">Found ' + query.suggestions.length + ' choices for <i>' + query.query + '</i></div>';
            },
            suggestion: function (suggestion) {
                //console.log(suggestion);
                return '<div>' + suggestion.full_name + '</div>';
            }

        }
    };

    function initTypeahead($item) {
        $item.typeahead(opts, sets);
        $item.on('typeahead:select', function (event, suggestion) {
            $(this).parents().eq(4).find('.contribution_person').val(suggestion.id);
        });
    }

    $(document).ready(function () {
        $('input.typeahead').each(function (index, item) {
            var $item = $(item);
            initTypeahead($item);
        });
    });

    $(document).on('collection:add', function (event, item) {
        console.log(item);
        initTypeahead($(item).find('input.typeahead'));
    });


})(jQuery, window);
