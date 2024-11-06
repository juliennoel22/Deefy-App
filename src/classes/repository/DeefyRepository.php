<?php
namespace iutnc\deefy\repository;

use PDO;
use PDOException;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AudioTrack;

class DeefyRepository {
    // Variable statique pour stocker l'instance de DeefyRepository
    private static ?DeefyRepository $instance = null;

    // Variables pour stocker la configuration et la connexion PDO
    private static array $config = [];
    private ?PDO $pdo = null;

    // Méthode pour charger la configuration à partir d'un fichier .ini
    public static function setConfig(string $file): void {
        self::$config = parse_ini_file($file);
        echo "Configuration chargée</br>";
    }

    // Méthode pour récupérer l'instance de DeefyRepository
    public static function getInstance(): DeefyRepository {
        if (self::$instance === null) {
            self::$instance = new DeefyRepository();
        }
        return self::$instance;
    }

    // Méthode pour obtenir l'instance PDO
    public function getPDO(): PDO {
        if ($this->pdo === null) {
            // Vérification et récupération de la configuration
            $driver = self::$config['driver'] ?? 'mysql';
            $host = self::$config['host'] ?? 'localhost';
            $database = self::$config['database'] ?? '';
            $username = self::$config['username'] ?? '';
            $password = self::$config['password'] ?? '';

            $dsn = "$driver:host=$host;dbname=$database";
            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false
            ];

            try {
                $this->pdo = new PDO($dsn, $username, $password, $options);
                $this->pdo->exec("SET NAMES 'UTF8'");
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        return $this->pdo;
    }

    // TD13 - Exo 3 (méthodes du repository)
    /**
     * Méthode pour récupérer toutes les playlists sans pistes
     * @return array Un tableau d'objets Playlist
     */
    public function findAllPlaylists(): array {
        $pdo = $this->getPDO(); // Utilisation de la méthode getPDO() pour récupérer la connexion
        $query = "SELECT id, nom FROM playlist";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $playlists = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $playlist = new Playlist($row['nom']); // Crée l'objet Playlist sans ID
            $playlist->setId($row['id']);          // Définit l'ID avec le setter
            $playlists[] = $playlist;
        }
        return $playlists;
    }


    /**
     * Méthode pour sauvegarder une playlist vide de pistes
     * @param Playlist $playlist L'objet Playlist à sauvegarder
     * @return bool Retourne true si la playlist a été insérée avec succès, false sinon
     */
    public function saveEmptyPlaylist(Playlist $playlist): bool {
        $pdo = $this->getPDO(); // Utilisation de la méthode getPDO() pour récupérer la connexion

        // Préparation de la requête SQL pour insérer une playlist sans pistes
        $query = "INSERT INTO playlist (nom) VALUES (:nom)";
        $stmt = $pdo->prepare($query);

        // Bind de la valeur 'nom' de la playlist
        $nom = $playlist->getNom();
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);

        try {
            // Exécution de la requête
            $stmt->execute();

            // On récupère l'ID de la playlist insérée
            $playlist->setId($pdo->lastInsertId());


            return true; // Retourne true si l'insertion est réussie
        } catch (PDOException $e) {
            // En cas d'erreur, on retourne false
            echo "Erreur lors de l'insertion de la playlist : " . $e->getMessage();
            return false;
        }
    }

    public function deletePlaylist(int $id): bool {
        $pdo = $this->getPDO(); // Connexion à la base de données
        $query = "DELETE FROM playlist WHERE id = :id";
        $stmt = $pdo->prepare($query);

        // Bind de l'ID à la requête
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête de suppression
        return $stmt->execute();
    }
    /**
     * Sauvegarde une piste dans la base de données
     * @param Track $track L'objet Track à sauvegarder
     * @return bool Retourne true si la piste a été ajoutée avec succès, sinon false
     */
    public function saveTrack(AudioTrack $track): bool {
        // Récupérer la connexion PDO
        $pdo = $this->getPDO();

        // Préparer la requête d'insertion
        $query = "INSERT INTO track (titre, filename, titre_album, numero_album, duree) 
                  VALUES (:titre, :nomFichierAudio, :album, :numeroPiste, :duree)";

        // Préparer la requête
        $stmt = $pdo->prepare($query);

        // Associer les paramètres
        $stmt->bindValue(':titre', $track->__get('titre'), PDO::PARAM_STR);
        $stmt->bindValue(':nomFichierAudio', $track->__get('nomFichierAudio'), PDO::PARAM_STR);
        $stmt->bindValue(':album', $track->__get('album'), PDO::PARAM_STR);
        $stmt->bindValue(':numeroPiste', $track->__get('numeroPiste'), PDO::PARAM_INT);
        $stmt->bindValue(':duree', $track->__get('duree'), PDO::PARAM_INT);

        // Exécuter la requête et vérifier si l'insertion a réussi
        try {
            $stmt->execute();
            return true;  // Retourne true si la piste a été ajoutée avec succès
        } catch (PDOException $e) {
            // En cas d'erreur, afficher un message et retourner false
            echo "Erreur lors de l'insertion de la piste : " . $e->getMessage();
            return false;
        }
    }

    /**
     * Ajoute une piste existante à la fin d'une playlist existante.
     * @param int $idTrack L'ID de la piste à ajouter.
     * @param int $idPlaylist L'ID de la playlist à laquelle ajouter la piste.
     * @return bool Retourne true si l'ajout a réussi, sinon false.
     */
    public function addExistingTrackToEndOfPlaylist(int $idTrack, int $idPlaylist): bool {
        // Récupérer la connexion PDO
        $pdo = $this->getPDO();

        // Récupérer le dernier numéro de piste dans cette playlist pour savoir où ajouter la nouvelle
        $query = "SELECT MAX(no_piste_dans_liste) AS last_position 
              FROM playlist2track 
              WHERE id_pl = :idPlaylist";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':idPlaylist', $idPlaylist, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nextPosition = $result['last_position'] + 1;  // La position de la nouvelle piste est la suivante

            // Insérer la relation entre la playlist et la piste existante dans la table `playlist2track`
            $queryInsertPlaylistTrack = "INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) 
                                     VALUES (:idPlaylist, :idTrack, :noPiste)";

            $stmtInsertPlaylistTrack = $pdo->prepare($queryInsertPlaylistTrack);
            $stmtInsertPlaylistTrack->bindValue(':idPlaylist', $idPlaylist, PDO::PARAM_INT);
            $stmtInsertPlaylistTrack->bindValue(':idTrack', $idTrack, PDO::PARAM_INT);
            $stmtInsertPlaylistTrack->bindValue(':noPiste', $nextPosition, PDO::PARAM_INT);

            $stmtInsertPlaylistTrack->execute();
            return true; // Retourne true si l'ajout a réussi

        } catch (PDOException $e) {
            // En cas d'erreur, afficher un message et retourner false
            echo "Erreur lors de l'ajout de la piste à la playlist : " . $e->getMessage();
            return false;
        }
    }




}