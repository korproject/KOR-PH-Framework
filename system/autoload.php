<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * List folders recursively
 *
 * @param string $directory: target directory
 * @param array $directories: directories array
 * @return array
 */
function globRecursive($directory, &$directories = array())
{
    foreach (glob($directory, GLOB_ONLYDIR) as $folder) {
        $directories[] = "{$folder}";
        globRecursive("{$folder}/*", $directories);
    }

    return $directories;
}

/**
 * Autload function
 *
 * @param string|array $includeFolders: target folder or folders
 * @param bool $subFolders: search in sub folders option
 */
function autoload($includeFolders = null, $subFolders = false)
{
    $dir = null;
    $dirName = dirname(__DIR__);

    // if there is array (multiple target folders)
    if ($includeFolders && is_array($includeFolders)) {
        // search every target directory
        foreach ($includeFolders as $folder => $searchSub) {
            // search sub directories
            if ($searchSub) {
                $dirName . DIRECTORY_SEPARATOR . $folder.'<br>';
                globRecursive($dirName . DIRECTORY_SEPARATOR . $folder, $dir);
            } // only current directory
            else {
                $dir[] = $dirName . DIRECTORY_SEPARATOR . $folder;
            }
        }
    } // if there is only path (as a string)
    else if (is_string($includeFolders) && $subFolders) {
        globRecursive($dirName . DIRECTORY_SEPARATOR . $includeFolders, $dir);
    }

    // merge all directories
    $dirs = is_array($dir) ? implode(PATH_SEPARATOR, $dir) : $dirName . ($includeFolders ? DIRECTORY_SEPARATOR . $includeFolders : null);

    registerDirs($dirs);
}

/**
 * Register classes in target directory/directories
 * @param string $dirs: merged directory list
 */
function registerDirs($dirs){
    if ($dirs != null) {
        set_include_path(rtrim(get_include_path(), PATH_SEPARATOR) . PATH_SEPARATOR . $dirs);
        spl_autoload_extensions('.php,.class.php');

        // register
        spl_autoload_register(function ($className) {
            if ($lastNsPos = strrpos($className, '\\')) {
                $className = substr($className, $lastNsPos + 1);
            }

            // loads when called
            spl_autoload($className);
        });
    }
}