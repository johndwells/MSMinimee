<?php

	echo '<p>' . lang('install_msg') . '</p>';

	echo $form_open;

	echo '<p>' . form_submit('submit', lang('install'), 'class="submit"') . '</p>';
	echo form_close();
?>
