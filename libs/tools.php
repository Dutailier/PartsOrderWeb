<?php

/**
 * Class Tools
 * Gère différentes méthodes utiles dans ce projet.
 */
class Tools
{
    /**
     * Retourne une chaîne de caractères délimitée formée par les proprétés
     * et sous-propriétés du tableau passé en paramètre.
     * @param array $array
     * @param string $delimiter
     * @return string
     */
    public static function getStringDelimited(array $array, $delimiter = '\t')
    {
        // Première itération.
        $string = is_array(current($array)) ?
            self::getStringDelimited(current($array), $delimiter) :
            current($array);

        // Itérations suivantes.
        while (next($array)) {
            $string .= $delimiter . (
                is_array(current($array)) ?
                    self::getStringDelimited(current($array), $delimiter) :
                    current($array)
                );
        }

        return $string;
    }
}