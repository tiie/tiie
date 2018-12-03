<?php return array (
  'response' => 
  array (
    'headers' => 
    array (
      'Access-Control-Allow-Origin' => '*',
      'Access-Control-Allow-Headers' => 'Cache-Control, X-Requested-With, Content-Type',
    ),
    'engines' => 
    array (
      'application/json' => 'json',
      'default' => 'application/json',
    ),
    'contentType' => 
    array (
      'negotiation' => 1,
      'priorities' => 
      array (
        0 => 'application/json',
      ),
    ),
    'lang' => 
    array (
      'priorities' => 
      array (
        0 => 'pl-PL,pl',
        1 => 'en-US,en',
      ),
    ),
  ),
  'elusim' => 
  array (
    'errors' => 
    array (
      'errorReporting' => 
      array (
        0 => 'E_PARSE',
        1 => 'E_NOTICE',
        2 => 'E_CORE_ERROR',
        3 => 'E_CORE_WARNING',
      ),
    ),
    'lang' => 
    array (
      'dictionaries' => 
      array (
        0 => '@lang.dictionaries.elusim',
      ),
    ),
    'router' => 
    array (
      'error' => 
      array (
        'action' => '\\Elusim\\Actions\\Error',
      ),
    ),
    'errorReportingSilently' => true,
  ),
);