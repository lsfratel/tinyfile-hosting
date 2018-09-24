<?php

namespace app\core;

use app\models\File;
use app\models\Link;
use mako\config\Config;
use mako\http\UploadedFile;

class Upload
{
    /**
     * Handle all uploads.
     *
     * @access  public
     * @param   \mako\http\UploadedFile $file The file uploaded
     * @param   \mako\config\Config $config
     * @return  array
     */
    public static function handle(UploadedFile $file, Config $config): array
    {
        $maxFileSize = $config->get('uploads.max_file_size');

        if (!$file || !$file->isUploaded()) {
            $result = [
                "success" => false,
                "message" => "Unable to upload this file. Try again later.",
            ];
        } else if ($file->getReportedSize() > $maxFileSize) {
            $result = [
                "success" => false,
                "message" => "The file size exceeds the limit.",
            ];
        } else if ($file->hasError()) {
            $result = [
                "success" => false,
                "message" => $file->getErrorMessage(),
            ];
        } else {
            $id = substr(md5(microtime(true) . $file->getName()), 0, 10);
            $ext = pathinfo($file->getName(), PATHINFO_EXTENSION);
            $name = $file->getName();
            $size = $file->getReportedSize();
            $type = $file->getReportedType();
            empty($ext) ? $newName = $id : $newName = $id . '.' . $ext;

            $fileModel = File::create([
                "id" => $id,
                "name" => $newName,
                "name_original" => $name,
                "ext" => $ext,
                "size" => $size,
                "type" => $type,
                "uploaded_at" => new \DateTime(),
                "expire_at" => new \DateTime('+10 years'),
                "last_access" => new \DateTime(),
                "delete_token" => md5(microtime(true) . $file->getName()),
            ]);

            if ($fileModel) {
                $file->moveTo(UPLOADS_PATH . $newName);
            } else {
                $result = [
                    "success" => false,
                    "message" => "Unable to upload this file. Try again later.",
                ];
            }

            $result = [
                "success" => true,
                "message" => "File uploaded successfully",
                "id" => $id,
                "delete_token" => $fileModel->delete_token,
                "file" => [
                    "ext" => $ext,
                    "name" => $name,
                    "size" => $size,
                    "type" => $type,
                    "newName" => $newName,
                ],
            ];
        }

        return $result;
    }

    /**
     * Prepare upload to download.
     *
     * @access  public
     * @param   string $id The file ID from database
     * @return  null|array
     */
    public static function get(string $id): ?array
    {
        $file = File::get($id);

        if (!$file) {
            return null;
        }

        $file->last_access = new \DateTime();
        $file->save();

        $link = Link::create([
            "file_id" => $file->id,
            "expire_at" => new \DateTime('+1 hour'),
            "created_at" => new \DateTime(),
        ]);

        return ["link" => $link, "file" => $file];
    }

    /**
     * Get the file info for delete.
     *
     * @access  public
     * @param   string $token The generated token for the file
     * @return  null|object
     */
    public static function delete(string $token): ?object
    {
        $file = File::where('delete_token', '=', $token)->including('links')->first();

        if (!$file) {
            return null;
        }

        return $file;
    }

    /**
     * Get the file link.
     * Validate the token, return the link.
     *
     * @access  public
     * @param   string $token The generated token for the file
     * @return  bool|object
     */
    public static function download(string $token): ?object
    {
        $link = Link::where('token', '=', $token)->including('file')->first();

        if (!$link || $link->expire_at < date('Y-m-d H:i:s')) {
            return null;
        }

        return $link;
    }
}
