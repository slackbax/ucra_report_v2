$(document).ready(function () {
  Noty.overrideDefaults({
    theme: 'sunset',
    timeout: 3000,
    killer: true,
    closeWith: ['click']
  })
  $('[data-toggle="tooltip"]').tooltip({html:true})
  moment.locale('es')
  $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
    widgetPositioning: {
      horizontal: 'right',
      vertical: 'top'
    },
    locale: 'es'
  })
  $('.number').keyup(function () {
    const val = $(this).val()
    $(this).val(val.replace(/[^\d.]+/g, ''))
  })
})