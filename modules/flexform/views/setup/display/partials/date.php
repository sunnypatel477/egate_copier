<?php
$default_value = ($preview) ? '' : flexform_get_block_answer($block);
echo render_date_input('answer_'.$block['id'], '', $default_value);