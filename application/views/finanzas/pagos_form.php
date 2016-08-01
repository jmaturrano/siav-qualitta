<div class="main">
	<div class="main-inner">
	    <div class="container">
	      <div class="row">
	      	<div class="span12">
	      		<div class="widget">
	      			<div class="widget-header">
	      				<i class="<?= $header_icon; ?>"></i>
	      				<h3><?= $header_title; ?></h3>
	                    <ul class="breadcrumb custom-breadcrumb">
	                        <li><a href="<?php echo base_url('panel/principal') ?>"><i class="<?php echo ICONO_HOME ?>"></i>&nbsp; Inicio</a></li>
	                        <li>&nbsp; /</li>
	                        <li><a href="<?php echo base_url('servicios/matricula') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_pagos',
							  'name'    	=> 'form_pagos',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'servicios/matricula/guardar/'.((isset($data_matr))?str_encrypt($data_matr->matr_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
                                <div class="control-group">
                                    <label for="moda_id" class="control-label" style="">Modalidad</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="moda_id" id="moda_id" data-live-search="true" data-subruta="" data-container="body" disabled>
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_moda)){
                                            foreach ($data_moda as $item => $modalidad) {
                                        ?>
                                            <option value="<?= $modalidad->moda_id; ?>" <?= (isset($data_matr) && $data_matr->moda_id === $modalidad->moda_id)?'selected':set_select('moda_id', $modalidad->moda_id); ?>><?= substr($modalidad->moda_descripcion, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="carr_id" class="control-label" style="">Programa de instrucción</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="carr_id" id="carr_id" data-live-search="true" data-subruta="" data-container="body" disabled>
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_carr)){
                                            foreach ($data_carr as $item => $carrera) {
                                        ?>
                                            <option value="<?= $carrera->carr_id; ?>" <?= (isset($data_matr) && $data_matr->carr_id === $carrera->carr_id)?'selected':set_select('carr_id', $carrera->carr_id); ?>><?= $carrera->carr_codigo.' - '.substr($carrera->carr_descripcion, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="alum_id" class="control-label" style="">Alumno</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="alum_id" id="alum_id" data-live-search="true" data-subruta="" data-container="body" disabled>
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_alum)){
                                            foreach ($data_alum as $item => $alumno) {
                                        ?>
                                            <option value="<?= $alumno->alum_id; ?>" <?= (isset($data_matr) && $data_matr->alum_id === $alumno->alum_id)?'selected':set_select('alum_id', $alumno->alum_id); ?>><?= $alumno->alum_codigo.' - '.substr($alumno->alum_apellido.' '.$alumno->alum_nombre, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="lipe_id" class="control-label" style="">Lista Precio</label>
                                    <div class="controls">
                                        <select class="selectpicker span8 selectpicker-carrera" name="lipe_id" id="lipe_id" data-live-search="true" data-subruta="" data-container="body" disabled>
                                            <option value="">Seleccione</option>
	                                        <?php
	                                        if(isset($data_lipe)){
	                                            foreach ($data_lipe as $item => $lipe) {
	                                            	$checked = '';
	                                            	$principal = '';
	                                            	if($lipe->lipe_indvigente === 'S'){
	                                            		$principal = ' - Principal';
	                                            	}
	                                            	if(isset($data_matr)){
	                                            		if($data_matr->lipe_id === $lipe->lipe_id){
	                                            			$checked = 'selected';
	                                            		}
	                                            	}else{
	                                            		$checked = ($lipe->lipe_indvigente === 'S' ? 'selected' : '');
	                                            	}
	                                        ?>
	                                            <option <?= $checked; ?> value="<?= $lipe->lipe_id; ?>"><?= $lipe->lipe_descripcion.$principal; ?></option>
	                                        <?php
	                                            }//end foreach
	                                        }
	                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="gmat_id" class="control-label" style="">Grupo Inicio</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="gmat_id" id="gmat_id" data-live-search="true" data-subruta="" data-container="body" disabled>
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_gmat)){
                                            foreach ($data_gmat as $item => $grupomat) {
                                        ?>
                                            <option value="<?= $grupomat->gmat_id; ?>" <?= (isset($data_matr) && $data_matr->gmat_id === $grupomat->gmat_id)?'selected':set_select('gmat_id', $grupomat->gmat_id); ?>><?= fecha_latino($grupomat->gmat_fecha_inicio); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

								<div class="control-group">
									<label for="matr_codigo" class="control-label">Código</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_matr))?$data_matr->matr_codigo:set_value('matr_codigo'); ?>" id="matr_codigo" name="matr_codigo" class="span8 campo_editable" maxlength="20" disabled>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="matr_fecha_proceso" class="control-label">Fecha matrícula</label>
									<div class="controls">
									    <div class="input-append date datepicker" data-date="<?= (isset($data_matr))?fecha_latino($data_matr->matr_fecha_proceso):date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy">
									      <input class="span2" size="16" type="text" value="<?= (isset($data_matr))?fecha_latino($data_matr->matr_fecha_proceso):date('d/m/Y'); ?>" id="matr_fecha_proceso" name="matr_fecha_proceso" disabled>
									      <span class="add-on"><i class="icon-th"></i></span>
									    </div>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="matr_observacion" class="control-label">Observaciones</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_matr))?$data_matr->matr_observacion:set_value('matr_observacion'); ?>" id="matr_observacion" name="matr_observacion" class="span8 campo_editable" maxlength="200" disabled>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<hr>
								
                                <div class="control-group">
                                    <label for="emat_id" class="control-label" style="">Estado Matrícula</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="emat_id" id="emat_id" data-live-search="true" data-subruta="" data-container="body" disabled>
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_emat)){
                                            foreach ($data_emat as $item => $estado) {
                                        ?>
                                            <option value="<?= $estado->emat_id; ?>" <?= (isset($data_matr) && $data_matr->emat_id === $estado->emat_id)?'selected':set_select('emat_id', $estado->emat_id); ?>><?= substr($estado->emat_descripcion, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

							</fieldset>
					       	<?php
					        echo form_close();
					        ?>

						</div>
					</div> <!-- /widget-content -->
				</div> <!-- /widget -->
		    </div> <!-- /span8 -->
	      </div> <!-- /row -->
	    </div> <!-- /container -->
	</div> <!-- /main-inner -->
</div> <!-- /main -->







<script src="<?php echo base_url('public/assets/js/Pagos.js') ?>"></script>