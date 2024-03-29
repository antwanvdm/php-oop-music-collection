<?php namespace MusicCollection\Utils;

use MusicCollection\DTO\FileUpload;

/**
 * Class Image
 * @package MusicCollection\Utils
 */
class Image
{
    /**
     * @param FileUpload $uploadFile
     * @return string
     * @throws \RuntimeException
     */
    public function save(FileUpload $uploadFile): string
    {
        //Check error value.
        switch ($uploadFile->error) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new \RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new \RuntimeException('Exceeded filesize limit.');
            default:
                throw new \RuntimeException('Unknown errors.');
        }

        //You should also check filesize here.
        if ($uploadFile->size > 2000000) {
            throw new \RuntimeException('Exceeded filesize limit.');
        }

        //DO NOT TRUST mime VALUE !!, check MIME Type by yourself.
        $fInfo = new \finfo(FILEINFO_MIME_TYPE);
        $extension = array_search(
            $fInfo->file($uploadFile->tmp_name),
            [
                'jpg' => 'image/jpeg',
                'png' => 'image/png'
            ],
            true
        );
        if ($extension === false) {
            throw new \RuntimeException('Invalid file format.');
        }

        //You should name it uniquely., DO NOT USE name value WITHOUT ANY VALIDATION !!
        $fileName = sha1_file($uploadFile->tmp_name) . uniqid('', true) . '.' . $extension;
        if (!move_uploaded_file($uploadFile->tmp_name, sprintf('./images/%s', $fileName))) {
            throw new \RuntimeException('Failed to move uploaded file.');
        }

        return $fileName;
    }

    /**
     * @param string $fileName
     * @return bool
     * @throws \RuntimeException
     */
    public function delete(string $fileName): bool
    {
        //Unlink the file from the server
        $removed = unlink('./images/' . $fileName);

        if ($removed === false) {
            throw new \RuntimeException('Something went wrong with removing the image');
        }

        return true;
    }
}
