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
        $this->rootPath = $rootPath;
    }

    /**
     * @param $currentPath
     */
    public function SetCurrentPath($currentPath)
    {
        // TODO: do not allow for current path above root folder

        $this->currentPath = rtrim($currentPath, '/') . '/';
    }

    /**
     * @param array $extensionFilter
     */
    public function SetExtensionFilter(array $extensionFilter)
    {
        $this->extensionFilter = $extensionFilter;
    }


    public function Get(){

        // TODO: this is horrible - try to use glob or RecursiveIteratorIterator - no time
        $fileList = [];
        if (is_dir($this->currentPath)){

            $files = scandir($this->currentPath);

            foreach ($files as $file) {
                if($file == "." || $file == ".."){
                    continue;
                }

                if ($this->isDir($file)) {
                    $fileList[] = array(
                        'file_name' => $file,
                        'directory' => true,
                        'extension' => '',
                        'size' => $this->fileSize($file),
                        'path' => ltrim($this->currentPath . $file, $this->rootPath),
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
                        'path' => ltrim($this->currentPath . $file, $this->rootPath),
                    ];

                }
            }
        }

        return $fileList;
    }

    private function isDir($fileName)
    {
        return is_dir($this->currentPath . $fileName);
    }

    private function fileExtension($fileName)
    {
        return pathinfo($this->currentPath . $fileName, PATHINFO_EXTENSION);
    }

    private function fileSize($fileName)
    {
        return filesize($this->currentPath . $fileName) . " bytes";
    }
}
