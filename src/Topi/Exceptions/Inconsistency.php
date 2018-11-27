<?php
namespace Elusim\Exceptions;

/**
 * Wyjątek powinień być, wyrzucany, w momencie gdy dane nie są spójne. Np. jest
 * odwłowanie do użytkownika, który nie istnieje. W takiej sytuacji API zwraca
 * kod 404, że nie odnaleziono zasobu.
 */
class Inconsistency extends \Exception {}
