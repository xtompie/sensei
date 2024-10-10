<?php

declare(strict_types=1);

namespace App\Media\Application\Service\CreateImageByUpload;

use Exception;
use Laminas\Diactoros\UploadedFile;
use Xtompie\Result\Error;

class UploadedImage
{
    public function __construct(
        private UploadedFile $upload,
    ) {
    }

    public function error(): ?Error
    {
        return match ($this->upload->getError()) {
            UPLOAD_ERR_OK => null,
            UPLOAD_ERR_INI_SIZE => Error::of(UploadedFile::ERROR_MESSAGES[UPLOAD_ERR_INI_SIZE], 'ini_size', 'upload'),
            UPLOAD_ERR_FORM_SIZE => Error::of(UploadedFile::ERROR_MESSAGES[UPLOAD_ERR_FORM_SIZE], 'form_size', 'upload'),
            UPLOAD_ERR_PARTIAL => Error::of(UploadedFile::ERROR_MESSAGES[UPLOAD_ERR_PARTIAL], 'partial', 'upload'),
            UPLOAD_ERR_NO_FILE => Error::of(UploadedFile::ERROR_MESSAGES[UPLOAD_ERR_NO_FILE], 'no_file', 'upload'),
            UPLOAD_ERR_NO_TMP_DIR => Error::of(UploadedFile::ERROR_MESSAGES[UPLOAD_ERR_NO_TMP_DIR], 'no_tmp_dir', 'upload'),
            UPLOAD_ERR_CANT_WRITE => Error::of(UploadedFile::ERROR_MESSAGES[UPLOAD_ERR_CANT_WRITE], 'cant_write', 'upload'),
            UPLOAD_ERR_EXTENSION => Error::of(UploadedFile::ERROR_MESSAGES[UPLOAD_ERR_EXTENSION], 'extension', 'upload'),
            default => throw new Exception(),
        };
    }

    public function mimeType(): ?string
    {
        $filePath = $this->upload->getStream()->getMetadata('uri');
        if (!is_string($filePath)) {
            return null;
        }
        $mimeType = mime_content_type($filePath);
        return $mimeType !== false ? $mimeType : null;
    }

    public function name(): ?string
    {
        $filename = $this->upload->getClientFilename();
        if ($filename === null) {
            return null;
        }

        return pathinfo($filename, PATHINFO_FILENAME);
    }

    public function moveTo(string $path): void
    {
        $this->upload->moveTo($path);
    }
}
