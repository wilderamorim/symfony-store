<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    private $folder;

    public function __construct(string $uploadDir)
    {
        $this->folder = $uploadDir;
    }

    public function upload($file, string $folder)
    {
        if (is_array($file)) {
            $files = [];
            foreach ($file as $item) {
                $files[] = $this->move($item, $folder);
            }

            return $files;
        }

        return $this->move($file, $folder);
    }

    private function move($file, string $folder): string
    {
        $folder = $this->folder . '/' . ($folder[0] == '/' ? mb_substr($folder, 1) : $folder);
        $fileName = $this->makeNewName($file);

        $file->move($folder, $fileName);

        return $fileName;
    }

    private function makeNewName(UploadedFile $file): string
    {
        return sha1($file->getClientOriginalName()) . uniqid() . '.' . $file->guessExtension();
    }
}