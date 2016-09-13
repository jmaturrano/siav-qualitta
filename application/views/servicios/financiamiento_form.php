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
	                        <li><a href="<?php echo base_url('servicios/financiamiento') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_financiamiento',
							  'name'    	=> 'form_financiamiento',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$funcion_guardar = (isset($finanzas)) ? 'guardarpago' : 'guardar';
							$ruta = 'servicios/financiamiento/'.$funcion_guardar.'/'.str_encrypt($data_matr->matr_id, KEY_ENCRYPT).'/'.((isset($data_fima))?str_encrypt($data_fima->fima_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>

								<div class="control-group">
									<label for="matr_codigo" class="control-label">Código Matrícula</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_matr))?$data_matr->matr_codigo:set_value('matr_codigo'); ?>" id="matr_codigo" name="matr_codigo" class="span8" disabled>
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
									<label for="fima_monto" class="control-label">Monto</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_fima))?$data_fima->fima_monto:set_value('fima_monto'); ?>" id="fima_monto" name="fima_monto" class="span8">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="fima_fecha_programada" class="control-label">Fecha programada</label>
									<div class="controls">
									    <div class="datepickerx" data-date="<?= (isset($data_fima))?fecha_latino($data_fima->fima_fecha_programada):date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy">
									      <input class="span2" size="16" type="text" value="<?= (isset($data_fima))?fecha_latino($data_fima->fima_fecha_programada):date('d/m/Y'); ?>" id="fima_fecha_programada" name="fima_fecha_programada">
									    </div>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								
								<?php 
									if(isset($finanzas)){
								?>

								<div class="control-group">
									<label for="fima_pagado" class="control-label">Pagado?</label>
									<div class="controls">
										<input type="checkbox" name="fima_pagado" data-toggle="checkbox-x" data-three-state="false" class="" value="0" id="fima_pagado">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="fima_comprobante" class="control-label">Comprobante</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_fima))?$data_fima->fima_comprobante:set_value('fima_comprobante'); ?>" id="fima_comprobante" name="fima_comprobante" class="span8">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="fima_fecha_proceso" class="control-label">Fecha proceso</label>
									<div class="controls">
									    <div class="datepickerx" data-date="<?= (isset($data_fima))?fecha_latino($data_fima->fima_fecha_proceso):date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy">
									      <input class="span2" size="16" type="text" value="<?= (isset($data_fima))?fecha_latino($data_fima->fima_fecha_proceso):''; ?>" id="fima_fecha_proceso" name="fima_fecha_proceso">
									    </div>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<?php
									}//end if
								?>
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







<script src="<?php echo base_url('public/assets/js/Financiamiento.js') ?>"></script>