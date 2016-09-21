<div class="main">
	<div class="main-inner">
	    <div class="container">
	      <div class="row">
	      	<div class="span12">
	      		<div class="widget">
	      			<div class="widget-header">
	      				<i class="<?= $header_icon; ?>"></i>
	      				<h2><?= $header_title; ?></h2>
	                    <ul class="breadcrumb custom-breadcrumb">
	                        <li><a href="<?php echo base_url('panel/principal') ?>"><i class="<?php echo ICONO_HOME ?>"></i>&nbsp; Inicio</a></li>
	                        <li>&nbsp; /</li>
	                        <li><a href="<?php echo base_url('registros/certificadosxalumno') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp;  <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_certificadosxalumno',
							  'name'    	=> 'form_certificadosxalumno',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'servicios/certificadosxalumno/guardar/'.str_encrypt($data_matr->matr_id, KEY_ENCRYPT).'/'.((isset($data_cxal))?str_encrypt($data_cxal->cxal_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
                                <div class="control-group">
                                    <label for="cele_id" class="control-label" style="">Certificado/Constancia</label>
                                    <div class="controls">
                                        <select class="selectpicker span8 selectpicker-carrera" name="cele_id" id="cele_id" data-live-search="true" data-subruta="" data-container="body">
                                            <option value="" data_anios_vigencia="" data_unidad_vigencia="" data_unidad_vigencia_="" >Seleccione</option>
	                                        <?php
	                                        if(isset($data_cele)){
	                                            foreach ($data_cele as $item => $cele) {
	                                            	$checked = '';
	                                            	if(isset($data_cxal)){
	                                            		if($data_cxal->cele_id === $cele->cele_id){
	                                            			$checked = 'selected';
	                                            		}
	                                            	}//end if
	                                            	$vigencia = $cele->cele_anios_vigencia;
	                                            	$unidadvig = $cele->cele_unidad_vigencia;
	                                            	$vigencia_ = $cele->cele_anios_vigencia.' ('.ucfirst(strtolower(str_replace('N', 'ñ', $cele->cele_unidad_vigencia))).')';
	                                        ?>
	                                            <option <?= $checked; ?> value="<?= $cele->cele_id; ?>" 
	                                            data_anios_vigencia = "<?= $vigencia; ?>" 
	                                            data_unidad_vigencia = "<?= $unidadvig; ?>" 
	                                            data_unidad_vigencia_ = "<?= $vigencia_; ?>" 
	                                            >
	                                            	<?= $cele->cele_descripcion; ?>
	                                            </option>
	                                        <?php
	                                            }//end foreach
	                                        }
	                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->
								<div class="control-group">
									<label for="vigencia" class="control-label">Vigencia</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_cxal) ? $data_cxal->cele_anios_vigencia.' ('.ucfirst(strtolower(str_replace('N', 'ñ', $data_cxal->cele_unidad_vigencia))).')' : ''); ?>" id="vigencia" name="vigencia" class="span8" disabled>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="cxal_fecha_vencimiento" class="control-label">Fecha matrícula</label>
									<div class="controls">
										<?php 
											$cxal_fecha_vencimiento = (isset($data_cxal))?(($data_cxal->cxal_fecha_vencimiento==='0000-00-00'||$data_cxal->cxal_fecha_vencimiento===null)?'':fecha_latino($data_cxal->cxal_fecha_vencimiento)):'';
										?>
									    <div class="datepickerx" data-date="<?= $cxal_fecha_vencimiento; ?>" data-date-format="dd/mm/yyyy">
									      <input class="span2" size="16" type="text" value="<?= $cxal_fecha_vencimiento; ?>" id="cxal_fecha_vencimiento" name="cxal_fecha_vencimiento" disabled>
									    </div>
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

<script src="<?php echo base_url('public/assets/js/Certificadosxalumno.js') ?>"></script>