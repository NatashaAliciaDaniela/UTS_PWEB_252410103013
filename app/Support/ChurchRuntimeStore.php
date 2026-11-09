<?php

namespace App\Support;

use RuntimeException;

class ChurchRuntimeStore
{
    private static function path(): string
    {
        return storage_path('framework/church-runtime.json');
    }

    public static function get(string $key): array
    {
        return self::access(function (array &$data) use ($key): array {
            $value = $data[$key] ?? [];
            if (! is_array($value)) {
                throw new RuntimeException("Invalid temporary church data for {$key}.");
            }

            return $value;
        });
    }

    public static function append(string $key, array $row): void
    {
        self::access(function (array &$data) use ($key, $row): void {
            $data[$key] ??= [];
            if (! is_array($data[$key])) {
                throw new RuntimeException("Invalid temporary church data for {$key}.");
            }

            $data[$key][] = $row;
        });
    }

    public static function attendance(): array
    {
        return self::access(function (array &$data): array {
            $attendance = $data['attendance'] ?? [];
            if (! is_array($attendance)) {
                throw new RuntimeException('Invalid temporary church attendance data.');
            }

            return $attendance;
        });
    }

    public static function setAttendance(array $attendance): void
    {
        self::access(function (array &$data) use ($attendance): void {
            $data['attendance'] = array_merge($data['attendance'] ?? [], $attendance);
        });
    }

    public static function reset(): void
    {
        self::access(function (array &$data): void {
            $data = [];
        }, true);
    }

    private static function access(callable $callback, bool $reset = false): mixed
    {
        $path = self::path();
        $directory = dirname($path);

        if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            throw new RuntimeException('Unable to create the temporary church data directory.');
        }

        $handle = fopen($path, 'c+');
        if ($handle === false) {
            throw new RuntimeException('Unable to open temporary church data storage.');
        }

        try {
            if (! flock($handle, LOCK_EX)) {
                throw new RuntimeException('Unable to lock temporary church data storage.');
            }

            rewind($handle);
            $contents = stream_get_contents($handle);
            if ($contents === false) {
                throw new RuntimeException('Unable to read temporary church data storage.');
            }

            $decoded = $contents === '' ? null : json_decode($contents, true);
            if ($contents !== '' && ! is_array($decoded)) {
                throw new RuntimeException('Temporary church data storage contains invalid JSON.');
            }

            if ($reset || ($decoded['process_id'] ?? null) !== getmypid()) {
                $decoded = ['process_id' => getmypid(), 'data' => []];
            }

            $data = $decoded['data'] ?? [];
            if (! is_array($data)) {
                throw new RuntimeException('Temporary church data storage has an invalid structure.');
            }

            $result = $callback($data);
            $encoded = json_encode(
                ['process_id' => getmypid(), 'data' => $data],
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
            );

            rewind($handle);
            if (! ftruncate($handle, 0) || fwrite($handle, $encoded) === false || ! fflush($handle)) {
                throw new RuntimeException('Unable to write temporary church data storage.');
            }

            flock($handle, LOCK_UN);

            return $result;
        } finally {
            fclose($handle);
        }
    }
}
