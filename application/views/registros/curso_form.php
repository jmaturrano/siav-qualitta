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
	                        <li><a href="<?php echo base_url('registros/curso') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_curso',
							  'name'    	=> 'form_curso',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'registros/curso/guardar/'.str_encrypt($data_carr->carr_id, KEY_ENCRYPT).'/'.str_encrypt($data_modu->modu_id, KEY_ENCRYPT).'/'.((isset($data_curs))?str_encrypt($data_curs->curs_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group">
									<label for="curs_codigo" class="control-label">Código curso</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_curs))?$data_curs->curs_codigo:set_value('curs_codigo'); ?>" id="curs_codigo" name="curs_codigo" class="span8" maxlength="10">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="curs_descripcion" class="control-label">Curso</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_curs))?$data_curs->curs_descripcion:set_value('curs_descripcion'); ?>" id="curs_descripcion" name="curs_descripcion" class="span8" maxlength="100">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="niap_id" class="control-label">Nivel de aprendizaje</label>
									<div class="controls">
					                    <select class="selectpicker span8" name="niap_id" id="niap_id" data-container="body">
					                    <option value="">Seleccione</option>
					                    <?php
					                    if(isset($data_niap)){
					                    	foreach ($data_niap as $item => $nivelaprendizaje) {
					                   	?>
											<option value="<?= $nivelaprendizaje->niap_id; ?>" <?= (isset($data_curs) && $data_curs->niap_id === $nivelaprendizaje->niap_id)?'selected': set_select('niap_id', $nivelaprendizaje->niap_id); ?>><?= $nivelaprendizaje->niap_codigo.' - '.$nivelaprendizaje->niap_descripcion; ?></option>
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


<script src="<?php echo base_url('public/assets/js/Curso.js') ?>"></script>