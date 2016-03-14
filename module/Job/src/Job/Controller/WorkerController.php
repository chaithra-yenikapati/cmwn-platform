<?php

namespace Job\Controller;

use Job\Service\ResqueWorker;
use Zend\Console\Request as ConsoleRequest;
use Zend\Log\Logger;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;
use Zend\Log\LoggerInterface;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Controller\AbstractConsoleController as ConsoleController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class WorkerController
 * @method
 */
class WorkerController extends ConsoleController implements LoggerAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $services;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * WorkerController constructor.
     * @param ResqueWorker $worker
     */
    public function __construct(ServiceLocatorInterface $services)
    {
        $this->services = $services;
    }

    /**
     * Set logger instance
     *
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        if ($this->logger === null) {
            $this->setLogger(new Logger(['writers' => [['name' => 'noop']]]));
        }

        return $this->logger;
    }

    public function workAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('Invalid Request');
        }

        $this->getLogger()->addWriter(new Stream(STDOUT));

        $queue    = [$request->getParam('queue', 'default')];
        $interval = $request->getParam('interval', 5);
        $worker   = new ResqueWorker($queue, $this->services);
        $worker->setLogger($this->getLogger());

        $this->getLogger()->notice('Starting Worker');
        $worker->work($interval);
    }
}
