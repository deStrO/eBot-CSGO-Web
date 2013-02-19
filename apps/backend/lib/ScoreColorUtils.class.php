<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ScoreColorUtils
 *
 * @author deStrO
 */
class ScoreColorUtils {

    /**
     * Colorise selon le side de l'équipe en bleu ou rouge
     * @param string $side ct ou t
     * @param int $score1
     * @param int $score2
     */
    public static function colorForMaps($side, &$score1, &$score2) {
        if (is_numeric($score1) && is_numeric($score2))
            self::addZeroToScore($score1, $score2);

        if ($side == "ct") {
            $score1 = "<font color=\"blue\">$score1</font>";
            $score2 = "<font color=\"red\">$score2</font>";
        } else {
            $score2 = "<font color=\"blue\">$score2</font>";
            $score1 = "<font color=\"red\">$score1</font>";
        }
    }

    /**
     * Colorise les scores si besoin avec les couleurs rouge/vert/bleu
     * @param int $score1
     * @param int $score2
     * @param bool $colorize
     */
    public static function colorForScore(&$score1, &$score2, $colorize = true) {
        self::addZeroToScore($score1, $score2);

        if (!$colorize)
            return;

        if (($score1 == $score2)) {
            $score1 = "<font color=\"blue\">$score1</font>";
            $score2 = "<font color=\"blue\">$score2</font>";
        } elseif ($score1 > $score2) {
            $score1 = "<font color=\"green\">$score1</font>";
            $score2 = "<font color=\"red\">$score2</font>";
        } else {
            $score1 = "<font color=\"red\">$score1</font>";
            $score2 = "<font color=\"green\">$score2</font>";
        }
    }

    /**
     * Change les scores inférieures à 10 pour mettre au format xx
     * @param string $score1
     * @param string $score2
     */
    public static function addZeroToScore(&$score1, &$score2) {
        if ($score1 < 10)
            $score1 = "0" . $score1;
        if ($score2 < 10)
            $score2 = "0" . $score2;
    }

}

?>
