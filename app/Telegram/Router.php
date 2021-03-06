<?php

namespace App\Telegram;

use App\Model\Match;

/**
 * Trait Router
 * @package App\Telegram
 */
trait Router
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * @param $pattern
     * @param $callable
     * @param array $options
     */
    public function add($pattern, $callable, $options = [])
    {
        $pattern = '/' . str_replace('/', '\/', $pattern) . '$/';
        $this->register($pattern, $callable, $options);
    }

    /**
     * @param $pattern
     * @param $callable
     * @param $options
     */
    private function register($pattern, $callable, $options)
    {
        $this->routes[$pattern] = array_merge([
            '$pattern' => $pattern,
            '$callable' => $callable,
        ], $options);
    }

    /**
     * @param array $message
     * @return Match|null
     */
    public function match($message)
    {
        $subject = $message['text'];
        // search by $match
        foreach ($this->routes as $pattern => $candidate) {
            if (preg_match($pattern, $subject, $parameters)) {
                array_shift($parameters);
                $properties = array_merge(
                    ['$parameters' => $parameters, '$message' => $message],
                    $candidate
                );
                return Match::make($properties);
            }
        }
        return null;
    }
}
