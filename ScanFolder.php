<?php
/**
 * @Tool       : VsCode.
 * @Date       : 2020-03-13 00:41:19
 * @Author     : rxm rxm@wiki361.com
 * @LastEditors: rxm rxm@wiki361.com
 */

/**
 * 扫描目录
 * Class ScanFolder
 */
class ScanFolder
{
    /**
     * @var array 忽略的文件 文件夹
     */
    protected array $ignore = [
        '.idea'     => true,
        '.DS_Store' => true,
    ];

    /**
     * @var int 文件数量
     */
    public int $fileNum = 0;

    /**
     * @var int 文件夹数量
     */
    public int $pathNum = 0;

    /**
     * @var array 所有文件
     */
    public array $files = [];

    /**
     * @var array 目录集合
     */
    public array $paths = [];

    /**
     * @var array 哨兵文件
     */
    protected array $sentinelPaths = [];

    /**
     * @var array 哨兵目录
     */
    protected array $sentinelFiles = [];

    /**
     * Open the folder and the folder below
     * @param string $path
     * @throws
     */
    public function __construct(string $path)
    {
        if (false == $handle = opendir($path)) {
            throw new Exception('path must be directory');
        }

        while (false !== ($file = readdir($handle))) {
            if ($file !== '.' && $file !== '..' && !isset($this->ignore[$file])) {
                $file = rtrim($path, '/') . '/' . $file;
                if (is_dir($file)) {
                    $files = (new ScanFolder($file));
                    $this->pathNum++;
                    $this->paths[] = $file;
                    $this->files   = array_merge($this->files, $files->files);
                    $this->paths   = array_merge($this->paths, $files->paths);
                    $this->pathNum += $files->pathNum;
                    $this->fileNum += $files->fileNum;
                } else {
                    $this->files[] = $file;
                    $this->fileNum++;
                }
            }
        }

        $this->sentinelFiles = array_flip($this->files);
        $this->sentinelPaths = array_flip($this->paths);

        closedir($handle);
    }

    /**
     * delete file
     * 删除文件
     * @param string $file
     * @return void
     */
    public function deleteFile(string $file): void
    {
        if (isset($this->sentinelFiles[$file])) {
            @unlink($file);
        }
    }

    /**
     * Clean up certain types of files
     * 清理某些类型的文件
     * @param string $suffix
     * @return void
     */
    public function clearSuffixFile(string $suffix): void
    {
        foreach ($this->files as $v) {
            if (pathinfo($v)['extension'] == $suffix) {
                @unlink($v);
            }
        }
    }

    /**
     * delete empty folder
     * 删除空文件夹
     * @return void
     */
    public function clearEmptyDirectory(): void
    {
        foreach ($this->files as $v) {
            $tempDirectory[pathinfo($v)['dirname']] = true;
        }

        foreach ($this->paths as $v) {
            if (!isset($tempDirectory[$v]) && file_exists($tempDirectory[$v])) {
                @exec('rm -rf ' . $v);
            }
        }
    }

    /**
     * delete directories after files
     * 删除目录
     * @param string $path
     * @return void
     */
    public function deleteDirectory(string $path): void
    {
        if (isset($this->sentinelPaths[$path])) {
            foreach ($this->files as $v) {
                if (false !== strpos($v, $path)) {
                    @unlink($v);
                }
            }
            @rmdir($path);
        }
    }
}

//
$files = new ScanFolder('../test/');
$files->clearEmptyDirectory();
$files->clearSuffixFile('txt');

var_dump($files->files);

