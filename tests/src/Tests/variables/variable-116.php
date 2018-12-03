<?php return array (
  'response' => 
  array (
    'headers' => 
    array (
      'Access-Control-Allow-Origin' => '*',
    ),
    'engines' => 
    array (
      'application/json' => 'json',
    ),
    'contentType' => 
    array (
      'negotiation' => 1,
    ),
    'lang' => 
    array (
      'negotiation' => 1,
    ),
  ),
  'elusim' => 
  array (
    'errors' => 
    array (
      'errorReporting' => 
      array (
        0 => 'E_ERROR,',
        1 => 'E_WARNING,',
        2 => 'E_PARSE,',
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
  ),
);