<?php

namespace App\Console\Commands;

use App\Services\System\SystemHealthService;
use Illuminate\Console\Command;

class SystemHealthCheckCommand extends Command
{
    protected $signature = 'app:system-health-check';

    protected $description = 'Run production readiness health checks.';

    public function handle(SystemHealthService $systemHealthService): int
    {
        $checks = $systemHealthService->check();

        foreach ($checks as $name => $result) {
            $status = ($result['ok'] ?? false) ? 'OK' : 'FAILED';

            $this->line(strtoupper($name).': '.$status);

            foreach ($result as $key => $value) {
                if ($key === 'ok') {
                    continue;
                }

                $this->line('  - '.$key.': '.$this->stringify($value));
            }
        }

        return $systemHealthService->isHealthy()
            ? self::SUCCESS
            : self::FAILURE;
    }

    private function stringify(mixed $value): string
    {
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value === null) {
            return 'null';
        }

        return (string) $value;
    }
}
