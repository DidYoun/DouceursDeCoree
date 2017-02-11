<?php

/**
 * Class UploadHelper
 *
 * @author              Didier Youn <didier.youn@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/CRUDSelenium
 */
namespace Helpers;

use DateTime;

class UploadHelper
{
    /**
     * @var array FILE_ALLOWED_EXTENSION
     */
    const FILE_ALLOWED_EXTENSION = array("jpg", "png", "gif", "jpeg");
    /**
     * @var string ROOT_UPLOAD_DIRECTORY
     */
    const ROOT_UPLOAD_DIRECTORY = DS . "assets" . DS . "medias" . DS . "upload";
    /**
     * @var string PATH_DEFAULT_IMAGE
     */
    const PATH_DEFAULT_IMAGE = "/assets/medias/img/default.png";
    /**
     * Upload file to storage directory
     *
     * @param $file
     * @return bool|string
     */
    public function upload($file)
    {
        if (!isset($file["file"])) {
            return false;
        }
        /** @var \Slim\Http\UploadedFile $uploadFile */
        $uploadFile = reset($file);
        if (!$this->_validFileExtension($uploadFile)) {
            return false;
        }
        /** @var bool|string $uploadPath */
        $uploadPath = $this->_mv($uploadFile);
        if (!$uploadPath) {
            return false;
        }

        return $uploadPath;
    }

    /**
     * Check extension of the upload file
     *
     * @param \Slim\Http\UploadedFile $file
     * @return bool
     */
    protected function _validFileExtension($file)
    {
        if (!isset($file)) {
            return false;
        }
        /** @var string $mimeType */
        $mimeType = $file->getClientMediaType();
        preg_match_all('/([a-zA-Z]*)$/', $mimeType, $matches);
        if (!isset($matches) || !isset($matches[1])) {
            return false;
        }
        /** @var string $extension */
        $extension = $matches[1][0];
        foreach (self::FILE_ALLOWED_EXTENSION as $allowedExtension) {
            if ($allowedExtension == $extension) {
                return true;
            }
        }

        return false;
    }

    /**
     * Move file to upload directory
     *
     * @param \Slim\Http\UploadedFile $file
     * @return bool
     */
    protected function _mv($file)
    {
        if (!isset($file)) {
            return false;
        }
        /** @var string $path */
        $path = $this->_getUploadPath();
        /** @var string $serverPath */
        $serverPath = ROOT_PATH . $path;
        if (!is_dir($serverPath) && !$this->_createUploadDirectory($serverPath)) {
            return false;
        }

        /** @var string $serverPath */
        $serverPath = $serverPath . DS . $file->getClientFilename();
        $file->moveTo($serverPath);
        if (!file_exists($serverPath)) {
            return false;
        }

        return $path . DS . $file->getClientFilename();
    }

    /**
     * Get upload path
     *
     * @return bool|string
     */
    protected function _getUploadPath()
    {
        /** @var string $dateFolder */
        $dateFolder = $this->_getDateFolder();
        /** @var string $path */
        $path = self::ROOT_UPLOAD_DIRECTORY . DS . $dateFolder;
        if (!isset($path)) {
            return false;
        }

        return $path;
    }

    /**
     * Create upload directory
     *
     * @param string $path
     * @return bool
     */
    protected function _createUploadDirectory($path)
    {
        if (!isset($path) || !mkdir($path, 0777, true)) {
            return false;
        }

        return true;
    }

    /**
     * Get date folder format as Ymd
     * --> 25 janvier 2017
     * --> 20172501
     *
     * @return string
     */
    private function _getDateFolder()
    {
        /** @var DateTime $now */
        $now = new DateTime();

        return $now->format('Ydm');
    }
}