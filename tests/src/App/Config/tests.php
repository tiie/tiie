<?php
return array(
    'tiie' => array(
        'components' => array(
            'dirs' => array(
                "./src/App/Components",
            )
        ),
        'lang' => array(
            'default' => '@lang.dictionaries.app',
            'dictionaries' => array(
                '@lang.dictionaries.app',
            ),
        ),
        'router' => array(
            'routes' => array(
                'api' => array(
                    'urn' => '/api',
                    'dir' => './src/App/Actions',
                    'namespace' => "\\App\\Actions",
                )
            )
        )
    )
);
