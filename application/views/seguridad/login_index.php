<div class="account-container">
	<div class="content clearfix">
		<?php
		$attributes = array(
		  'id'      => 'form_login',
		  'name'    => 'form_login'
		  );
		$ruta = 'seguridad/login/{FUNCTION}';
		echo form_open($ruta, $attributes);
		?>
			<h1 class="texto-centrado">
				<img src="<?= base_url(LOGOEMPRESA); ?>"/>
			</h1>
			<div class="login-fields">
				<div class="field">
					<label for="usua_numero_documento">DNI</label>
					<input type="text" id="usua_numero_documento" name="usua_numero_documento" value="" placeholder="Documento de Identidad" class="login docidenti-field" />
				</div> <!-- /field -->
				
				<div class="field">
					<label for="usua_nombre">Usuario</label>
					<input type="text" id="usua_nombre" name="usua_nombre" value="" placeholder="Usuario" class="login username-field" disabled="disabled"/>
				</div> <!-- /field -->
				
				<div class="field">
					<label for="usua_clave">Contraseña:</label>
					<input type="password" id="usua_clave" name="usua_clave" value="" placeholder="Clave" class="login password-field" disabled="disabled"/>
				</div> <!-- /password -->
			</div> <!-- /login-fields -->
			
			<div class="login-actions">
				
				<span class="login-checkbox">
					<a id="linkPASS" href="#">Restablecer Clave</a>
					<!--			
					<input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4" />
					<label class="choice" for="Field">Keep me signed in</label>
					-->
				</span>
				
				<button class="button btn btn-success btn-large" type="button" id="usua_submit">Ingresar</button>
			</div> <!-- .actions -->
       	<?php
            echo form_close();
        ?>
	</div> <!-- /content -->
</div> <!-- /account-container -->
<!--
<div class="login-extra">
	<a href="#">Reset Password</a>
</div> <!-- /login-extra -->

<?php
	if(isset($error)){
		echo '<script> var error_msg = "'.$error.'";</script>';
	}
?>
<script src="<?php echo base_url('public/assets/js/login.js') ?>"></script>


