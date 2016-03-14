<?php

namespace Import\Importer\Nyc;

use Import\Importer\Nyc\Parser\DoeParser;
use Import\ImporterInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;
use Zend\Log\LoggerAwareInterface;

/**
 * Class NycDoeImporter
 *
 * @package Import\Importer
 */
class DoeImporter implements LoggerAwareInterface, EventManagerAwareInterface, ImporterInterface
{
    use EventManagerAwareTrait;

    /**
     * @var string the file name to process
     */
    protected $fileName;

    /**
     * @var DoeParser
     */
    protected $parser;

    /**
     * @var LoggerInterface;
     */
    protected $logger;

    /**
     * DoeImporter constructor.
     * @param DoeParser $parser
     */
    public function __construct(DoeParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->parser->setLogger($logger);
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if ($this->logger === null) {
            $this->setLogger(new Logger(['writers' => [['name' => 'noop']]]));
        }

        return $this->logger;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return DoeImporter
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Performs the work for the job
     */
    public function perform()
    {
        $this->getLogger()->notice('Importing file: ' . $this->getFileName());
        $this->parser->setFileName($this->fileName);
        $event = new Event('nyc.import.excel', $this->parser);
        if ($this->getEventManager()->trigger($event)->stopped()) {
            $this->getLogger()->notice('Response caused processing to stop');
            return;
        }

        $this->parser->preProcess();
        if ($this->parser->hasErrors()) {
            $this->getLogger()->alert('Processor has errors');
            $event->setName('nyc.import.excel.error');
            $this->getEventManager()->trigger($event);
            return;
        }

        $event->setName('nyc.import.excel.run');
        $this->getEventManager()->trigger($event);

        $actions = $this->parser->getActions();
        $actions->top();
        $this->getLogger()->info(sprintf('Got %d actions', count($actions)));
        while ($actions->valid()) {
            $actions->current()->execute();
            $actions->next();
        }

        $event->setName('nyc.import.excel.complete');
        $this->getEventManager()->trigger($event);
    }

    /**
     * Gets the data that will be passed for the job
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'file_name' => $this->getFileName()
        ];
    }

    /**
     * Returns the argumet values back to the object
     *
     * @param array $data
     * @return mixed
     */
    public function exchangeArray(array $data)
    {
        $this->setFileName(isset($data['file_name']) ? $data['file_name'] : null);
    }
}
