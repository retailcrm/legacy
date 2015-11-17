<?php

class DebugHelper
{
    private $baseMemoryUsage;
    private $tusage;
    private $rusage;

    public function __construct()
    {
        $this->baseMemoryUsage = memory_get_usage(true);

        $proc = getrusage();
        $this->tusage = microtime(true);
        $this->rusage = $proc['ru_utime.tv_sec'] * 1e6 + $proc['ru_utime.tv_usec'];
    }

    private function formatSize($size)
    {
        $postfix = array('b', 'Kb', 'Mb', 'Gb', 'Tb');
        $position = 0;
        while ($size >= 1024 && $position < 4) {
            $size /= 1024;
            $position++;
        }

        return sprintf('%s %s', round($size, 2), $postfix[$position]);
    }

    public function getMemoryUsage()
    {
        return $this->formatSize(memory_get_usage(true) - $this->baseMemoryUsage);
    }

    public function getCpuUsage()
    {
        $proc = getrusage();
        $proc["ru_utime.tv_usec"] = ($proc["ru_utime.tv_sec"] * 1e6 + $proc["ru_utime.tv_usec"]) - $this->rusage;
        $time = (microtime(true) - $this->tusage) * 1000000;

        return $time > 0 ? sprintf("%01.2f", ($proc["ru_utime.tv_usec"] / $time) * 100) : '0.00';
    }

    public function write($string)
    {
        echo sprintf("%s\t%s\t%s%s", $string, $this->getCpuUsage(), $this->getMemoryUsage(), PHP_EOL);
    }
}
