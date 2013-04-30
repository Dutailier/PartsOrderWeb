<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');

class User
{
    /**
     * Tente de connecté l'utilisateur.
     * Retourne vrai si la connexion réussie.
     * @param $username
     * @param $password
     * @return bool
     * @throws Exception
     */
    public static function TryLogin($username, $password)
    {
        // Traitement du nom d'utilisateur et du mot de passe.
        $username = strtolower($username);
        $password = sha1($password . $username);

        // Récupère la connexion à la base de données.
        $conn = database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[tryLogin]("' . $username . '", "' . $password . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                // Récupère la première ligne résultante.
                $row = odbc_fetch_row($result);

                if (empty($row)) {
                    throw new Exception('Username or password incorrect.');
                } else {

                    // Démarre une session si celle-ci n'est pas déjà active.
                    if (!isset($_SESSION)) {
                        session_start();
                    }

                    $_SESSION['user']['id'] = $row['id'];
                    $_SESSION['authenticated'] = true;

                    return true;
                }
            }
        }
    }

    /**
     * Retourne vrai si l'utilisateur s'est authentifié.
     * @return bool
     */
    public static function isAuthenticated()
    {
        // Démarre la session si cela n'est pas déjà fait.
        if (!isset($_SESSION)) {
            session_start();
        }

        return !(empty($_SESSION['authenticated']));
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public static function Logout()
    {

        // Démarre la session si cela n'est pas déjà fait.
        if (!$_SESSION) {
            session_start();
        }

        // Détruit la session en cours.
        session_destroy();

        // Redirige l'utilisateur à la page de connexion.
        header('location: index.php');
    }
}