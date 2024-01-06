<?php

namespace console\controllers;

use yii\helpers\Inflector;
use yii\console\Controller;
use yii\helpers\Console;


abstract class BaseConsoleController extends Controller
{

    private $aliases = [];


    public function init()
    {
        parent::init();

        if (function_exists('pcntl_signal')) {
            declare(ticks = 1);
            pcntl_signal(SIGINT, [$this, 'signalHandler']);
        }
    }

    /**
     * @param string $actionID
     *
     * @return string[]
     */
    public function options($actionID)
    {
        $actionOptionsAliases = 'optionAliases' . ucfirst($actionID);
        if (method_exists($this, $actionOptionsAliases)) {
            $this->aliases = $this->$actionOptionsAliases();
        }

        $actionOptionsAliases = 'optionAliases' . Inflector::id2camel($actionID);
        if (method_exists($this, $actionOptionsAliases)) {
            $this->aliases = $this->$actionOptionsAliases();
        }

        $actionOptions = 'options' . ucfirst($actionID);
        if (method_exists($this, $actionOptions)) {
            return array_merge($this->$actionOptions(), parent::options($actionID));
        }

        $actionOptions = 'options' . Inflector::id2camel($actionID);
        if (method_exists($this, $actionOptions)) {
            return array_merge($this->$actionOptions(), parent::options($actionID));
        }

        return parent::options($actionID);
    }

    /**
     * @return array
     */
    public function optionAliases()
    {
        return $this->aliases ? : parent::optionAliases();
    }


    protected function signalHandler($signal)
    {
        Console::output($this->ansiFormat('Execution terminated.', Console::FG_RED));

        exit($signal);
    }

    /**
     * @param string $question A question that can be answered yes / no.
     * @param bool $default
     * @param bool $color Example: Console::FG_RED
     *
     * @return bool
     */
    protected function askQuestion(string $question, bool $default = false, $color = false): bool
    {
        if ($color) {
            $formattedQuestion = $this->ansiFormat($question, Console::FG_RED);
        } else {
            $formattedQuestion = $question;
        }
        return $this->confirm($formattedQuestion, $default);
    }

    protected function echoSuccess(string $string): void
    {
        if ($this->isColorEnabled()) {
            $string = Console::ansiFormat($string, [Console::FG_GREEN]);
        }
        echo $string , PHP_EOL;
    }

    protected function echoError(string $string): void
    {
        if ($this->isColorEnabled()) {
            $string = Console::ansiFormat($string, [Console::FG_RED]);
        }
        echo $string , PHP_EOL;
    }

    protected function echoException(\Throwable $exception): void
    {
        if ($this->isColorEnabled()) {
            $string = Console::ansiFormat(get_class($exception), [Console::FG_RED]) . ': '. Console::ansiFormat($exception->getMessage(), [Console::FG_RED]) . PHP_EOL . PHP_EOL . 'in ' . $exception->getFile() . ':' . $exception->getLine() . PHP_EOL;
        } else {
            $string = get_class($exception) . ': '. $exception->getMessage() . PHP_EOL . PHP_EOL . 'in ' . $exception->getFile() . ':' . $exception->getLine() . PHP_EOL;
        }
        echo $string , PHP_EOL;
    }

    /**
     * Do echo() to stdout with color support, see ansiFormat().
     *
     * @param string $string
     */
    protected function echoLine(string $string): void
    {
        if ($this->isColorEnabled()) {
            $args = func_get_args();
            array_shift($args);
            $string = Console::ansiFormat($string, $args);
        }

        echo $string , PHP_EOL;
    }

}
