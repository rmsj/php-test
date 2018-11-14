<?php
require_once('interface.php');

class FileBrowser implements __FileBrowser {

    /**
     * @var string
     */
    private $rootPath;

    /**
     * @var string
     */
    private $currentPath;

    /**
     * @var array
     */
    private $extensionFilter;

    public function __construct($rootPath, $currentPath = null, array $extensionFilter = array())
    {
        $this->SetRootPath($rootPath);

        // initial setup to current path is root path
        $this->SetCurrentPath($rootPath);

        if ($currentPath) {
            $this->SetCurrentPath($currentPath);
        }
        if ($extensionFilter) {
            $this->SetExtensionFilter($extensionFilter);
        }
    }

    public function SetRootPath($rootPath)
    {
        $this->rootPath = rtrim($rootPath, '/') . '/';
    }

    /**
     * @param $currentPath
     */
    public function SetCurrentPath($currentPath)
    {
        // TODO: do not allow for current path above root folder
        if ($this->rootPath == $currentPath) {
            $this->currentPath = $this->rootPath;
        } else {
            $this->currentPath = rtrim($currentPath, '/') . '/';
        }
    }

    /**
     * @param array $extensionFilter
     */
    public function SetExtensionFilter(array $extensionFilter)
    {
        $this->extensionFilter = $extensionFilter;
    }

    /**
     * Returns list of files and folders
     * @return array
     */
    public function Get(){

        // TODO: this is horrible - try to use glob or RecursiveIteratorIterator - no time
        $fileList = [];

        if (!is_dir($this->currentPath)) {
            return $fileList;
        }

        $files = $this->scanFolder($this->currentPath);

        // up one level link
        if ($this->currentPath != $this->rootPath) {
            $fileList[] = array(
                'file_name' => "&uarr;",
                'directory' => true,
                'extension' => '',
                'size' => "",
                'link' => $this->oneLevelUp(),
            );
        }

        foreach ($files as $file) {
            if($file == "." || $file == ".."){
                continue;
            }

            if ($this->isDir($file)) {
                $fileList[] = array(
                    'file_name' => $file,
                    'directory' => true,
                    'extension' => 'folder',
                    'size' => "",
                    'link' => $this->currentPath . $file,
                );
            } else {
                // filter on the fly
                $ext = $this->fileExtension($file);
                if($this->extensionFilter){
                    if(!in_array($ext, $this->extensionFilter)){
                        continue;
                    }
                }
                $fileList[] = [
                    'file_name' => $file,
                    'directory' => false,
                    'extension' => $ext,
                    'size' => $this->fileSize($file),
                    'link' => "",
                ];

            }
        }

        return $fileList;
    }

    /**
     * Checks if we are on a folder
     *
     * @param $fileName
     * @return bool
     */
    private function isDir($fileName)
    {
        return is_dir($this->currentPath . $fileName);
    }

    /**
     * Checks file extension
     *
     * @param $fileName
     * @return mixed
     */
    private function fileExtension($fileName)
    {
        return pathinfo($this->currentPath . $fileName, PATHINFO_EXTENSION);
    }

    /**
     * Cake recioy fir human readable file size
     * @param $fileName
     * @return string
     */
    private function fileSize($fileName)
    {
        $bytes = filesize($this->currentPath . $fileName);
        $size = ['bytes','kB','MB','GB','TB','PB','EB','ZB','YB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.2f", $bytes / pow(1024, $factor)) . " " . @$size[$factor];
    }

    /**
     * Scan the current path for files and folder
     * @return array
     */
    private function scanFolder()
    {
        return scandir($this->currentPath);
    }

    /**
     * One level up - up to root folder
     * @return bool|string
     */
    private function oneLevelUp()
    {
        if ($this->currentPath == $this->rootPath) {
            return $this->rootPath;
        }
        $currentPath = rtrim($this->currentPath, '/');
        return substr($this->currentPath, 0, strrpos($currentPath, "/"));
    }
}
