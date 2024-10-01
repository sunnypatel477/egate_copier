<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Flexforum_notification_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name' => 'Name',
                'key' => '{name}',
                'available' => [
                ],
                'templates' => [
                    'flexforum-reply-notification',
                ],
            ],
            [
                'name' => 'FlexForum Link',
                'key' => '{link}',
                'available' => [
                ],
                'templates' => [
                    'flexforum-reply-notification',
                ],
            ],
        ];
    }

    /**
     * Flexibackup event merge fields
     */
    public function format($data)
    {
        $fields['{name}'] = $data['name'];
        $fields['{link}'] = $data['link'];
        
        return $fields;
    }
}