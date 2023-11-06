<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Middleware;

use ProgrammerZamanNow\Belajar\PHP\MVC\config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\SessionService;

class MustLoginMiddleware implements Middleware
{
    private SessionService $sessionService;

    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService=new SessionService();
    }

    function before(): void
    {
       $user = $this->sessionService->current();
       if($user != null){
           View::redirect('/users/login');
       }
    }
}