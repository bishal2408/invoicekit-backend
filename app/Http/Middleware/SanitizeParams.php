<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeParams
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validOrderDirection = ['asc', 'desc'];
        $requestOrderByDirection = $request->get('order_by_dir');
        $requestPage = (int) $request->get('page');
        $requestPerPage = (int) $request->get('per_page');

        // set default order by direction
        if (! in_array(strtolower($requestOrderByDirection), $validOrderDirection)) {
            $requestOrderByDirection = 'asc';
        }

        // if empty; default to 1
        if (empty($requestPage)) {
            $requestPage = 1;
        }

        // if negative; default to 0
        if ($requestPerPage < 0) {
            $requestPerPage = 0;
        }

        // overwrite with defaults
        $request->merge([
            'order_by_dir' => $requestOrderByDirection,
            'page' => $requestPage,
            'per_page' => (int) $requestPerPage,
        ]);

        return $next($request);
    }
}
