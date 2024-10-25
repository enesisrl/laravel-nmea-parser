<?php

namespace Enesisrl\LaravelNmeaParser\Classes;

class NmeaParser
{
    /**
     * Extract latitude and longitude from a NMEA string.
     *
     * @param string $nmeaString
     * @return array Associative array with 'latitude' and 'longitude' in decimal format
     * @throws \Exception
     */
    public function parseCoordinates(string $nmeaString): array
    {
        $segments = explode(',', $nmeaString);

        if (count($segments) < 6) {
            throw new \Exception("NMEA not valid.");
        }

        $latitude = $this->nmeaToDecimal($segments[2], $segments[3]);
        $longitude = $this->nmeaToDecimal($segments[4], $segments[5]);

        return [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }

    /**
     * Convert coordinates NMEA in decimal format.
     *
     * @param string $coordinate
     * @param string $direction
     * @return float
     */
    private function nmeaToDecimal(string $coordinate, string $direction): float
    {
        $degrees = (float)(substr($coordinate, 0, 2));
        $minutes = (float)(substr($coordinate, 2));

        $decimal = $degrees + ($minutes / 60);

        // Adjust for hemisphere
        if ($direction === 'S' || $direction === 'W') {
            $decimal *= -1;
        }

        return $decimal;
    }
}