<?php

/**
 * Class Document
 * Représente un document.
 */
class Document
{
    /**
     * Retourne le contenu d'un document avec variables passées.
     * @param $path
     * @param array $parameters
     * @return string
     * @throws Exception
     */
    public static function getContents($path, array $parameters)
    {
        @ob_start();

        if (!file_exists($path)) {
            throw new Exception('The document doesn\'t exists.');
        } else {
            @extract($parameters);
            include_once($path);
        }

        $document = @ob_get_contents();
        @ob_end_clean();

        return $document;
    }
}