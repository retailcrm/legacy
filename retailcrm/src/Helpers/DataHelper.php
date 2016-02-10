<?php

class DataHelper
{
    public static function getDate($file)
    {
        if (file_exists($file) && 1 < filesize($file)) {
            $result = file_get_contents($file);
        } else {
            $result = date(
                'Y-m-d H:i:s',
                strtotime('-1 days', strtotime(date('Y-m-d H:i:s')))
            );
        }

        return $result;
    }

    public static function filterRecursive($haystack)
    {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = self::filterRecursive($haystack[$key]);
            }

            if (is_null($haystack[$key]) || $haystack[$key] === '' || count($haystack[$key]) == 0) {
                unset($haystack[$key]);
            } elseif (!is_array($value)) {
                $haystack[$key] = trim($value);
            }
        }

        return $haystack;
    }

    public static function explodeFIO($string)
    {
        $result = array();
        $parse = (!$string) ? false : explode(" ", $string, 3);

        switch (count($parse)) {
            case 1:
                $result['firstName'] = $parse[0];
                $result['lastName'] = '';
                $result['patronymic'] = '';
                break;
            case 2:
                $result['firstName'] = $parse[1];
                $result['lastName'] = $parse[0];
                $result['patronymic'] = '';
                break;
            case 3:
                $result['firstName'] = $parse[1];
                $result['lastName'] = $parse[0];
                $result['patronymic'] = $parse[2];
                break;
            default:
                return false;
        }

        return $result;
    }

    public static function explodeUids($uids)
    {
        $uids   = explode(',', $uids);
        $ranges = array();

        foreach ($uids as $idx => $uid) {
            if (strpos($uid, '-')) {
                $range = explode('-', $uid);
                $ranges = array_merge($ranges, range($range[0], $range[1]));
                unset($uids[$idx]);
            }
        }

        $uids = implode(',', array_merge($uids, $ranges));

        return $uids;
    }
}
