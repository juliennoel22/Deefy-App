<?php
namespace iutnc\deefy\action;

class DisplayPlaylistAction extends Action
{
    public function execute(): string
    {
        session_start();

        if (isset($_SESSION['playlist']) && !empty($_SESSION['playlist'])) {
            $output = "Playlist actuelle :<br>";
            foreach ($_SESSION['playlist'] as $index => $track) {
                $output .= ($index + 1) . ". " . htmlspecialchars($track['titre']) . " - " . htmlspecialchars($track['artiste']) . "<br>";
            }
            return $output;
        } else {
            return "La playlist est vide ou n'a pas été initialisée.";
        }
    }
}
