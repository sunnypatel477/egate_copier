<?php
$default_value = ($preview) ? '' : flexform_get_block_answer($block);
echo render_color_picker('answer_'.$block['id'], '', $default_value);