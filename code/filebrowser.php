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
        $this->currentPath = $currentPath;
    }

    /**
     * @param array $extensionFilter
     */
    public function SetExtensionFilter(array $extensionFilter)
    {
        $this->extensionFilter = $extensionFilter;
    }

    public function Get()
    {
        return [];
    }
}
