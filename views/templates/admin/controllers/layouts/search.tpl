<script>
    $(document).ready(function() {
        var $d        = $(this),
            date_from = $('#date_from').attr('value'),
            date_to   = $('#date_to').attr('value');

        function searchOrders() {
            var query         = $('#orders_search').attr('value'),
                date_from     = $('#date_from').attr('value'),
                date_to       = $('#date_to').attr('value'),
                ajax_settings = {
                    data    : {
                        action    : 'searchOrders',
                        word      : query,
                        date_from : date_from,
                        date_to   : date_to
                    },
                    success : function(msg) {
                        if (msg.status) {
                            if (msg.content != '') {
                                $('.no-results').addClass('hidden');
                                $('.tab-pane.active .navbar-nav').removeClass('hidden').html(msg.content);
                            } else {
                                $('.tab-pane.active .navbar-nav').addClass('hidden');
                                $('.no-results').removeClass('hidden');
                            }
                        }
                    }
                },
                ajax          = new jxoco.ajax();
            ajax.init(ajax_settings);
        }

        $('#date_from').die('change').live('change', function() {
            if (date_from != $(this).attr('value')) {
                date_from = $(this).attr('value')
                searchOrders();
            }
        });
        $('#date_to').die('change').live('change', function() {
            if (date_to != $(this).attr('value')) {
                date_to = $(this).attr('value');
                searchOrders();
            }
        });
        $('#orders_search').die('keyup').live('keyup', function() {
            searchOrders();
        });
        $(".datepicker").datetimepicker({
            prevText   : '',
            nextText   : '',
            dateFormat : 'yy-mm-dd',
            // Define a custom regional settings in order to use PrestaShop translation tools
            ampm       : false,
            amNames    : ['AM', 'A'],
            pmNames    : ['PM', 'P'],
            timeFormat : 'hh:mm:ss tt',
            timeSuffix : ''
        });
    });
</script>