<?php

namespace AppBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * Class DateIntervalPostgresType
 */
class DateIntervalPostgresType extends Type
{
    const DATE_INTERVAL_POSTGRES = 'date_interval_postgres';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTimeTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        /** @var $value \DateInterval */
        if ($value === null) {
            return $value;
        }
        $result = '';
        if ($value->y) {
            $result .= $value->y.'Y';
        }
        if ($value->m) {
            $result .= $value->m.'M';
        }
        if ($value->d) {
            $result .= $value->d.'D';
        }
        if ($value->h || $value->i || $value->s) {
            $result .= 'T';
        }
        if ($value->h) {
            $result .= $value->h.'H';
        }
        if ($value->i) {
            $result .= $value->i.'M';
        }
        if ($value->s) {
            $result .= $value->s.'S';
        }
        if ($result) {
            $result = 'P'.$result;
        }

        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof \DateInterval) {
            return $value;
        }
        $matches = [];
        $years = preg_match('/(\d+) year(s)?/', $value, $matches) ? $matches[1] : 0;
        $months = preg_match('/(\d+) mon(s)?/', $value, $matches) ? $matches[1] : 0;
        $days = preg_match('/(\d+) day(s)?/', $value, $matches) ? $matches[1] : 0;
        list($hours, $minutes, $seconds) = preg_match('/(\d{2}):(\d{2}):(\d{2})/', $value, $matches) ? [intval($matches[1]), intval($matches[2]), intval($matches[3])] : [0, 0, 0];
        $interval = '';
        if ($years) {
            $interval .= $years.'Y';
        }
        if ($months) {
            $interval .= $months.'M';
        }
        if ($days) {
            $interval .= $days.'D';
        }
        if ($hours || $minutes || $seconds) {
            $interval .= 'T';
        }
        if ($hours) {
            $interval .= $hours.'H';
        }
        if ($minutes) {
            $interval .= $minutes.'M';
        }
        if ($seconds) {
            $interval .= $seconds.'S';
        }
        if ($interval) {
            $interval = 'P'.$interval;
        }
        try {
            return new \DateInterval($interval);
        } catch (\Exception $e) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                'Postgres format'
            );
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::DATE_INTERVAL_POSTGRES;
    }
}
