<?php

declare(strict_types=1);

namespace App\Api\Action\User;


use App\Entity\User;
use App\Service\Request\RequestService;
use App\Service\User\ResetPasswordService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

class ResetPassword
{
    /** @var ResetPasswordService */
    private ResetPasswordService $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    /**
     * @param Request $request
     * @param string  $id
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(Request $request, string $id) : User
    {
        return $this->resetPasswordService->reset(
            $id,
            RequestService::getField($request, 'resetPasswordToken'),
            RequestService::getField($request, 'password')
        );
    }
}