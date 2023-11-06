<?php
namespace ProgrammerZamanNow\Belajar\PHP\MVC\App {
    function header(string $value)
    {
        echo $value;
    }
}

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Middleware {

    use mysql_xdevapi\Session;
    use PHPUnit\Framework\TestCase;
    use ProgrammerZamanNow\Belajar\PHP\MVC\config\Database;
    use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
    use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;

    class MustNotLoginMiddlewareTest extends TestCase
    {
        private MustNotLoginMiddleware $middleware;
        private UserRepository $userRepository;
        private SessionRepository $sessionRepository;


        protected function setUp(): void
        {
            $this->middleware = new MustNotLoginMiddleware();
            putenv("mode=test");

            $this->userRepository = new UserRepository(Database::getConnection());
            $this->sessionRepository = new SessionRepository(Database::getConnection());

            $this->sessionRepository->deleteAll();
            $this->userRepository->deleteAll();
        }

        public function testBeforeGuest()
        {
            $this->middleware->before();
            $this->expectOutputString("");


        }

        public function testBeforeLoginUser()
        {
            $user = new User();
            $user->id = "eko";
            $user->name = "Eko";
            $user->password = "rahasia";
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);

            $this->middleware->before();
            $this->expectOutputRegex("[Location: /]");

        }
    }
}
