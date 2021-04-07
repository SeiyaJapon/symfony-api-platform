<?php

declare(strict_types=1);

namespace App\Service\File;

use Exception;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\FilesystemException;
use League\Flysystem\Visibility;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FileService
{
    public const AVATAR_INPUT_NAME = 'avatar';

    private FilesystemOperator $defaultStorage;
    private LoggerInterface $logger;
    private string $mediaPath;

    public function __construct(FilesystemOperator $defaultStorage, LoggerInterface $logger, string $mediaPath)
    {
        $this->defaultStorage = $defaultStorage;
        $this->logger = $logger;
        $this->mediaPath = $mediaPath;
    }

    public function uploadFile(UploadedFile $file, string $prefix): string
    {
        $fileName = sprintf('%s/%s.%s', $prefix, sha1(uniqid()), $file->guessExtension());

        $this->defaultStorage->writeStream(
            $fileName,
            fopen($file->getPathname(), 'r'),
            ['visibility' => Visibility::PUBLIC]
        );

        return $fileName;
    }

    public function validateFile(Request $request, string $inputName): UploadedFile
    {
        if (null === $file = $request->files->get($inputName)) {
            throw new BadRequestHttpException(sprintf('Cannot get file with input name %s', $inputName));
        }

        return $file;
    }

    /**
     * @param string|null $path
     * @throws FilesystemException
     */
    public function deleteFile(?string $path): void
    {
        try {
            if (null !== $path) {
                $this->defaultStorage->delete($path);
            }
        } catch (Exception $e) {
            $this->logger->warning(sprintf('File %s not found in the storage', $path));
        }
    }
}