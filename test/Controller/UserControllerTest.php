<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Controller;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;

class UserControllerTest extends TestCase
{
    private UserController $userController;
    private UserRepository $userRepository;

    protected function setUp():void
    {
        $this->userController = new UserController();

        $this->userRepository  = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();

        putenv("mode=test");
    }
    public function testRegister()
    {
        $this->userController->register();

        $this->expectOutputRegex("[Register]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[Name]");
        $this->expectOutputRegex("[Password]");
        $this->expectOutputRegex("[Register new User]");
    }

    public function  testPostRegisterSuccess()
    {
        $_POST['id'] = 'eko';
        $_POST['name'] = 'Eko';
        $_POST['password'] = 'rahasia';

        $this->userController->postRegister();

        $this->expectOutputRegex("[Location:: /users/login]");
    }

    public function  testPostRegisterValidationError()
    {
        $_POST['id'] = '';
        $_POST['name'] = 'Eko';
        $_POST['password'] = 'rahasia';

        $this->userController->postRegister();

        $this->expectOutputRegex("[Register]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[Name]");
        $this->expectOutputRegex("[Password]");
        $this->expectOutputRegex("[Register new User]");
        $this->expectOutputRegex("[Id, Name, Password can not blank]");
    }

    public function  testPostRegisterDuplicate()
    {
        $user = new User();
        $user->id = 'eko';
        $user->name = 'Eko';
        $user->password = 'rahasia';

        $this->userRepository->save();

        $_POST['id'] = 'eko';
        $_POST['name'] = 'Eko';
        $_POST['password'] = 'rahasia';

        $this->userController->getRegister();

        $this->expectOutputRegex("[Register]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[Name]");
        $this->expectOutputRegex("[Password]");
        $this->expectOutputRegex("[Register new User]");
        $this->expectOutputRegex("[User Id already exists]");
    }

    public function testLogin()
    {
        $this->userController->login();

        $this->expectOutputRegex("[Login user]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[Password]");

    }

    public function testLoginSuccess()
    {

        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $_POST['id'] = 'eko';
        $_POST['password'] = 'rahasia';

        $this->userController->postLogin();

        $this->expectOutputRegex("[Location:/]");
    }

    public function testLoginValidationError()
    {
        $_POST['id'] = '';
        $_POST['password'] = '';
        $this->userController->postLogin();

        $this->expectOutputRegex("[Login user]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[Password]");
        $this->expectOutputRegex("[Id, Password can not blank]");
    }

    public function testLoginUserNotFound()
    {
        $_POST['id'] = 'not found';
        $_POST['password'] = 'not found';
        $this->userController->postLogin();

        $this->expectOutputRegex("[Login user]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[Password]");
        $this->expectOutputRegex("[Id and password is wrong]");
    }

    public function testLoginWrongPassword()
    {

        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $_POST['id'] = '';
        $_POST['password'] = '';
        $this->userController->postLogin();

        $this->expectOutputRegex("[Login user]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[Password]");
        $this->expectOutputRegex("[Id, Password can not blank]");
    }
}
