<?php

declare(strict_types=1);

namespace App\Api\Action\Movement;

use App\Entity\User;
use App\Service\Movement\DownloadFileService;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadFile
{
    private const CONTENT_TYPE = 'application/octet-stream';
    
    private DownloadFileService $fileService;

    public function __construct(DownloadFileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function __invoke(Request $request, User $user): Response
    {
        $filePath = RequestService::getField($request, 'filePath');
        $file = $this->fileService->downloadFile($user, $filePath);

        $response = new Response($file);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            sprintf('movement-file.%s', explode('.', $filePath)[1])
        );

        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', self::CONTENT_TYPE);

        return $response;
    }
}