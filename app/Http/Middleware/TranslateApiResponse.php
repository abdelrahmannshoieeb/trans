<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if the `lang` parameter is present
        if ($request->has('lang')) {
            $targetLang = $request->get('lang');

            // Ensure the response is a JSON response
            if ($response instanceof JsonResponse) {
                $data = $response->getData(true); // Get JSON data as an array

                $tr = new GoogleTranslate();
                $tr->setTarget($targetLang);

                // Translate the response data
                $data = $this->translateArray($data, $tr);

                // Set the translated data back to the response
                $response->setData($data);
            }
        }

        return $response;
    }
    private function translateArray(array $data, GoogleTranslate $tr)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = $tr->translate($value);
            } elseif (is_array($value)) {
                $data[$key] = $this->translateArray($value, $tr);
            }
        }
        return $data;
    }
}
