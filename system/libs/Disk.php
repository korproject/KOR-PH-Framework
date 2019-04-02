<?php

class Disk
{
    /**
     * Returns detailed disk usage info
     *
     * @param string $dir target directory (default is root dir)
     * @see http://php.net/manual/tr/function.disk-total-space.php
     */
    public function getDiskUsage($dir = '/')
    {
        $total_space = disk_total_space($dir);
        $free_space = disk_free_space($dir);
        $disk_usage = $total_space - $free_space;
        $disk_usage_p = ($disk_usage / $total_space) * 100;
        $free_space_p = ($free_space / $total_space) * 100;

        return [
            'disks' => $this->getDisks(),
            'total' => $this->getSize($total_space),
            'free' => $this->getSize($free_space),
            'usage' => $this->getSize($disk_usage),
            'usage_percentage' => round($disk_usage_p, 2),
            'free_percentage' => round($free_space_p, 2),
        ];
    }

    /**
     * Returns recalculated bytes
     *
     * @param string $bytes default byte value
     * @see http://php.net/manual/tr/function.disk-total-space.php
     * @return int|string
     */
    public function getSize($bytes)
    {
        if ($bytes == null) {
            return 0;
        }

        $symbols = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $exp = floor(log($bytes) / log(1024));

        return sprintf('%.2f ' . $symbols[$exp], ($bytes / pow(1024, floor($exp))));
    }

    /**
     * Returns logical drive disks (separated windows and unix)
     *
     * @param string $bytes default byte value
     * @see http://php.net/manual/tr/function.disk-total-space.php
     * @return array|mixed|string
     */
    public function getDisks()
    {
        if (php_uname('s') == 'Windows NT') {
            // windows
            $disks = `fsutil fsinfo drives`;
            $disks = str_word_count($disks, 1);
            if ($disks[0] != 'Drives') {
                return '';
            }

            unset($disks[0]);
            foreach ($disks as $key => $disk) {
                $disks[$key] = $disk . ':\\';
            }

            return $disks;
        } else {
            // unix
            $data = `mount`;
            $data = explode(' ', $data);
            $disks = array();
            foreach ($data as $token) {
                if (substr($token, 0, 5) == '/dev/') {
                    $disks[] = $token;
                }
            }

            return $disks;
        }
    }
}