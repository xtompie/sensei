<?php

declare(strict_types=1);

namespace App\Shared\Job\Depot\FileDepot;

use App\Shared\Job\Envelope;
use App\Shared\Job\Priority;
use App\Shared\Kernel\File;
use DateTime;
use DateTimeZone;
use Generator;

class Fetch
{
    public function __construct(
        private Dir $dir,
    ) {
    }

    private function envelope(string $path): ?Envelope
    {
        $serialization = file_get_contents($path);
        $envelope = Envelope::fromSerialization($serialization);
        if (!$envelope) {
            File::write($this->dir->__invoke() . '/critical' . substr($path, strlen($this->dir->__invoke())), $serialization);
            unlink($path);
            return null;
        }

        return $envelope;
    }

    /**
     * @return Generator<Envelope>
     */
    public function __invoke(): Generator
    {
        $max = new DateTime('now', new DateTimeZone('UTC'));
        foreach (Priority::cases() as $priority) {
            foreach ($this->priority($priority->value(), $max) as $path) {
                $envelope = $this->envelope($path);
                if ($envelope) {
                    yield $envelope;
                }
            }
        }
    }

    /**
     * @return Generator<string>
     */
    private function priority(string $priority, DateTime $max): Generator
    {
        $priorityDir = $this->dir->__invoke() . '/' . $priority;
        yield from $this->processYear($priorityDir, $max);
    }

    /**
     * @return Generator<string>
     */
    private function processYear(string $path, DateTime $max): Generator
    {
        $years = $this->getSortedDirectories($path);
        $maxYear = (int)$max->format('Y');
        foreach ($years as $year) {
            $yearInt = (int)$year;
            if ($yearInt > $maxYear) {
                continue;
            }
            yield from $this->processMonth($path . '/' . $year, $yearInt, $max);
        }
    }

    /**
     * @return Generator<string>
     */
    private function processMonth(string $path, int $year, DateTime $max): Generator
    {
        $months = $this->getSortedDirectories($path);
        $maxMonth = (int)$max->format('m');
        foreach ($months as $month) {
            $monthInt = (int)$month;
            if ($year === (int)$max->format('Y') && $monthInt > $maxMonth) {
                continue;
            }
            yield from $this->processDay($path . '/' . $month, $year, $monthInt, $max);
        }
    }

    /**
     * @return Generator<string>
     */
    private function processDay(string $path, int $year, int $month, DateTime $max): Generator
    {
        $days = $this->getSortedDirectories($path);
        $maxDay = (int)$max->format('d');
        foreach ($days as $day) {
            $dayInt = (int)$day;
            if ($year === (int)$max->format('Y') && $month === (int)$max->format('m') && $dayInt > $maxDay) {
                continue;
            }
            yield from $this->processHour($path . '/' . $day, $year, $month, $dayInt, $max);
        }
    }

    /**
     * @return Generator<string>
     */
    private function processHour(string $path, int $year, int $month, int $day, DateTime $max): Generator
    {
        $hours = $this->getSortedDirectories($path);
        $maxHour = (int)$max->format('H');
        foreach ($hours as $hour) {
            $hourInt = (int)$hour;
            if ($year === (int)$max->format('Y') && $month === (int)$max->format('m') && $day === (int)$max->format('d') && $hourInt > $maxHour) {
                continue;
            }
            yield from $this->processMinute($path . '/' . $hour, $year, $month, $day, $hourInt, $max);
        }
    }

    /**
     * @return Generator<string>
     */
    private function processMinute(string $path, int $year, int $month, int $day, int $hour, DateTime $max): Generator
    {
        $minutes = $this->getSortedDirectories($path);
        $maxMinute = (int)$max->format('i');
        foreach ($minutes as $minute) {
            $minuteInt = (int)$minute;
            if ($year === (int)$max->format('Y') && $month === (int)$max->format('m') && $day === (int)$max->format('d') && $hour === (int)$max->format('H') && $minuteInt > $maxMinute) {
                continue;
            }
            yield from $this->processFiles($path . '/' . $minute, $max);
        }
    }

    /**
     * @return Generator<string>
     */
    private function processFiles(string $path, DateTime $max): Generator
    {
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }
                $fullPath = $path . '/' . $entry;
                if (is_file($fullPath)) {
                    $filename = pathinfo($entry, PATHINFO_FILENAME);
                    $parts = explode('_', $filename);
                    if (count($parts) >= 6) {
                        $dateTimeString = implode('_', array_slice($parts, 0, 6));
                        $fileDateTime = DateTime::createFromFormat('Y_m_d_H_i_s', $dateTimeString, new DateTimeZone('UTC'));
                        if ($fileDateTime && $fileDateTime <= $max) {
                            yield $fullPath;
                        }
                    }
                }
            }
            closedir($handle);
        }
    }

    /**
     * @return array<string>
     */
    private function getSortedDirectories(string $path): array
    {
        $dirs = [];
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }
                if (is_dir($path . '/' . $entry)) {
                    $dirs[] = $entry;
                }
            }
            closedir($handle);
            sort($dirs, SORT_NUMERIC);
        }
        return $dirs;
    }
}
