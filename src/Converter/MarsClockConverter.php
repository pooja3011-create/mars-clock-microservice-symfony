<?php
declare(strict_types = 1);

namespace App\Converter;

class MarsClockConverter
{
    /**
     * @reference https://en.wikipedia.org/wiki/Leap_second
     */
    private const CURRENT_LEAP_SECONDS = 37;
    
    /**
     * @ 'MARS_EARTH_COM_LATENCY_SECONDS" please check the attached explanation PDF file for further details 
     */
    private const MARS_EARTH_COM_LATENCY_SECONDS = 751;
    
    private const ELECTRONICS_LATENCY_SECONDS = 5;
    
    private $earthDate;

    public function __construct(\DateTime $earthDate)
    {
        $this->earthDate = $earthDate;
    }

    /**
     * @reference https://en.wikipedia.org/wiki/Timekeeping_on_Mars#Formulas_to_compute_MSD_and_MTC
     */
    public function getWithoutComLatencyMarsSolDate(): float
    {
        $seconds = $this->earthDate->getTimestamp();
        /* @expliantion
         * JulianDateTT -> 01.01.4713 <---- 2440587.5 days -----> 01.01.1970 <----- ($seconds / 86400) days ----->Today
         */
        $julianDateUT = 2440587.5 + ($seconds / 86400);
        $julianDateTT = $julianDateUT + (self::CURRENT_LEAP_SECONDS + 32.184) / 86400;
        
        /* @expliantion
         * MSD = (01.01.4713 <-----(JulianDateTT)Days-----> Today) - (01.01.4713 <-----2405522.0028779 days----->29.12.1873) / 1.0274912517 (Earth Day/Mars Sol)
         */
        return ($julianDateTT - 2405522.0028779) / 1.0274912517;
        
    }

    public function getWithComLatencyMarsSolDate(): float
    {
        $seconds = $this->earthDate->getTimestamp();
        $julianDateUT = 2440587.5 + ($seconds / 86400);
        $julianDateTT = $julianDateUT + (self::CURRENT_LEAP_SECONDS + self::MARS_EARTH_COM_LATENCY_SECONDS + self::ELECTRONICS_LATENCY_SECONDS + 32.184) / 86400;
        return ($julianDateTT - 2405522.0028779) / 1.0274912517;
    }
    
    public function getWithoutComLatencyMartianCoordinatedTime(): string
    {
        $marsSolDate = $this->getWithoutComLatencyMarsSolDate();
        $martianHours = fmod((24 * $marsSolDate), 24);
        return gmdate("H:i:s", (int) floor($martianHours * 3600));
    }
    
    public function getWithComLatencyMartianCoordinatedTime(): string
    {
        $marsSolDate = $this->getWithComLatencyMarsSolDate();
        $martianHours = fmod((24 * $marsSolDate), 24);
        return gmdate("H:i:s", (int) floor($martianHours * 3600));
    }
}
