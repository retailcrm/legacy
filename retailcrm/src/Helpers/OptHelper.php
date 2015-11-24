<?php

class OptHelper
{
    private $shortopts = array();
    private $longopts = array();

    public function __construct($shortopts = '', $longopts = array())
    {
        if (!empty($shortopts)) {
            $this->shortopts = preg_split(
                '@([a-z0-9][:]{0,2})@i',
                $shortopts, 0,
                PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
            );
        }

        if (!empty($longopts)) {
            $this->longopts = $longopts;
        }
    }

    public function get()
    {
        if (!array_key_exists('argv', $_SERVER)) {
            return false;
        }

        $result = array();

        $params = $_SERVER['argv'];

        foreach ($params as $key => $param) {
            if ($param{0} == '-') {
                $name = substr($param, 1);
                $value = true;

                if ($name{0} == '-') {
                    $name = substr($name, 1);
                    if (strpos($param, '=') !== false) {
                        $long = explode('=', substr($param, 2), 2);
                        $name = $long[0];
                        $value = $long[1];
                        unset($value);
                    }
                }

                if (
                    isset($params[$key + 1]) &&
                    $value === true &&
                    $params[$key + 1] !== false &&
                    $params[$key + 1]{0} != '-'
                        ) {
                            $value = $params[$key + 1];
                        }

                        $result[$name] = $value;
            } else {
                $result[] = $param;
            }
        }

        unset($params);

        return empty($result) ? false : $this->filter($result);
    }

    private function filter($params)
    {
        $result = array();

        $opts = array_merge($this->shortopts, $this->longopts);

        foreach ($opts as $opt) {
            if (substr($opt, -2) === '::') {
                $key = substr($opt, 0, -2);

                if (isset($params[$key]) && !empty($params[$key])) {
                    $result[$key] = $params[$key];
                } elseif (isset($params[$key])) {
                    $result[$key] = true;
                }
            } elseif (substr($opt, -1) === ':') {
                $key = substr($opt, 0, -1);

                if (isset($params[$key]) && !empty($params[$key])) {
                    $result[$key] = $params[$key];
                }
            } elseif (ctype_alnum($opt) && isset($params[$opt])) {
                $result[$opt] = true;
            }
        }

        return $result;
    }
}
