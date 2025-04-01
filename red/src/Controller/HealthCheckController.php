<?php declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use EQS\HealthCheckProvider\DTO\CheckDetails;
use EQS\HealthCheckProvider\DTO\HealthResponse;
use EQS\HealthCheckProvider\HealthChecker\CallableHealthChecker;
use EQS\HealthCheckProvider\HealthChecker\DoctrineConnectionHealthChecker;
use EQS\HealthCheckProvider\RequestHandler;
use Interop\Amqp\AmqpConnectionFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HealthCheckController extends AbstractController
{
    public function __construct(
        private Connection $connection,
        #[Autowire(service: 'Enqueue\AmqpExt\AmqpConnectionFactory')]
        private AmqpConnectionFactory $amqpConnectionFactory
    ) {
    }

    #[Route(path: '/health_check')]
    public function __invoke(Request $request): Response
    {
        $psr17Factory = new Psr17Factory();
        $psrBridge = new HttpFoundationFactory();

        return $psrBridge->createResponse(
            (new RequestHandler(
                new HealthResponse(),
                [
                    new CallableHealthChecker(new CheckDetails('AMQP', true), fn () => $this->amqpConnectionFactory->createContext()),
                    new DoctrineConnectionHealthChecker(new CheckDetails('Database', true), $this->connection),
                ],
                $psr17Factory,
                $psr17Factory,
            ))
                ->handle((new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory))
                ->createRequest($request)),
        );
    }
}
