<?php

namespace App\Http\Middleware;

use App\Services\Mobile\MobileApiAuditLogger;
use App\Support\MobileApiResponse;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class MobileApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('mobile_request_id', (string) Str::uuid());

        try {
            $response = $next($request);
        } catch (AuthenticationException $exception) {
            $response = MobileApiResponse::error('Unauthorized', [], 401);
        } catch (AuthorizationException $exception) {
            $response = MobileApiResponse::error($exception->getMessage() ?: 'Forbidden', [], 403);
        } catch (ValidationException $exception) {
            $response = MobileApiResponse::error(
                'Validasi gagal.',
                $exception->errors(),
                422
            );
        } catch (ModelNotFoundException $exception) {
            $response = MobileApiResponse::error('Data tidak ditemukan.', [], 404);
        } catch (InvalidArgumentException $exception) {
            $response = MobileApiResponse::error($exception->getMessage(), [], 422);
        } catch (HttpExceptionInterface $exception) {
            $response = MobileApiResponse::error(
                $exception->getMessage() ?: Response::$statusTexts[$exception->getStatusCode()] ?? 'Request gagal.',
                [],
                $exception->getStatusCode()
            );
        } catch (Throwable $exception) {
            report($exception);

            $response = MobileApiResponse::error('Terjadi kesalahan server.', [], 500);
        }

        $response->headers->set('X-Mobile-Request-Id', $request->attributes->get('mobile_request_id'));

        app(MobileApiAuditLogger::class)->log($request, $response);

        return $response;
    }
}
