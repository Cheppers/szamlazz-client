<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient;

use Throwable;

class SzamlazzClientException extends \Exception
{
    const REQUEST_TYPE_NOT_EXISTS                = 'A kérés típusa nem létezik';
    const RESPONSE_TYPE_NOT_EXISTS               = 'A válasz típusa nem létezik';
    const RESPONSE_TYPE_NOT_VALID                = 'A válasz típusa nem érvényes';
    const CALL_TYPE_NOT_EXISTS                   = 'Nem létező hívás típus';
    const XML_SCHEMA_TYPE_NOT_EXISTS             = 'Az XML séma típusa nem létezik';
    const XML_KEY_NOT_EXISTS                     = 'XML kulcs nem létezik';
    const XML_NOT_VALID                          = 'Az összeállított XML nem érvényes';
    const XML_DATA_NOT_AVAILABLE                 = 'Hiba történt az XML adatok összeállításánál: nincs adat.';
    const FIELDS_CHECK_ERROR                     = 'Hiba a mezők ellenőrzése közben';
    const CONNECTION_METHOD_CANNOT_BE_DETERMINED = 'A kapcsolódási mód típusa nem meghatározható';
    const DATE_FORMAT_NOT_EXISTS                 = 'Nincs ilyen dátum formátum';
    const NO_AGENT_INSTANCE_WITH_USERNAME        = 'Nincs ilyen felhasználónévvel Számla Agent példányosítva!';
    const NO_SZLAHU_KEY_IN_HEADER                = 'Érvénytelen válasz!';
    const DOCUMENT_DATA_IS_MISSING               = 'A bizonylat PDF adatai hiányoznak!';
    const PDF_FILE_SAVE_SUCCESS                  = 'PDF fájl mentése sikeres';
    const PDF_FILE_SAVE_FAILED                   = 'PDF fájl mentése sikertelen';
    const AGENT_RESPONSE_NO_CONTENT              = 'A Számla Agent válaszában nincs tartalom!';
    const AGENT_RESPONSE_NO_HEADER               = 'A Számla Agent válasza nem tartalmaz fejlécet!';
    const AGENT_RESPONSE_IS_EMPTY                = 'A Számla Agent válasza nem lehet üres!';
    const AGENT_ERROR                            = 'Agent hiba';
    const FILE_CREATION_FAILED                   = 'A fájl létrehozása sikertelen.';
    const TAXPAYER_NOT_EXIST                     = 'Az adófizető nem létezik';
    const INVOICE_GENERATE_FAILED                = 'Számla generálás nem sikerült';
}
