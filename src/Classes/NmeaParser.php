<?php

namespace Enesisrl\LaravelNmeaParser\Classes;

class NmeaParser
{
    /**
     * Analizza una stringa NMEA e restituisce tutti i dati disponibili.
     *
     * @param string $nmeaString
     * @return array Associative array containing all data fields
     * @throws \Exception
     */
    public function parseAllData(string $nmeaString): array
    {
        // Split string into parts
        $segments = explode(',', $nmeaString);

        // Check if the message is valid
        if (count($segments) < 15) {
            throw new \Exception("Stringa NMEA non valida o incompleta.");
        }

        // Extract and convert all necessary data
        return [
            'time' => $this->parseTime($segments[1]), // Ora UTC
            'latitude' => $this->nmeaToDecimal($segments[2], $segments[3]), // Latitudine in decimale
            'longitude' => $this->nmeaToDecimal($segments[4], $segments[5]), // Longitudine in decimale
            'fix_quality' => (int)$segments[6], // Qualità del segnale GPS
            'satellites' => (int)$segments[7], // Numero di satelliti
            'horizontal_dilution' => (float)$segments[8], // Precisione orizzontale
            'altitude' => (float)$segments[9], // Altitudine in metri
            'height_geoid' => (float)$segments[11] // Altezza sopra il geoid
        ];
    }

    /**
     * Converte coordinate NMEA in formato decimale.
     *
     * @param string $coordinate
     * @param string $direction
     * @return float
     */
    private function nmeaToDecimal(string $coordinate, string $direction): float
    {
        $degrees = substr($coordinate, 0, 2);
        $minutes = substr($coordinate, 2);

        $decimal = $degrees + ($minutes / 60);

        if ($direction === 'S' || $direction === 'W') {
            $decimal *= -1;
        }

        return $decimal;
    }

    /**
     * Converte il campo orario NMEA in formato leggibile (HH:MM:SS).
     *
     * @param string $time
     * @return string
     */
    private function parseTime(string $time): string
    {
        $hours = substr($time, 0, 2);
        $minutes = substr($time, 2, 2);
        $seconds = substr($time, 4, 2);

        return "$hours:$minutes:$seconds";
    }

}