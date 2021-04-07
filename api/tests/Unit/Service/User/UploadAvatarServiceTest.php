<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Service\File\FileService;
use App\Service\User\UploadAvatarService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UploadAvatarServiceTest extends UserServiceTestBase
{
    private const IMAGE_NAME = 'aaa.png';

    /** @var FileService|MockObject */
    private $fileService;
    private UploadAvatarService $service;
    private string $mediaPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileService = $this->getMockBuilder(FileService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mediaPath = 'https://asdasd.com/';
        $this->service = new UploadAvatarService(
            $this->userRepository,
            $this->fileService,
            $this->mediaPath
        );
    }

    public function testUploadAvatar(): void
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $file = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $user = new User('name', 'name@api.com');
        $user->setAvatar('abc.png');

        $this->fileService
            ->expects($this->exactly(1))
            ->method('validateFile')
            ->with($request, FileService::AVATAR_INPUT_NAME)
            ->willReturn($file);

        $this->fileService
            ->expects($this->exactly(1))
            ->method('deleteFile')
            ->with($user->getAvatar());

        $this->fileService
            ->expects($this->exactly(1))
            ->method('uploadFile')
            ->with($file, FileService::AVATAR_INPUT_NAME)
            ->willReturn(self::IMAGE_NAME);

        $response = $this->service->uploadAvatar($request, $user);

        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals(self::IMAGE_NAME, $response->getAvatar());
    }
}