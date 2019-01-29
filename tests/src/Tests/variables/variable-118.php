<?php return array (
  'response' => 
  array (
    'headers' => 
    array (
      'Access-Control-Allow-Origin' => '*',
      'Access-Control-Allow-Headers' => 'Cache-Control, X-Requested-With, Content-Type',
      'Access-Control-Expose-Headers' => ' X-Rows-Number, X-Page-Size, X-Page, X-Page-Offset, X-Pages-Number',
    ),
    'engines' => 
    array (
      'application/json' => 'json',
      'default' => 'application/json',
      'text/html' => 'twig',
    ),
    'contentType' => 
    array (
      'negotiation' => 1,
      'priorities' => 
      array (
        0 => 'text/html',
        1 => 'application/pdf',
      ),
    ),
    'lang' => 
    array (
      'priorities' => 
      array (
        0 => 'pl-PL,pl',
        1 => 'en-US,en',
        2 => 'ar-EG,eg',
      ),
      'negotiation' => 0,
    ),
  ),
  'tiie' => 
  array (
    'errors' => 
    array (
      'errorReporting' => 
      array (
        0 => 'E_STRICT',
        1 => 'E_RECOVERABLE_ERROR',
        2 => 'E_DEPRECATED',
        3 => 'E_USER_DEPRECATED',
      ),
      'errorReportingSilently' => false,
    ),
    'lang' => 
    array (
      'dictionaries' => 
      array (
        0 => '@lang.dictionaries.tiie',
        1 => '@lang.dictionaries.tiie-a',
        2 => '@lang.dictionaries.tiie-b',
      ),
    ),
    'router' => 
    array (
      'error' => 
      array (
        'action' => '\\Tiie\\Actions\\Error',
      ),
    ),
    'errorReportingSilently' => true,
    'twig' => 
    array (
      'loader' => 
      array (
        0 => './src/App/templates',
        1 => './src/App/templates-main',
      ),
    ),
    'components' => 
    array (
      'dirs' => 
      array (
        0 => '../src/Components',
      ),
    ),
  ),
);