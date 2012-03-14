<?php

	echo '<p>' . lang('uninstall_msg') . '</p>';

	echo $form_open;

	echo '<p>' . form_submit('submit', lang('uninstall_button'), 'class="submit"') . '&nbsp;&nbsp;or <a href="' . $nevermind_url . '">Cancel</a></p>';

	echo form_close();
?>
