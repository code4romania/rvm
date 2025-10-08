<?php
return [
    'label' => [
        'singular' => 'News',
        'plural' => 'News',
    ],
    'field' => [
        'title' => 'Title',
        'body' => 'Content',
        'status' => 'Status',
        'organisation' => 'Organization',
        'updated_at' => 'Last update',
        'media_files' => 'Photos gallery',
        'cover_photo' => 'Cover photo',
        'published_at' => 'Published at',
    ],
    'status' => [
        'drafted' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
    ],
    'action' => [
        'change_status' => [
            'draft' => [
                'heading' => 'Move to Draft',
                'subheading' => 'Are you sure you want to move this news article to draft? Once moved to draft, it can be edited and republished on the Intervention Coordinator website, under the Partners\' News section.',
                'button' => 'Move to Draft',
                'success' => 'The news article has been moved to draft.',
            ],
            'publish' => [
                'heading' => 'Publish the news',
                'subheading' => 'Are you sure you want to publish this article? Once published, it will be displayed on the Intervention coordinator\'s website in the Partners\' News section.',
                'button' => 'Publish',
                'success' => 'The news article has been published.',
            ],
            'archive' => [
                'heading' => 'Archive this article',
                'subheading' => 'Are you sure you want to archive this article? Once archived, it will no longer be displayed on the Intervention Coordinator\'s website in the Partners\' News section.',
                'button' => 'Archive',
                'success' => 'The news item has been archived.',
            ],
        ],
    ],
    'disclaimer' => '<b> Important: </b> News articles published in this section must be directly related to emergencies, civil protection, or other topics relevant to working with the Intervention Coordinator.',
];
