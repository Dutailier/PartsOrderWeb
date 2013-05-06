<?php

include_once('config.php');

/**
 * Class File
 * Méthodes relatives aux manipulations de fichiers.
 */
class File
{
    /**
     * Permet d'écrire à la fin d'un fichier.
     * @param $path
     * @param $text
     * @return bool
     * @throws Exception
     */
    public static function WriteAtTheEnd($path, $text)
    {
        if (!is_writable($path)) {
            throw new Exception('The file must be writable.');

        } else {
            if (!$handle = fopen($path, 'a')) {
                throw new Exception('Cannot open file: ' . $path);

            } else {
                if (!fwrite($handle, $text)) {
                    throw new Exception('Written in the file is impossible.');
                }

                return fclose($path);
            }
        }
    }

    /**
     * Permet de créé et d'écrire dans un fichier.
     * @param $path
     * @param $text
     * @return bool
     * @throws Exception
     */
    public static function CreateAndWrite($path, $text)
    {
        if (!$handle = fopen($path, 'w')) {
            throw new Exception('Cannot open file: ' . $path);
        } else {
            if (!fwrite($handle, $text)) {
                throw new Exception('Written in the file is impossible.');
            }

            return fclose($path);
        }
    }
}