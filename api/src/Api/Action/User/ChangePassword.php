<?php

declare(strict_types=1);

namespace App\Api\Action\User;


use App\Entity\User;
use App\Service\User\ChangePasswordService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

class ChangePassword
{
    /** @var ChangePasswordService */
    private ChangePasswordService $changePasswordService;

    public function __construct(ChangePasswordService $changePasswordService)
    {
        $this->changePasswordService = $changePasswordService;
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(Request $request, User $user) : User
    {
        return $this->changePasswordService->changePassword($request, $user);
    }
}