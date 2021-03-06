<?php
return array(
    'response' => array(
        'headers' => array(
            'Access-Control-Expose-Headers' => ' X-Rows-Number, X-Page-Size, X-Page, X-Page-Offset, X-Pages-Number',
        ),
        'engines' => array(
            'text/html' => 'twig',
        ),
        'contentType' => array(
            '_priorities' => array(
                'text/html',
                'application/pdf',
            ),
        ),
        'lang' => array(
            'negotiation' => 0,
            'priorities' => array(
                'ar-EG,eg'
            ),
        ),
    ),

    'tiie' => array(
        'errors' => array(
            '_errorReporting' => array(
                // List of errors to display
                'E_STRICT',
                'E_RECOVERABLE_ERROR',
                'E_DEPRECATED',
                'E_USER_DEPRECATED',
            ),

            'errorReportingSilently' => false,
        ),
        'lang' => array(
            'dictionaries' => array(
                '@lang.dictionaries.tiie',
                '@lang.dictionaries.tiie-a',
                '@lang.dictionaries.tiie-b',
            )
        ),
        'twig' => array(
            'loader' => array(
                './src/App/templates',
                './src/App/templates-main',
            ),
        ),
        'router' => array(
            'error' => array(
                'action' => '\Tiie\Actions\Error',
            )
        ),
        'components' => array(
            'dirs' => array(
                "../src/Components"
            )
        ),
    )
);

