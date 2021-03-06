<?php

class Urfa_Resolve
{
    static public function getSenderName($id)
    {
        if ($id == 0) {
            return 'Системное сообщение';
        } else {
            return 'Сообщение от администратора';
        }
    }

    static public function getTimeFromSec($secs)
    {
        $vals = array(
            'н' => (int)($secs / 86400 / 7),
            'д' => $secs / 86400 % 7,
            'ч' => $secs / 3600 % 24,
            'м' => $secs / 60 % 60,
            'с' => $secs % 60,
        );

        $ret = array();

        $added = false;
        foreach ($vals as $k => $v) {
            if ($v > 0 || $added) {
                $added = true;
                $ret[] = $v.$k;
            }
        }

        return join(' ', $ret);
    }

    static public function getMb($byte)
    {
        return round($byte / (1024 * 1024), 2);
    }


    /**
     * @static
     *
     * @param        $timestamp
     * @param string $format
     *
     * @return string
     */
    static public function getDateFromTimestamp($timestamp, $format = 'd.m.Y H:i')
    {
        return date($format, $timestamp);
    }

    static public function roundDouble($double)
    {
        return round($double, 2);
    }

    static public function ip2string($ip)
    {
        $a = $ip & 0xff;
        $b = ($ip >> 8) & 0xff;
        $c = ($ip >> 16) & 0xff;
        $d = ($ip >> 24) & 0xff;

        return "".$d.".".$c.".".$b.".".$a;
    }

    static public function parceIpNetworkFormat($ip)
    {
        $ip2 = ip2long($ip);
        $arr = unpack("V", pack("N", $ip2));

        return $arr[1];
    }

    static public function resolveBlockState($state)
    {
        if ($state == 0) {
            return 'Пользовательская блокировка';
        } else {
            if ($state == 1) {
                return 'Системная блокировка';
            } else {
                if ($state == 2) {
                    return 'Администраторская блокировка';
                } else {
                    return "";
                }
            }
        }
    }

    static public function resolveBlockItem($item)
    {
        if ($item == 2) {
            return 'Лицевой счет';
        }

        return $item;
    }

    static public function resolveUserName($id)
    {
        return $id;
    }

    static public function resolveIntStatus($state)
    {
        if ($state == -1) {
            return 'Включен не для всех счетов';
        }
        if ($state == 0) {
            return 'Выключен';
        }
        if ($state == 1) {
            return 'Включен';
        }
    }

    static public function resolveIntStatusForAccount($state)
    {
        if ($state == 0) {
            return 'Включить';
        }
        if ($state == 1) {
            return 'Выключить';
        }
    }

    static public function resolveServiceType($state)
    {
        switch ($state) {
            case 1:
                return 'Разовая услуга';
            case 2:
                return 'Периодическая услуга';
            case 3:
                return 'Услуга передачи IP трафика';
            case 4:
                return 'Услуга Hotspot';
            case 5:
                return 'Услуга Dialup';
            case 6:
                return 'Услуга телефонии';
            case 7:
                return 'Другие услуги';
            case 16:
                return 'Платеж';
            case 17:
                return 'Отменённый платёж';
            case 18:
                return 'burn_payment';
            case 19:
                return 'unassigned_traffic';
            case 20:
                return 'ballance_rollover';
            case 21:
                return 'Движение средств';
            case 22:
                return 'Возврат средств';
            default:
                return $state;
        }
    }

    static public function getLinkToServicePass($slink_id, $item_id, $service_name, $login = '')
    {
        return '<a HREF="/user/change-service-password/slink_id/'.$slink_id.'/item_id/'.$item_id.'/login/'.$login.'">'
        .htmlspecialchars($service_name).'</a>';
    }

    static public function getLinkToTariff($aid, $tlink_id)
    {
        return '/user/change-tariff/aid/'.$aid.'/tlink_id/'.$tlink_id;
    }

    static public function getLinkToService($slink_id, $service_name)
    {
        return '<a HREF="/user/service/'.$slink_id.'">'.htmlspecialchars($service_name).'</a>';
    }

    static public function getLinkToChangeTariff($aid, $tlink_id, $tp_next)
    {
        return '/user/change-tariff/aid/'.$aid.'/tlink_id/'.$tlink_id.'/tp_next/'.$tp_next;
    }

    static public function getLinkToTurboMode($slink_id)
    {
        return '<a href="/user/turbo-mode/slink_id/'.$slink_id.'">Включить</a>';
    }

    static public function getTimeFromSeconds($uptime)
    {
        $days = ($uptime - $uptime % 3600) / 3600 / 24;
        $hour = ($uptime - $uptime % 3600) / 3600 % 24;
        $sec = ($uptime % 3600) % 60;
        $min = ($uptime - $hour * 3600 - $days * 3600 * 24 - $sec) / 60;

        return $hour.":".$min.":".$sec;
    }

    static public function getDayFromSeconds($uptime)
    {
        $days = ($uptime - $uptime % 3600) / 3600 / 24;

        return $days;
    }

    static public function resolveRate($rate)
    {
        if ($rate > 0) {
            return $rate;
        } else {
            if ($rate == -1) {
                return 'без ограничений';
            } else {
                return 'нет информации';
            }
        }
    }

    static public function resolveLimit($rate, $bytesInKb)
    {

        if ($rate == -1) {
            return 'без ограничений';
        }

        if ($rate == 0) {
            return 'нет информации';
        }

        $negative = $rate < 0;

        if ($negative) {
            $rate = bcmul($rate, "-1");
        }

        $str = '';

        $bInGb = bcpow($bytesInKb, 3);
        $bInMb = bcpow($bytesInKb, 2);

        $gb = bcdiv($rate, $bInGb);
        $mb = bcdiv(bcmod($rate, $bInGb), $bInMb);
        $kb = bcdiv(bcmod($rate, $bInMb), $bytesInKb);
        $b = bcmod($rate, $bytesInKb);

        if ($negative) {
            $str .= "-";
        }

        if (bccomp($gb, 0)) {
            $str .= $gb."G ";
        }

        if (bccomp($mb, 0)) {
            $str .= $mb."M ";
        }

        if (bccomp($kb, 0)) {
            $str .= $kb."K ";
        }

        if (bccomp($b, 0)) {
            $str .= $b."B";
        }

        return trim($str);
    }
}