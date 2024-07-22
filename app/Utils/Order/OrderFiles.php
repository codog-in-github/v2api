<?php

namespace App\Utils\Order;

class OrderFiles
{
    public static $intance = null;

    protected string $HTTP_BASE_DIR = '';
    protected string $BASE_DIR = '';
    protected string $RECYCLE_BIN = '';

    protected function __construct()
    {
        if(self::$intance !== null) {
            throw new \Exception("please use " . static::class ."::getInstance()");
        }
        $this->BASE_DIR = public_path('order') . DIRECTORY_SEPARATOR;
        $this->RECYCLE_BIN = public_path('trash') . DIRECTORY_SEPARATOR;
        $this->HTTP_BASE_DIR = rtrim(env('APP_URL'), '/') . '/order/';
    }

    public static function getInstance(): self
    {
        if(self::$intance === null) {
            self::$intance = new self();
        }
        return self::$intance;
    }

    public function toHttpURI(string $fsURI): string
    {
        if(DIRECTORY_SEPARATOR === '\\') {
            $fsURI = str_replace('\\', '/', $fsURI);
        }
        return $this->HTTP_BASE_DIR . $fsURI;
    }
    public function toFilePath(string $HTTPURI): string
    {
        if(!strstr($HTTPURI, $this->HTTP_BASE_DIR)) {
            throw new \Exception('invalid http uri');
        }
        $fsURI = str_replace($this->HTTP_BASE_DIR, '', $HTTPURI);

        if(DIRECTORY_SEPARATOR === '\\') {
            $fsURI = str_replace('/', '\\', $fsURI);
        }
        return $this->BASE_DIR. $fsURI;
    }


    public function getAsHttpURI(int $orderId, bool $group = false): array
    {
        $files = $this->getOrderFiles($orderId, $group);
        if(!$group) {
            return array_map([$this, 'toHttpURI'], $files);
        }
        $groups = [];
        foreach ($files as $groupKey => $file) {
            $groups[$groupKey] = array_map([$this, 'toHttpURI'], $file);
        }
        return $groups;
    }

    protected function ls(string $dir): array
    {
        $fullDir = $this->BASE_DIR . $dir;
        $files = array_diff(scandir($fullDir), ['.', '..']);
        if(empty($files))
            return [];
        $fsURIs = [];
        foreach ($files as $file) {
            if(is_dir($fullDir. $file)) {
                $nextDir = $dir. $file. DIRECTORY_SEPARATOR;
                $children = $this->ls($nextDir);
                $fsURIs = array_merge($fsURIs, $children);
            } else {
                $fsURIs[] = $dir. $file;
            }
        }
        return $fsURIs;
    }


    /**
     * @throws \Exception
     */
    public function getOrderFiles(int $orderId, bool $group = false): array {
        if(!is_dir($this->BASE_DIR. $orderId))
          return [];
        if(!$group) {
            return $this->ls($orderId . DIRECTORY_SEPARATOR);
        }
        $files = [];
        foreach ($this->ls($orderId. DIRECTORY_SEPARATOR) as $file) {
            $type = explode(DIRECTORY_SEPARATOR, $file)[1];
            $files[$type][] = $file;
        }
        return $files;
    }


    public function saveFile(string $file, string $fileName, int $orderId, int $type): string
    {
        $fileDir = $this->BASE_DIR.
            DIRECTORY_SEPARATOR.
            $orderId.
            DIRECTORY_SEPARATOR.
            $type;
        if(is_file($fileDir. DIRECTORY_SEPARATOR. $fileName)){
            throw new \Exception("file exists");
        }
        if(!is_dir($fileDir)){
            mkdir($fileDir, 0777, true);
        }
        copy($file, $fileDir. DIRECTORY_SEPARATOR. $fileName);
        return $orderId. DIRECTORY_SEPARATOR. $type. DIRECTORY_SEPARATOR. $fileName;
    }

    public function unlink($file)
    {
        if(!is_file($file)) {
            throw new \Exception("file not exists");
        }
        $fileHash = hash_file('sha256', $file);
        rename($this->BASE_DIR. $file, $this->RECYCLE_BIN. $fileHash);
        return $fileHash;
    }
}
