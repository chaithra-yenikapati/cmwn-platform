<?php

namespace Import\Importer\Nyc\Parser;

use Group\GroupInterface;
use Group\Service\UserGroupServiceInterface;
use Import\Importer\Nyc\ClassRoom\ClassRoomRegistry;
use Import\Importer\Nyc\Parser\Excel\ClassWorksheetParser as ClassParser;
use Import\Importer\Nyc\Parser\Excel\StudentWorksheetParser as StudentParser;
use Import\Importer\Nyc\Parser\Excel\TeacherWorksheetParser as TeacherParser;
use Import\Importer\Nyc\Students\StudentRegistry;
use Import\Importer\Nyc\Teachers\TeacherRegistry;
use Zend\Log\Logger;

/**
 * Class DoePreProcessor
 */
class DoeParser extends AbstractParser
{
    /**
     * @var UserGroupServiceInterface
     */
    protected $userGroupService;

    /**
     * @var string name of the file to process
     */
    protected $fileName;

    /**
     * @var ClassRoomRegistry
     */
    protected $classRegistry;

    /**
     * @var TeacherRegistry
     */
    protected $teacherRegistry;

    /**
     * @var StudentRegistry
     */
    protected $studentRegistry;

    /**
     * @var ClassParser
     */
    protected $classParser;

    /**
     * @var TeacherParser
     */
    protected $teacherParser;

    /**
     * @var StudentParser;
     */
    protected $studentParser;

    /**
     * @var GroupInterface; The school this parser is for
     */
    protected $school;

    /**
     * DoeParser constructor.
     *
     * @param ClassRoomRegistry $classRegistry
     * @param TeacherRegistry $teacherRegistry
     * @param StudentRegistry $studentRegistry
     * @param UserGroupServiceInterface $userGroupService
     */
    public function __construct(
        ClassRoomRegistry $classRegistry,
        TeacherRegistry $teacherRegistry,
        StudentRegistry $studentRegistry,
        UserGroupServiceInterface $userGroupService
    ) {
        $this->classRegistry    = $classRegistry;
        $this->teacherRegistry  = $teacherRegistry;
        $this->studentRegistry  = $studentRegistry;
        $this->userGroupService = $userGroupService;
        $this->setLogger(new Logger(['writers' => [['name' => 'noop']]]));
    }

    /**
     * Sets the school this parser is for
     *
     * @param GroupInterface $school
     */
    public function setSchool(GroupInterface $school)
    {
        $this->school = $school;
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
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * PreProcess a file
     */
    public function preProcess()
    {
        $this->getLogger()->info('Starting to process file: ' . $this->getFileName());
        if ($this->fileName === null) {
            throw new \RuntimeException('Cannot pre process: No File name set');
        }

        $reader      = \PHPExcel_IOFactory::load($this->fileName);
        $foundSheets = [];
        foreach ($reader->getAllSheets() as $sheet) {
            $this->getLogger()->debug('Found Sheet: ' . $sheet->getTitle());
            $foundSheets[$sheet->getTitle()] = true;
            switch ($sheet->getTitle()) {
                case ClassParser::SHEET_NAME:
                    $this->buildClassSheet($sheet);
                    break;

                case TeacherParser::SHEET_NAME:
                    $this->buildTeacherSheet($sheet);
                    break;

                case StudentParser::SHEET_NAME:
                    $this->buildStudentSheet($sheet);
                    break;

                default:
                    $this->addWarning(
                        sprintf(
                            'Sheet with the name "%s" was found and will be ignored',
                            $sheet->getTitle()
                        )
                    );
            }
        }

        $this->parseFoundSheets($foundSheets);
    }

    /**
     * Parses the sheets that were found
     *
     * @param array $foundSheets
     */
    protected function parseFoundSheets(array $foundSheets)
    {
        foreach ([ClassParser::SHEET_NAME, TeacherParser::SHEET_NAME, StudentParser::SHEET_NAME] as $requiredSheet) {
            $this->getLogger()->debug('Checking for sheet: ' . $requiredSheet);
            if (!isset($foundSheets[$requiredSheet])) {
                $this->addError(
                    sprintf('Required sheet "%s" is missing', $requiredSheet)
                );
            }

            $this->getLogger()->debug('Sheet found');
        }

        if ($this->hasErrors()) {
            $this->getLogger()->notice('Parsing failed, initial checks produced errors');
            return;
        }

        $this->parseClassSheet();
        $this->parseTeacherSheet();
        $this->parseStudentSheet();

        if (!$this->hasErrors()) {
            $this->createAssociationActions();
        }
    }

    protected function createAssociationActions()
    {
        $this->getLogger()->info('Creating associations to classes');
    }

    /**
     * @return ClassParser
     * @codeCoverageIgnore
     */
    public function getClassParser()
    {
        return $this->classParser;
    }

    /**
     * @return TeacherParser
     * @codeCoverageIgnore
     */
    public function getTeacherParser()
    {
        return $this->teacherParser;
    }

    /**
     * @return StudentParser
     * @codeCoverageIgnore
     */
    public function getStudentParser()
    {
        return $this->studentParser;
    }

    /**
     * Parses the class sheet
     *
     * Merges the errors, warnings and actions
     */
    protected function parseClassSheet()
    {
        $this->getLogger()->info('Parsing Class Sheet');
        $this->getClassParser()->preProcess();
        if ($this->getClassParser()->hasWarnings()) {
            $this->warnings = array_merge($this->warnings, $this->getClassParser()->getWarnings());
        }

        if ($this->getClassParser()->hasErrors()) {
            $this->errors = array_merge($this->errors, $this->getClassParser()->getErrors());
        }
    }

    /**
     * Parses the teacher sheet
     *
     * Merges the errors, warnings and actions
     */
    protected function parseTeacherSheet()
    {
        $this->getTeacherParser()->preProcess();
        if ($this->getTeacherParser()->hasWarnings()) {
            $this->warnings = array_merge($this->warnings, $this->getTeacherParser()->getWarnings());
        }

        if ($this->getTeacherParser()->hasErrors()) {
            $this->errors = array_merge($this->errors, $this->getTeacherParser()->getErrors());
        }
    }

    /**
     * Parses the student sheet
     *
     * Merges the errors, warnings and actions
     */
    protected function parseStudentSheet()
    {
        $this->getStudentParser()->preProcess();
        if ($this->getStudentParser()->hasWarnings()) {
            $this->warnings = array_merge($this->warnings, $this->getStudentParser()->getWarnings());
        }

        if ($this->getStudentParser()->hasErrors()) {
            $this->errors = array_merge($this->errors, $this->getStudentParser()->getErrors());
        }
    }

    /**
     * @param \PHPExcel_Worksheet $worksheet
     */
    protected function buildClassSheet(\PHPExcel_Worksheet $worksheet)
    {
        $this->classParser = new ClassParser($worksheet, $this->classRegistry);
    }

    /**
     * @param \PHPExcel_Worksheet $worksheet
     */
    protected function buildTeacherSheet(\PHPExcel_Worksheet $worksheet)
    {
        $this->teacherParser = new TeacherParser($worksheet, $this->teacherRegistry, $this->classRegistry);
    }

    /**
     * @param \PHPExcel_Worksheet $worksheet
     */
    protected function buildStudentSheet(\PHPExcel_Worksheet $worksheet)
    {
        $this->studentParser = new StudentParser($worksheet, $this->studentRegistry, $this->classRegistry);
    }
}
