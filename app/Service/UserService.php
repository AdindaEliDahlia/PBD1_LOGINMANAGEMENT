<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Service;

use Cassandra\Exception\ValidationException;
use ProgrammerZamanNow\Belajar\PHP\MVC\config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserLoginRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserLoginResponse;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserProfileUpdateRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserProfileUpdateRespons;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->validateUserRegistrationRequest($request);

        try {
            Database::beginTranstaction();
            $user = $this->userRepository->findById($request);
            if ($user != null) {
                throw new ValidationException("User id already exists");
            }

            $user = new User();
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $response = new UserRegisterResponse();
            $response->user = $user;
            Database::commitTranstaction();
            return $response;
        } catch (Exception $exception) {
            Database::roolbackTranstaction();
            throw $exception;
        }
    }

    private function validateUserRegistrationRequest(UserRegisterRequest $request)
    {
        if ($request->id = null || $request->name = null || $request->password = null ||
                    trim($request->id) == "" || trim($request->name) == "" || trim($request->password) == "") {
            throw new  ValidationException("Id, Name, Password can not blank");
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validationLoginRequest($request);

        $user = $this->userRepository->findById($request->id);
        if ($user == null) {
            throw new ValidationException("Id or password is wrong");
        }

        if (password_verify($request->password, $user->password)) {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        } else {
            throw new ValidationException("Id or password is wrong");
        }
    }

    private function validationLoginRequest(UserLoginRequest $request)
    {
        if ($request->id = null || $request->password = null ||
                trim($request->id) == "" || trim($request->password) == "") {
            throw new  ValidationException("Id, Password can not blank");
        }
    }

    public function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateRespons
    {
        $this->validateUserProfileUpdateRequest($request);
        try {
            Database::beginTranstaction();

            $user = $this->userRepository->findById($request->id);
            if ($user == null) {
                throw  new ValidationException("User is not found");
            }

            $user->name = $request->name;
            $this->userRepository->save($user);

            Database::commitTranstaction();

            $response = new UserProfileUpdateRespons();
            $response->user = $user;
            return $response;
        } catch (\Exception $exception) {
            Database::roolbackTranstaction();
            throw $exception;
        }
    }

    private function validateUserProfileUpdateRequest(UserProfileUpdateRequest $request)
    {
        if ($request->id = null || $request->name = null ||
                trim($request->id) == "" || trim($request->name) == "") {
            throw new  ValidationException("Id, Name can not blank");
        }
    }
    public function updatePassword(UserPasswordUpdateRequest $request): UserPasswordUpdateResponse
    {
        $this->validateUserPasswordUpdateRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if ($user == null) {
                throw new ValidationException("User is not found");
            }

            if (!password_verify($request->oldPassword, $user->password)) {
                throw new ValidationException("Old password is wrong");
            }

            $user->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserPasswordUpdateResponse();
            $response->user = $user;
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }
    private function validateUserPasswordUpdateRequest(UserPasswordUpdateRequest $request)
    {
        if ($request->id == null || $request->oldPassword == null || $request->newPassword == null ||
            trim($request->id) == "" || trim($request->oldPassword) == "" || trim($request->newPassword) == "") {
            throw new ValidationException("Id, Old Password, New Password can not blank");
        }
    }
}