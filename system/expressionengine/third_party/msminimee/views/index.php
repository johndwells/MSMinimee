<p>You have not yet installed MSMinimee for this site; currently Minimee's default settings are in charge. To install MSMinimee for this site, begin here:</p>

<?php
	echo $form_open;

	echo '<p>' . form_submit('submit', lang('install'), 'class="submit"') . '</p>';
	echo form_close();
?>
