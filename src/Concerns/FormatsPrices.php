<?php

namespace Whitecube\Price\Concerns;

use Whitecube\Price\Formatting\Formatter;
use Whitecube\Price\Formatting\CustomFormatter;

trait FormatsPrices
{
    /**
     * The defined custom formatters.
     *
     * @var array
     */
    static protected $formatters = [];

    /**
     * Formats the given monetary value into the application's currently preferred format.
     *
     * @param array $arguments
     * @return string
     */
    static public function format(...$arguments)
    {
        return static::callFormatter(null, ...$arguments);
    }

    /**
     * Formats the given monetary value into the application's currently preferred format.
     *
     * @param null|string $name
     * @param array $arguments
     * @return string
     */
    static protected function callFormatter($name, ...$arguments)
    {
        return static::getAssignedFormatter($name)->call($arguments);
    }

    /**
     * Formats the given monetary value using the package's default formatter.
     * This static method is hardcoded in order to prevent overwriting.
     *
     * @param string $value
     * @param null|string $locale
     * @return string
     */
    static public function formatDefault($value, $locale = null)
    {
        return static::getDefaultFormatter()->call([$value, $locale]);
    }

    /**
     * Formats the given monetary value using the package's default formatter.
     * This static method is hardcoded in order to prevent overwriting.
     *
     * @param mixed $formatter
     * @return \Whitecube\Price\CustomFormatter
     */
    static public function formatUsing($formatter) : CustomFormatter
    {
        if(is_string($formatter) && is_a($formatter, CustomFormatter::class, true)) {
            $instance = new $formatter;
        } elseif (is_a($formatter, CustomFormatter::class)) {
            $instance = $formatter;
        } elseif (is_callable($formatter)) {
            $instance = new CustomFormatter($formatter);
        } else {
            throw new \InvalidArgumentException('Price formatter should be callable or extend "\\Whitecube\\Price\\CustomFormatter". "' . gettype($formatter) . '" provided.');
        }

        static::$formatters[] = $instance;

        return $instance;
    }

    /**
     * Returns the correct formatter for the requested context
     *
     * @param null|string $name
     * @return \Whitecube\Price\Formatter
     */
    static protected function getAssignedFormatter($name = null) : Formatter
    {
        foreach (static::$formatters as $formatter) {
            if ($formatter->is($name)) return $formatter;
        }

        return static::getDefaultFormatter();
    }

    /**
     * Returns the package's default formatter
     *
     * @return \Whitecube\Price\Formatter
     */
    static protected function getDefaultFormatter() : Formatter
    {
        return new Formatter();
    }

    /**
     * Unsets all the previously defined custom formatters.
     *
     * @return void
     */
    static public function forgetAllFormatters()
    {
        static::$formatters = [];
    }
}
