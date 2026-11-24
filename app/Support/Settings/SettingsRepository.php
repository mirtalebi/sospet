<?php

namespace App\Support\Settings;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SettingsRepository
{
    private const CACHE_KEY = 'sospet.settings';

    /** @var array<string, mixed>|null */
    private ?array $values = null;

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        if ($this->values !== null) {
            return $this->values;
        }

        if (! Schema::hasTable('settings')) {
            return $this->values = [];
        }

        return $this->values = Cache::rememberForever(self::CACHE_KEY, function (): array {
            return DB::table('settings')->get()->mapWithKeys(function (object $row): array {
                return [$row->key => json_decode((string) $row->value, true)];
            })->all();
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->setMany([$key => $value]);
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public function setMany(array $values): void
    {
        $now = now();

        $rows = collect($values)->map(fn (mixed $value, string $key): array => [
            'key' => $key,
            'value' => json_encode($value, JSON_UNESCAPED_UNICODE),
            'created_at' => $now,
            'updated_at' => $now,
        ])->values()->all();

        DB::table('settings')->upsert($rows, ['key'], ['value', 'updated_at']);

        $this->flush();
    }

    public function forget(string $key): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        DB::table('settings')->where('key', $key)->delete();

        $this->flush();
    }

    public function flush(): void
    {
        $this->values = null;

        Cache::forget(self::CACHE_KEY);
        Cache::forever('sospet.sitemap.version', $this->sitemapVersion() + 1);
    }

    public function sitemapVersion(): int
    {
        return (int) Cache::get('sospet.sitemap.version', 1);
    }
}
