(function($) {
    console.log('my-text-button.js loaded');
    $(document).on('fl-builder-settings-init', function(event, panel) {
        panel.addButton('my-text-button', {
            title: 'My Text Button',
            icon: 'fa fa-plus',
            onclick: function() {
                // Add your button functionality here
                alert('Button clicked!');
            }
        });
    });
})(window.jQuery);