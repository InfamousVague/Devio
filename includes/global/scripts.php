    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="assets/twitter_bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/scripts/typeahead.bundle.js"></script>
    <script src="assets/scripts/typeahead.js"></script>
    
    <script type="text/javascript">
        // User Dropdown
        $(document).ready(function(){
            $('#user_info_dropdown').toggle();
            $('#user_info').click(function(){
                if ($('#user_info_dropdown').attr('status') === 'logged_in'){
                    $('#user_info_dropdown').toggle();
                }
            });
        });
        //Login Script
        $(function () {
            $('.button-checkbox').each(function () {
        
                // Settings
                var $widget = $(this),
                    $button = $widget.find('button'),
                    $checkbox = $widget.find('input:checkbox'),
                    color = $button.data('color'),
                    settings = {
                        on: {
                            icon: 'glyphicon glyphicon-check'
                        },
                        off: {
                            icon: 'glyphicon glyphicon-unchecked'
                        }
                    };
        
                // Event Handlers
                $button.on('click', function () {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                    $checkbox.triggerHandler('change');
                    updateDisplay();
                });
                $checkbox.on('change', function () {
                    updateDisplay();
                });
        
                // Actions
                function updateDisplay() {
                    var isChecked = $checkbox.is(':checked');
        
                    // Set the button's state
                    $button.data('state', (isChecked) ? "on" : "off");
        
                    // Set the button's icon
                    $button.find('.state-icon')
                        .removeClass()
                        .addClass('state-icon ' + settings[$button.data('state')].icon);
        
                    // Update the button's color
                    if (isChecked) {
                        $button
                            .removeClass('btn-default')
                            .addClass('btn-' + color + ' active');
                    }
                    else {
                        $button
                            .removeClass('btn-' + color + ' active')
                            .addClass('btn-default');
                    }
                }
        
                // Initialization
                function init() {
        
                    updateDisplay();
        
                    // Inject the icon if applicable
                    if ($button.find('.state-icon').length == 0) {
                        $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
                    }
                }
                init();
            });
        });
    </script>