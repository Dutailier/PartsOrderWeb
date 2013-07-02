<?php

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=orders.xls");

include_once('config.php');
include_once(ROOT . 'libs/tools.php');
include_once(ROOT . 'libs/repositories/orders.php');

if (!empty($_GET['from']) &&
    !empty($_GET['to'])
) {
    $rows = Orders::FilterByRangeOfDates(
        strtotime($_GET['from']),
        strtotime($_GET['to']));

    xlsBOF();

    // Initialise la ligne des titres
    xlsWriteLabel(0, 0, 'id');
    xlsWriteLabel(0, 1, 'storeId');
    xlsWriteLabel(0, 2, 'receiverId');
    xlsWriteLabel(0, 3, 'shippingAddressId');
    xlsWriteLabel(0, 4, 'number');
    xlsWriteLabel(0, 5, 'creationDate');
    xlsWriteLabel(0, 6, 'lastModificationByUsername');
    xlsWriteLabel(0, 7, 'lastModificationDate');
    xlsWriteLabel(0, 8, 'status');

    $r = 0;
    do {
        $c = 0;
        $columns = current($rows)->getArray();
        do {
            xlsWriteLabel($r, $c, current($columns));
            $c++;
        } while (next($columns));
        $r++;
    } while (next($rows));

    xlsEOF();
}

/**
 * Initialise le début du fichier XLS.
 */
function xlsBOF()
{
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
}

/**
 * Complète le fichier XLS.
 */
function xlsEOF()
{
    echo pack("ss", 0x0A, 0x00);
}

/**
 * Écris un texte dans une cellule.
 * @param $row
 * @param $col
 * @param $value
 */
function xlsWriteLabel($row, $col, $value)
{
    $L = strlen($value);
    echo pack("ssssss", 0x204, 8 + $L, $row, $col, 0x0, $L);
    echo $value;
}

