<?php

namespace App\Console\Commands;

use App\Models\ApiClient;
use App\Services\DeveloperPortal\ApiClientTokenService;
use Illuminate\Console\Command;

class RotateApiClientTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rotate-api-client-token {client : API client id or client_code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revoke active tokens and generate a new API client token.';

    /**
     * Execute the console command.
     */
    public function handle(ApiClientTokenService $tokens): int
    {
        $clientIdentifier = $this->argument('client');
        $client = ApiClient::query()
            ->where('client_code', $clientIdentifier)
            ->orWhere('id', $clientIdentifier)
            ->first();

        if (! $client) {
            $this->error('API client not found.');

            return self::FAILURE;
        }

        [$plainToken] = $tokens->rotate($client);
        $this->warn('Copy this token now. It will not be shown again.');
        $this->line($plainToken);

        return self::SUCCESS;
    }
}
