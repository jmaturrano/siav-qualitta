<?php
require_once 'header.php';

if($this->session->userdata('usua_id')){
	require_once 'navbar.php';
}

echo $contenido;

require_once 'footer.php';

