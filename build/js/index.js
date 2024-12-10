$(document).ready(function () {
  const $showLoad = $('#option_load'), $showManual = $('#option_manual'),
    $uploadFile = $('#upload_file'), $manualData = $('#manual_data'),
    $formLoad = $('#form-load'), $data = $('#patdata_list'), $down = $('#down_link'), $reload = $('#restart_link'),
    $loader = $('#loader'), $loader2 = $('#loader2'), $loader3 = $('#loader3'),
    $btnLoad = $('#btn-load'), $btnMap = $('#btn-map'),
    $formMap = $('#form-mapping'), $folder = $('#folder'),
    $dateSel = $('#date'), $pulseSel = $('#pulse'), $systolicSel = $('#systolic'), $diastolicSel = $('#diastolic'),
    $ghoraInicio = $('#ghora-inicio'), $horaInicio = $('#hora-inicio'),
    $columnMap = $('#column_map'), $result = $('#result_div'),
    pressureChart = document.getElementById('pressureChart'), heartChart = document.getElementById('heartChart'),
    $formManual = $('#form-manual'), $mdata = $('#mdata'),
    $gdate = $('#gdate'), $mdate = $('#mdate'), $mpulse = $('#mpulse'), $msystolic = $('#msystolic'),
    $mdiastolic = $('#mdiastolic'), $addData = $('#add_data'), $btnManual = $('#btn_manual'),
    $mtable = $('#mtable').DataTable({
      buttons: [],
      paging: false,
      lengthChange: false,
      searching: false,
      ordering: false,
      info: false,
      language: {url: 'dist/js/dataTable.spanish.json'}
    }),
    optionsLoad = {
      url: 'ajax/process-csv.php',
      type: 'post',
      dataType: 'json',
      beforeSubmit: validateFormLoad,
      success: showResponseLoad
    },
    optionsMap = {
      url: 'ajax/load-data.php',
      type: 'post',
      dataType: 'json',
      beforeSubmit: validateFormMap,
      success: showResponseMap
    },
    optionsManualData = {
      url: 'ajax/load-manual-data.php',
      type: 'post',
      dataType: 'json',
      beforeSubmit: validateManualData,
      success: showResponseManualData
    }

  $('#patdata').MultiFile({
    max: 1,
    accept: 'csv',
    STRING: {
      selected: 'Seleccionado: $file',
      denied: '¡Archivo de tipo .$ext no permitido! Inténtalo nuevamente.'
    }
  })

  $gdate.datetimepicker({
    format: 'DD/MM/YYYY',
    maxDate: moment()
  })

  $ghoraInicio.datetimepicker({
    format: 'LT'
  })

  let pChart = '', hChart = '', data = []

  function graphData(s) {
    if (pChart !== '') pChart.destroy()
    if (hChart !== '') hChart.destroy()

    pChart = new Chart(pressureChart, {
      type: 'line',
      data: {
        labels: s.dates.data,
        datasets: [{
          label: 'Sistólica',
          data: s.systolic.data,
          borderWidth: 1,
          borderColor: '#DC3545',
          backgroundColor: '#DC3545'
        }, {
          label: 'Presión Media',
          data: s.avg,
          borderWidth: 2,
          borderColor: '#ffffff',
          backgroundColor: '#666666'
        }, {
          label: 'Diastólica',
          data: s.diastolic.data,
          borderWidth: 1,
          borderColor: '#325285',
          backgroundColor: '#325285',
          fill: {
            target: '-2',
            below: 'rgba(183, 183, 183, .5)'
          }
        }]
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: 'Evolución de Presión Arterial'
          },
          annotation: {
            annotations: {
              systolic: {
                type: 'line',
                mode: 'horizontal',
                yMin: 140,
                yMax: 140,
                borderColor: '#DC3545',
                borderWidth: 1,
                borderDash: [5, 5]
              },
              diastolic: {
                type: 'line',
                mode: 'horizontal',
                yMin: 90,
                yMax: 90,
                borderColor: '#325285',
                borderWidth: 1,
                borderDash: [5, 5]
              }
            }
          },
        },
        scales: {
          y: {
            max: Math.ceil((s.systolic.max + 20) / 10) * 10,
            min: Math.ceil((s.diastolic.min - 30) / 10) * 10,
            beginAtZero: false,
            title: {
              display: true,
              text: 'mmHg'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Momento de medición'
            }
          }
        }
      }
    });

    hChart = new Chart(heartChart, {
      type: 'line',
      data: {
        labels: s.dates.data,
        datasets: [{
          label: 'Latidos por minuto',
          data: s.pulse.data,
          borderWidth: 1,
          borderColor: '#59A09B',
          backgroundColor: '#59A09B'
        }]
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: 'Evolución de Frecuencia Cardíaca'
          }
        },
        scales: {
          y: {
            max: Math.ceil((s.pulse.max + 10) / 10) * 10,
            min: Math.ceil((s.pulse.min - 10) / 10) * 10,
            beginAtZero: false,
            title: {
              display: true,
              text: 'BPM'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Momento de medición'
            }
          }
        }
      }
    });
  }

  function validateFormLoad() {
    $loader.css('display', 'block')
    $dateSel.empty().append('<option value="">Selecciona columna</option>')
    $pulseSel.empty().append('<option value="">Selecciona columna</option>')
    $systolicSel.empty().append('<option value="">Selecciona columna</option>')
    $diastolicSel.empty().append('<option value="">Selecciona columna</option>')

    let count = 0
    $('.MultiFile-applied').each(function () {
      count++
    })

    if (count === 1) {
      new Noty({
        text: '<strong>¡Error!</strong><br>Debes elegir un archivo para analizar los datos.',
        type: 'error'
      }).show()

      $loader.css('display', 'none')
      return false
    } else {
      return true
    }
  }

  function showResponseLoad(r) {
    $loader.css('display', 'none')
    $btnLoad.click()
    if (r.type) {
      new Noty({
        text: '<strong>¡Éxito!</strong><br>El archivo ha sido evaluado correctamente.',
        type: 'success'
      }).show()

      $('input:file').MultiFile('reset')
      $columnMap.css('display', 'block')
      r.headers.forEach(function (h, i) {
        $dateSel.append('<option value="' + i + '">' + 'Columna ' + (i + 1) + ' - ' + h + '</option>')
        $pulseSel.append('<option value="' + i + '">' + 'Columna ' + (i + 1) + ' - ' + h + '</option>')
        $systolicSel.append('<option value="' + i + '">' + 'Columna ' + (i + 1) + ' - ' + h + '</option>')
        $diastolicSel.append('<option value="' + i + '">' + 'Columna ' + (i + 1) + ' - ' + h + '</option>')
      })

      data = r.data
      $folder.val(r.folder)
    } else {
      new Noty({
        text: '<strong>¡Error!</strong><br>' + r.msg,
        type: 'error'
      }).show()
    }
  }

  function validateFormMap() {
    $loader2.css('display', 'block')
    if ($dateSel.val() === '' || $pulseSel.val() === '' || $systolicSel.val() === '' || $diastolicSel.val() === '') {
      new Noty({
        text: '<strong>¡Error!</strong><br>Debes seleccionar todas las columnas.',
        type: 'error'
      }).show()

      $loader2.css('display', 'none')
      return false
    } else {
      return true
    }
  }

  function showResponseMap(r) {
    $loader2.css('display', 'none')
    $btnMap.click()
    if (r.type) {
      new Noty({
        text: '<strong>¡Éxito!</strong><br>El archivo ha sido evaluado correctamente.',
        type: 'success'
      }).show()

      $('input:file').MultiFile('reset')
      $down.attr('href', r.url).css('display', 'block')
      $reload.css('display', 'block')
      $result.css('display', 'block');

      graphData(r.stats)
    } else {
      new Noty({
        text: '<strong>¡Error!</strong><br>' + r.msg,
        type: 'error'
      }).show()
    }
  }

  function validateManualData() {
    $loader3.css('display', 'block')

    if ($mtable.rows().count() < 3) {
      new Noty({
        text: '<strong>¡Error!</strong><br>Se necesita al menos tres registros para el análisis de datos.',
        type: 'error'
      }).show()

      $loader.css('display', 'none')
      return false
    } else {
      return true
    }
  }

  function showResponseManualData(r) {
    $loader3.css('display', 'none')
    $btnLoad.click()
    if (r.type) {
      new Noty({
        text: '<strong>¡Éxito!</strong><br>Los datos han sido evaluados correctamente.',
        type: 'success'
      }).show()

      //$mtable.clear().draw()
      //$btnManual.prop('disabled', true)
      $down.attr('href', r.url).css('display', 'block')
      $reload.css('display', 'block')
      $result.css('display', 'block');

      graphData(r.stats)
    } else {
      new Noty({
        text: '<strong>¡Error!</strong><br>' + r.msg,
        type: 'error'
      }).show()
    }
  }

  $showLoad.click(function () {
    $uploadFile.toggle(true)
    $manualData.toggle(false)
  })

  $showManual.click(function () {
    $uploadFile.toggle(false)
    $manualData.toggle(true)
    $('input:file').MultiFile('reset')
    $formMap.trigger('reset')
    $columnMap.toggle(false)

    if ($('#upload_file .card-body').is(':hidden')) {
      $('#upload_file .card-body').toggle(true)
      $('#upload_file .card-footer').toggle(true)
    }
  })

  $addData.click(function () {
    if ($mdate.val() === '' || $horaInicio.val() === '' || $mpulse.val() === '' || $msystolic.val() === '' || $mdiastolic.val() === '') {
      new Noty({
        text: '<strong>¡Error!</strong><br>Debes ingresar todos los valores para agregar la fila de datos.',
        type: 'error'
      }).show()
    } else {
      data.push(JSON.stringify([$mdate.val() + ' ' + $horaInicio.val(), $mpulse.val(), $msystolic.val(), $mdiastolic.val()]))
      $mdata.val('[' + data + ']')
      if (data.length >= 3) {
        $btnManual.prop('disabled', false)
      }
      $mtable.row.add([
        $mdate.val() + ' ' + $horaInicio.val(), $mpulse.val(), $msystolic.val(), $mdiastolic.val()
      ]).draw()
      $mdate.val('')
      $horaInicio.val('')
      $mpulse.val('')
      $msystolic.val('')
      $mdiastolic.val('')
      new Noty({
        text: '<strong>¡Éxito!</strong><br>La fila de datos ha sido agregada correctamente.',
        type: 'success'
      }).show()
    }
  })

  $formLoad.submit(function () {
    $(this).ajaxSubmit(optionsLoad)
    return false
  })

  $formMap.submit(function () {
    $(this).ajaxSubmit(optionsMap)
    return false
  })

  $formManual.submit(function () {
    $(this).ajaxSubmit(optionsManualData)
    return false
  })

  $reload.click(function () {
    location.reload()
  })
})