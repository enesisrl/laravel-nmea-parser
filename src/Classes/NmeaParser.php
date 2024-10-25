<?php

namespace Enesisrl\LaravelNmeaParser\Classes;

class NmeaParser
{
    /**
     * Estrae i dati GPS dalla stringa NMEA.
     *
     * @param string $nmeaString
     * @return array
     */
    public function parse(string $nmeaString): array
    {
        $data = [
            'date' => null,
            'time' => null,
            'latitude' => null,
            'longitude' => null
        ];

        $lines = explode("\n", $nmeaString);

        foreach ($lines as $line) {
            if (strpos($line, '$GPRMC') !== false) {
                $this->parseGprmc($line, $data);
            } elseif (strpos($line, '$GPGGA') !== false) {
                $this->parseGpgga($line, $data);
            }
        }

        return $data;
    }

    /**
     * Parser per le stringhe GPRMC.
     *
     * @param string $line
     * @param array $data
     */
    private function parseGprmc(string $line, array &$data): void
    {
        $parts = explode(',', $line);

        if (count($parts) >= 10) {
            // Ora
            $data['time'] = $this->parseTime($parts[1]);

            // Data
            $data['date'] = $this->parseDate($parts[9]);

            // Latitudine e longitudine
            $data['latitude'] = $this->parseCoordinate($parts[3], $parts[4]);
            $data['longitude'] = $this->parseCoordinate($parts[5], $parts[6]);
        }
    }

    /**
     * Parser per le stringhe GPGGA.
     *
     * @param string $line
     * @param array $data
     */
    private function parseGpgga(string $line, array &$data): void
    {
        $parts = explode(',', $line);

        if (count($parts) >= 6) {
            $data['latitude'] = $this->parseCoordinate($parts[2], $parts[3]);
            $data['longitude'] = $this->parseCoordinate($parts[4], $parts[5]);
        }
    }

    /**
     * Parse time in formato HHMMSS.
     *
     * @param string $timeString
     * @return string|null
     */
    private function parseTime(string $timeString): ?string
    {
        if (strlen($timeString) >= 6) {
            $hours = substr($timeString, 0, 2);
            $minutes = substr($timeString, 2, 2);
            $seconds = substr($timeString, 4, 2);
            return "$hours:$minutes:$seconds";
        }
        return null;
    }

    /**
     * Parse date in formato DDMMYY.
     *
     * @param string $dateString
     * @return string|null
     */
    private function parseDate(string $dateString): ?string
    {
        if (strlen($dateString) === 6) {
            $day = substr($dateString, 0, 2);
            $month = substr($dateString, 2, 2);
            $year = '20' . substr($dateString, 4, 2);
            return Carbon::createFromDate($year, $month, $day)->toDateString();
        }
        return null;
    }

    /**
     * Parse della coordinata NMEA.
     *
     * @param string $coordinateString
     * @param string $direction
     * @return float|null
     */
    private function parseCoordinate(string $coordinateString, string $direction): ?float
    {
        if (empty($coordinateString)) {
            return null;
        }

        $degrees = (int)substr($coordinateString, 0, 2);
        $minutes = (float)substr($coordinateString, 2) / 60.0;
        $coordinate = $degrees + $minutes;

        return ($direction === 'S' || $direction === 'W') ? -$coordinate : $coordinate;
    }

}