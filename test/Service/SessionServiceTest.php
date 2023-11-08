<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Service;

require_once __DIR__ . '/../helper/helper.php';

use mysql_xdevapi\Session;
use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;

class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp():void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->UserRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);


        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user-> id = "eko";
        $user-> name = "Eko";
        $user->password = "rahasia";
        $this->userRepository->save($user);

    }

    public function testCreate()
    {
        $session= $this->sessionService->create("eko");

        $this->expectOutputRegex("[X-PZN-SESSION: $session->id]");

        $result = $this->userRepository->findById($session->id);

        self::asserEquals("eko", $result->userId);
    }

    public function testDestroy()
    {

        $session = new Session();
        $session->id = uniqid();
        $session->userId = "eko";

        $this->sessionRepository->save($session);

        $_COOKIE[$this->sessionService::$COOKIE_NAME] = $session->id;

        $this->sessionService->destory();

        $this->expectOutputRegex("[X-PZN-SESSION: $session->id]");

        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);
    }

    public function testCurrent()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "eko";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();

        self::assertEquals($session->userId, $user->id);
    }
}


