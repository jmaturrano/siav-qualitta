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
	                        <li><a href="<?php echo base_url('registros/listaprecio') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp;  <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_listaprecio',
							  'name'    	=> 'form_listaprecio',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'registros/listaprecio/guardar/'.((isset($data_lipe))?str_encrypt($data_lipe->lipe_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>

								<div class="control-group">
									<label for="mone_id" class="control-label">Moneda</label>
									<div class="controls">
					                    <select class="selectpicker span8" name="mone_id" id="mone_id" data-container="body">
					                    <option value="">Seleccione</option>
					                    <?php
					                    if(isset($data_mone)){
					                    	foreach ($data_mone as $item => $moneda) {
					                   	?>
											<option value="<?= $moneda->mone_id; ?>" <?= (isset($data_lipe) && $data_lipe->mone_id === $moneda->mone_id)?'selected': set_select('mone_id', $moneda->mone_id); ?>><?= $moneda->mone_descripcion; ?></option>
					                   	<?php
					                    	}
					                    }
					                    ?>
					                    </select>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="lipe_descripcion" class="control-label">Descripción</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_lipe))?$data_lipe->lipe_descripcion:set_value('lipe_descripcion'); ?>" id="lipe_descripcion" name="lipe_descripcion" class="span8" maxlength="50">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="lipe_indvigente" class="control-label">Lista principal</label>
									<div class="controls">
										<div>
											<?php $checked = isset($data_lipe) ? ( $data_lipe->lipe_indvigente === 'S' ? '1' : '0') : '0'; ?>
											<input type="checkbox" name="lipe_indvigente" data-toggle="checkbox-x" data-three-state="false" class="" value="<?= $checked; ?>" <?= ($checked === '1')?'checked':''; ?> id="lipe_indvigente">
										</div>
									</div>
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

<script src="<?php echo base_url('public/assets/js/Listaprecio.js') ?>"></script>