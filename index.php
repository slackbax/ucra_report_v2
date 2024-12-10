<?php
require 'src/settings.php';
require 'src/constants.php';
require 'src/fn.php';
extract($_GET);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRT Biobío - Generador de reporte UCRA</title>
  <?php //include 'src/favicon.php' ?>
  <?php include 'src/styles.php' ?>
</head>

<body class="hold-transition layout-boxed layout-footer-fixed">
<section class="content" id="main-screen">
  <div class="wrapper">
    <div class="row">
      <div class="col-12">
        <div class="text-center mt-3">
          <a href="https://www.crtbiobio.cl"><img alt="CRT Biobio" src="dist/img/logo_crt.png" style="height:50px"></a>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-head">
    <div class="wrapper mt-4">
      <div class="row">
        <div class="alert mt-4">
          <h3>Cuidar la presión arterial: protege tu salud</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="wrapper">
    <div class="row">
      <div class="alert mt-4">
        <p>
          La presión arterial es la fuerza con la que la sangre circula por tus vasos sanguíneos. Si esta
          es demasiado alta, aunque muchas veces no presente síntomas, puede causar problemas
          graves como infartos, derrames cerebrales o insuficiencia renal. Controlarla significa
          prevenir complicaciones que podrían perjudicar drásticamente tu salud y calidad de vida.
        </p>
        <h5 class="font-weight-bold mb-3">Importante: ¡Entrega estos resultados a tu equipo médico!</h5>
        <p>
          Este informe y registro gráfico proporciona información sobre cómo se comporta tu
          presión arterial ambulatoria durante el día en diferentes días de la semana. Estos
          resultados permitirán a tu equipo médico evaluar con mayor precisión tu salud
          cardiovascular y ajustar el tratamiento si fuera necesario. Llévalo a tu próxima consulta
          para aprovechar al máximo esta información.
        </p>
        <p>
          <strong>Advertencia:</strong> Si hubiera un registro de Pº arterial mayor o igual 160/100, debes concurrir
          a la brevedad al consultorio para evaluación por tu médico tratante.
        </p>
      </div>

      <div class="col-md-6 offset-md-3">
        <button class="btn btn-block btn-primary collapsed" type="button" data-toggle="collapse" data-target="#collapseRecommendations" aria-expanded="false">
          <i class="fa fa-plus mr-2"></i>Ver recomendaciones de salud
        </button>
      </div>
      <div class="col-md-10 offset-md-1 mt-3">
        <div id="collapseRecommendations" class="collapse">
          <div class="card card-body card-success">
            <ol>
              <li><strong>Asiste regularmente a tus controles con tu equipo médico:</strong>
                <ul>
                  <li>Mide tu presión arterial y sigue las indicaciones si necesitas tratamiento.</li>
                </ul>
              </li>
              <li><strong>Adopta una alimentación saludable:</strong>
                <ul>
                  <li>Reduce el consumo de sal (máximo una cucharadita de té al día).</li>
                  <li>Come más frutas, verduras y alimentos integrales.</li>
                  <li>Limita las grasas saturadas y los alimentos procesados.</li>
                  <li>Mantén una hidratación adecuada con una buena ingesta de líquidos.</li>
                </ul>
              </li>
              Se recomienda pedir apoyo de nutricionista (mayor información en anexo nutrición del Portal Salud Renal del CRT).
              <li><strong>Haz ejercicio regularmente:</strong>
                <ul>
                  <li>Camina, baila o realiza actividad física al menos 30 minutos al día, 5 veces a la semana.</li>
                </ul>
              </li>
              <li><strong>Mantén un peso saludable:</strong>
                <ul>
                  <li>Evita el sobrepeso y la obesidad, ya que aumenta el riesgo de presión alta.</li>
                </ul>
              </li>
              <li><strong>Evita el consumo excesivo de alcohol y tabaco:</strong>
                <ul>
                  <li>Limita el alcohol y, si fumas, busca ayuda para dejarlo.</li>
                </ul>
              </li>
              <li><strong>Controla el estrés:</strong>
                <ul>
                  <li>Practica actividades como yoga, meditación o cualquier actividad que te relaje.</li>
                </ul>
              </li>
            </ol>
          </div>
        </div>
      </div>

      <div class="col-10 offset-1 mt-3 text-center">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn btn-lg btn-success active">
            <input type="radio" name="options" id="option_load" autocomplete="off"><i class="fas fa-upload mr-2"></i>Cargar archivo
          </label>
          <label class="btn btn-lg btn-success">
            <input type="radio" name="options" id="option_manual" autocomplete="off"><i class="fas fa-edit mr-2"></i>Ingreso manual
          </label>
          <!--<label class="btn btn-lg btn-success">
            <input type="radio" name="options" id="option_a3" autocomplete="off"> Opcion 3
          </label>-->
        </div>
      </div>

      <div id="upload_file" class="col-10 offset-1" style="display: none">
        <form id="form-load">
          <div class="card card-primary card-outline mt-4">
            <div class="card-header">
              <h3 class="card-title">
                <i class="ion ion-stats-bars mr-2"></i>Generador de Reporte Telemonitoreo
              </h3>
              <div class="card-tools">
                <button type="button" id="btn-load" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="form-group col-10 offset-1 text-center">
                  <div class="controls">
                    <input type="hidden" name="id" value="<?php echo date('Y-m-d H:i:s') ?>">
                    <input name="patdata[]" id="patdata" type="file">
                    <span class="form-text text-muted">Formatos admitidos: CSV</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-md-6 offset-md-3 text-center">
                  <button type="submit" class="btn btn-block btn-lg btn-primary">
                    <i class="fa fa-file-search mr-2"></i>Analizar archivo
                    <span id="loader" class="spinner-border" role="status" aria-hidden="true"></span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div id="column_map" class="col-10 offset-1" style="display: none">
        <form id="form-mapping">
          <div class="card card-primary card-outline">
            <input type="hidden" id="folder" name="folder">
            <div class="card-header">
              <h3 class="card-title">
                <i class="ion ion-stats-bars mr-2"></i>Mapeo de columnas
              </h3>
              <div class="card-tools">
                <button type="button" id="btn-map" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="form-group col-6">
                  <label for="date">Fecha</label>
                  <div class="input-group">
                    <select class="form-control" name="date_sel" id="date">
                      <option value="">Selecciona columna</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-6">
                  <label for="pulse">Pulso</label>
                  <div class="input-group">
                    <select class="form-control" name="pulse_sel" id="pulse">
                      <option value="">Selecciona columna</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-6">
                  <label for="systolic">P. Sistólica</label>
                  <div class="input-group">
                    <select class="form-control" name="systolic_sel" id="systolic">
                      <option value="">Selecciona columna</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-6">
                  <label for="diastolic">P. Diastólica</label>
                  <div class="input-group">
                    <select class="form-control" name="diastolic_sel" id="diastolic">
                      <option value="">Selecciona columna</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-md-6 offset-md-3 text-center">
                  <button type="submit" class="btn btn-block btn-lg btn-primary">
                    <i class="fa fa-chart-line mr-2"></i>Analizar datos
                    <span id="loader2" class="spinner-border" role="status" aria-hidden="true"></span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div id="manual_data" class="col-10 offset-1" style="display: none">
        <form id="form-manual">
          <input type="hidden" name="data" id="mdata">
          <div class="card card-primary card-outline mt-4">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-edit mr-2"></i>Ingreso manual
              </h3>
              <div class="card-tools">
                <button type="button" id="btn-load" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="form-group col-3 offset-1">
                  <label for="mdate"><i class="fad fa-calendar-alt mr-2"></i>Fecha</label>
                  <div id="gdate" class="input-group date" data-target-input="nearest">
                    <input type="text" id="mdate" name="mdate" class="form-control datetimepicker-input float-right" data-target="#gdate">
                    <div class="input-group-append" data-target="#gdate" data-toggle="datetimepicker">
                      <span class="input-group-text">
                        <i class="fad fa-calendar-alt"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group col-2">
                  <label for="hora-inicio"><i class="fad fa-clock mr-2"></i>Hora</label>
                  <div class="input-group date" id="ghora-inicio" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" id="hora-inicio" name="h_ini" data-target="#ghora-inicio">
                    <div class="input-group-append" data-target="#ghora-inicio" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="far fa-clock"></i></div>
                    </div>
                  </div>
                </div>

                <div class="form-group col-5">
                  <label for="mpulse"><i class="fad fa-heartbeat mr-2"></i>Pulso</label>
                  <div class="input-group">
                    <input type="text" id="mpulse" name="pulse" class="form-control text-right number" autocomplete="off">
                    <div class="input-group-append">
                      <span class="input-group-text">BPM</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="form-group col-5 offset-1">
                  <label for="msystolic"><i class="fad fa-heart-rate mr-2"></i>P. Sistólica</label>
                  <div class="input-group">
                    <input type="text" id="msystolic" name="systolic" class="form-control text-right number" autocomplete="off">
                    <div class="input-group-append">
                      <span class="input-group-text">mm/Hg</span>
                    </div>
                  </div>
                </div>

                <div class="form-group col-5">
                  <label for="mdiastolic"><i class="fad fa-heart-rate mr-2"></i>P. Diastólica</label>
                  <div class="input-group">
                    <input type="text" id="mdiastolic" name="diastolic" class="form-control text-right number" autocomplete="off">
                    <div class="input-group-append">
                      <span class="input-group-text">mm/Hg</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-10 offset-1">
                  <button type="button" id="add_data" class="btn btn-success float-right">
                    <i class="fa fa-plus mr-2"></i>Agregar
                  </button>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-10 offset-1">
                  <table id="mtable" class="table table-striped">
                    <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Pulso</th>
                      <th>P. Sistólica</th>
                      <th>P. Diastólica</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-md-6 offset-md-3 text-center">
                  <button type="submit" id="btn_manual" class="btn btn-block btn-lg btn-primary" disabled>
                    <i class="fa fa-file-search mr-2"></i>Analizar datos
                    <span id="loader3" class="spinner-border" role="status" aria-hidden="true"></span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div id="result_div" class="col-10 offset-1">
        <div class="card">
          <div class="card-body">
            <canvas id="pressureChart"></canvas>
          </div>
          <div class="card-body">
            <canvas id="heartChart"></canvas>
          </div>
        </div>
      </div>

      <div class="col-4 offset-4 mb-1">
        <a id="down_link" class="btn btn-block btn-lg btn-success" target="_blank">
          <i class="fa fa-file-pdf mr-2"></i>Descargar informe
        </a>
      </div>
      <div class="col-4 offset-4 mb-5">
        <a id="restart_link" class="btn btn-block btn-primary">
          <i class="fa fa-redo mr-2"></i>Reiniciar proceso
        </a>
      </div>
    </div>
  </div>
</section>

<?php include 'src/scripts.php' ?>
</body>
</html>
