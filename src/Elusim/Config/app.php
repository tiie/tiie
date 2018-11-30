<?php

return array(
    'response' => array(
        'headers' => array(
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => ' Cache-Control, X-Requested-With, Content-Type',

            // Dodatkowe naglowki ktore mogą byc odczytane przez
            // przeglądarkę
            'Access-Control-Expose-Headers' => ' X-Rows-Number, X-Page-Size, X-Page, X-Page-Offset, X-Pages-Number',
        ),
        'engines' => array(
            'application/json' => 'json',
            'application/xml' => 'xml',
            'text/html' => 'twig',
        ),
        'contentType' => array(
            'negotiation' => 1,
            'default' => 'application/json',
            // 'priorities' => array('text/html; charset=UTF-8', 'application/json', 'application/xml;q=0.5'),
            'priorities' => array(
                'application/json',
                'application/xml',
                'text/html',
            ),
        ),
        'lang' => array(
            'negotiation' => 1,
            'default' => 'en-US,en',
            // 'priorities' => array('text/html; charset=UTF-8', 'application/json', 'application/xml;q=0.5'),
            'priorities' => array(
                'pl-PL,pl',
                'en-US,en'
            ),
        ),
    ),

    'elusim' => array(
        'errors' => array(
            'errorReporting' => array(
                // List of errors to display
                E_ERROR,
                E_WARNING,
                E_PARSE,
                E_NOTICE,
                E_CORE_ERROR,
                E_CORE_WARNING,
                E_COMPILE_ERROR,
                E_COMPILE_WARNING,
                E_USER_ERROR,
                E_USER_WARNING,
                E_USER_NOTICE,
                E_STRICT,
                E_RECOVERABLE_ERROR,
                E_DEPRECATED,
                E_USER_DEPRECATED,
            ),

            'errorReportingSilently' => true,
        ),
        'lang' => array(
            'dictionaries' => array(
                '@lang.dictionaries.elusim',
            )
        ),
        'twig' => array(
            'loader' => array(
                './src/App/templates',
            ),

            // 'layouts' => array(
            //     'main' => 'layouts/main.html'
            // ),
        ),
        'router' => array(
            'error' => array(
                'action' => \Elusim\Actions\Error::class
            )
        ),
        'components' => array(
            // 'dirs' => array(
            //     "../src/Components"
            // )
        ),
        'actions' => array(
            // 'default' => array(

            // ),
            // 'rest' => array(
            //     'requireParameterDescription' => true,
            //     'requireFieldsDescription' => true,
            // ),
        ),
        'http' => array(

        ),
    )
);
