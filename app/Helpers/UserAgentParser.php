<?php

/**
 * Clase para parsear User Agent y obtener información del dispositivo
 */

namespace App\Helpers;

class UserAgentParser {
    /**
     * Parsea el User Agent y devuelve información legible del dispositivo
     */
    public static function parseDevice($userAgent = null) {
        if (!$userAgent) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        }

        if (empty($userAgent)) {
            return 'Dispositivo Desconocido';
        }

        $browser = self::getBrowser($userAgent);
        $os = self::getOperatingSystem($userAgent);
        $device = self::getDeviceType($userAgent);

        // Formato: Navegador, Sistema Operativo (Tipo de dispositivo)
        $result = $browser . ', ' . $os;

        if ($device !== 'Desktop') {
            $result .= ' (' . $device . ')';
        }

        return $result;
    }

    /**
     * Detecta el navegador
     */
    private static function getBrowser($userAgent) {
        $browsers = [
            '/Edg\//'           => 'Microsoft Edge',
            '/Chrome\//'        => 'Google Chrome',
            '/Safari\//'        => 'Safari',
            '/Firefox\//'       => 'Mozilla Firefox',
            '/Opera\//'         => 'Opera',
            '/OPR\//'           => 'Opera',
            '/Brave\//'         => 'Brave',
            '/Vivaldi\//'       => 'Vivaldi',
            '/UCBrowser\//'     => 'UC Browser',
            '/SamsungBrowser\//' => 'Samsung Internet',
            '/MSIE\//'          => 'Internet Explorer',
            '/Trident\//'       => 'Internet Explorer'
        ];

        foreach ($browsers as $pattern => $name) {
            if (preg_match($pattern, $userAgent)) {
                return $name;
            }
        }

        return 'Navegador Desconocido';
    }

    /**
     * Detecta el sistema operativo
     */
    private static function getOperatingSystem($userAgent) {
        $os_array = [
            // Windows
            '/Windows NT 10.0/'     => 'Windows 10/11',
            '/Windows NT 6.3/'      => 'Windows 8.1',
            '/Windows NT 6.2/'      => 'Windows 8',
            '/Windows NT 6.1/'      => 'Windows 7',
            '/Windows NT 6.0/'      => 'Windows Vista',
            '/Windows NT 5.1/'      => 'Windows XP',
            '/Windows NT/'          => 'Windows',

            // macOS
            '/Mac OS X 10[._]15/'   => 'macOS Catalina',
            '/Mac OS X 10[._]14/'   => 'macOS Mojave',
            '/Mac OS X 10[._]13/'   => 'macOS High Sierra',
            '/Mac OS X 10[._]12/'   => 'macOS Sierra',
            '/Mac OS X/'            => 'macOS',
            '/Macintosh/'           => 'macOS',

            // iOS
            '/iPhone OS 15_/'       => 'iOS 15',
            '/iPhone OS 14_/'       => 'iOS 14',
            '/iPhone OS 13_/'       => 'iOS 13',
            '/iPhone OS/'           => 'iOS',
            '/iPad.*OS 15_/'        => 'iPadOS 15',
            '/iPad.*OS 14_/'        => 'iPadOS 14',
            '/iPad.*OS/'            => 'iPadOS',
            '/iPhone/'              => 'iOS',
            '/iPad/'                => 'iPadOS',

            // Android
            '/Android 13/'          => 'Android 13',
            '/Android 12/'          => 'Android 12',
            '/Android 11/'          => 'Android 11',
            '/Android 10/'          => 'Android 10',
            '/Android 9/'           => 'Android 9',
            '/Android 8/'           => 'Android 8',
            '/Android 7/'           => 'Android 7',
            '/Android/'             => 'Android',

            // Linux
            '/Ubuntu/'              => 'Ubuntu',
            '/Debian/'              => 'Debian',
            '/CentOS/'              => 'CentOS',
            '/Fedora/'              => 'Fedora',
            '/Linux/'               => 'Linux',

            // Otros
            '/BlackBerry/'          => 'BlackBerry',
            '/webOS/'               => 'webOS',
            '/Symbian/'             => 'Symbian',
            '/Windows Phone/'       => 'Windows Phone'
        ];

        foreach ($os_array as $pattern => $name) {
            if (preg_match($pattern, $userAgent)) {
                return $name;
            }
        }

        return 'Sistema Desconocido';
    }

    /**
     * Detecta el tipo de dispositivo
     */
    private static function getDeviceType($userAgent) {
        // Móviles
        if (preg_match('/Mobile|Android|iPhone|iPod|BlackBerry|Windows Phone/', $userAgent)) {
            if (preg_match('/iPhone/', $userAgent)) {
                return 'iPhone';
            } elseif (preg_match('/Android.*Mobile/', $userAgent)) {
                return 'Android Phone';
            } elseif (preg_match('/BlackBerry/', $userAgent)) {
                return 'BlackBerry';
            } elseif (preg_match('/Windows Phone/', $userAgent)) {
                return 'Windows Phone';
            } else {
                return 'Móvil';
            }
        }

        // Tablets
        if (preg_match('/Tablet|iPad|Android(?!.*Mobile)/', $userAgent)) {
            if (preg_match('/iPad/', $userAgent)) {
                return 'iPad';
            } elseif (preg_match('/Android/', $userAgent)) {
                return 'Tablet Android';
            } else {
                return 'Tablet';
            }
        }

        return 'Desktop';
    }

    /**
     * Obtiene información detallada del dispositivo
     */
    public static function getDetailedInfo($userAgent = null) {
        if (!$userAgent) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        }

        return [
            'browser' => self::getBrowser($userAgent),
            'os' => self::getOperatingSystem($userAgent),
            'device_type' => self::getDeviceType($userAgent),
            'formatted' => self::parseDevice($userAgent),
            'raw_user_agent' => $userAgent
        ];
    }
}
