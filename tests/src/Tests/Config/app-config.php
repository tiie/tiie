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
            'priorities' => array(
                'application/json',
                'text/html',
            ),
        ),
        'lang' => array(
            'negotiation' => 0,
            'priorities' => array(
                'en-US,en'
            ),
        ),
    ),

    'elusim' => array(
        'errors' => array(
            'errorReporting' => array(
                // List of errors to display
                E_STRICT,
                E_RECOVERABLE_ERROR,
                E_DEPRECATED,
                E_USER_DEPRECATED,
            ),

            'errorReportingSilently' => false,
        ),
        'lang' => array(
            'dictionaries' => array(
                '@lang.dictionaries.elusim',
                '@lang.dictionaries.elusim-a',
                '@lang.dictionaries.elusim-b',
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
                'action' => \Elusim\Actions\Error::class
            )
        ),
        'components' => array(
            'dirs' => array(
                "../src/Components"
            )
        ),
    )
);

