<?php

if (!function_exists('emailCanvasGetEmailContent')) {
    function emailCanvasGetEmailContent($email_template_object)
    {
        // include(__DIR__ . '/../models/Emailcanvas_model.php');

        $CI = &get_instance();
        // $CI->load->model('emailcanvas_model');
         $CI->load->model('emailcanvas/emailcanvas_model');

        $templateSlug = $email_template_object['template']->slug;
        $templateLanguage = $email_template_object['template']->language;

        $emailTemplate = $CI->emailcanvas_model->getEmailTemplateToSend($templateSlug, $templateLanguage);

        //If logic for mail sending
        if (empty($emailTemplate)) {
            return $email_template_object;
        } elseif (empty($emailTemplate['template_html_css'])) {
            return $email_template_object;
        }

        $emailTemplateHtmlCss = json_decode($emailTemplate['template_html_css']);

        $dom = new DOMDocument();
        $dom->loadHTML($email_template_object['template']->message);

        $tdElements = $dom->getElementsByTagName('td');

        $tdCount = 0;
        $extractedContent = '';

        foreach ($tdElements as $td) {

            $tdCount++;
            if ($tdCount === 1) {
                continue;
            }

            $tdContent = $dom->saveHTML($td);

            if (strpos($tdContent, '<!-- START MAIN CONTENT AREA -->') !== false && strpos($tdContent, '<!-- END MAIN CONTENT AREA -->') !== false) {
                $startPos = strpos($tdContent, '<!-- START MAIN CONTENT AREA -->');
                $endPos = strpos($tdContent, '<!-- END MAIN CONTENT AREA -->');
                $extractedContent = substr($tdContent, $startPos, $endPos - $startPos);
                break;
            }
        }

        $extractedContent = str_replace(
            array(
                '<!-- START MAIN CONTENT AREA -->',
                '<tr>',
                '<td class="wrapper">',
                '<td class="wrapper">',
                '<table border="0" cellpadding="0" cellspacing="0">',
                '</tr>',
                '</table>',
                '</table>',
                '</td>',
                '</tr>',
                '<td>',
                ''
            ), '', $extractedContent);

        $extractedContent = trim($extractedContent);
        $replaceCustomTemplateMergeFields = str_replace('{email_template_content}', $extractedContent, $emailTemplateHtmlCss);

        $merge_fields = [];
        $merge_fields = array_merge($merge_fields, $CI->other_merge_fields->format());

        foreach ($merge_fields as $key => $val) {
            if (stripos($replaceCustomTemplateMergeFields, $key) !== false) {
                $replaceCustomTemplateMergeFields = str_ireplace($key, $val, $replaceCustomTemplateMergeFields);
            } else {
                $replaceCustomTemplateMergeFields = str_ireplace($key, '', $replaceCustomTemplateMergeFields);
            }
        }

        $email_template_object['template']->message = $replaceCustomTemplateMergeFields;

        return $email_template_object;
    }
}