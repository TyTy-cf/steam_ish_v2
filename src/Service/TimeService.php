<?php


namespace App\Service;


/**
 * Class TimeService.php
 *
 * @author Kevin Tourret
 */
class TimeService
{

    /**
     * Converti l'attribut gameTime (qui est en seconde)
     *
     * @param int $gameTime
     * @return string
     */
    public function getTimeConverter(int $gameTime): string {
        $hours = floor($gameTime / 3600);
        $minutes = ($gameTime % 60);
        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }
        return $hours. 'h' . $minutes;
    }

}
