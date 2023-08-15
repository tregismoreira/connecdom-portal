(function($, Drupal) {
  Drupal.behaviors.connecdom = {
    attach: function(context, settings) {
      $('#drupal-modal').on('hidden.bs.modal', function() {
        window.location.reload();
      });

      $('.view table').basictable({ breakpoint: 992 });
    },
  };
})(jQuery, Drupal);
