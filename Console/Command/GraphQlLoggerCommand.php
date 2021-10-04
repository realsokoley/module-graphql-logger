<?php

/**
 * @author Sokoley
 * @copyright 2021 Sokoley
 * @package Sokoley_GraphQlLogger
 */
namespace Sokoley\GraphQlLogger\Console\Command;

use Magento\Framework\Filesystem\Io\File;
use Sokoley\GraphQlLogger\Model\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GraphQlLoggerCommand extends Command
{
    /**
     * command option
     */
    const LOGGER_STATUS = 'log';

    /**
     * logger start option value
     */
    const OPTION_LOG_START_VALUE = 'start';

    /**
     * logger stop option value
     */
    const OPTION_LOG_STOP_VALUE = 'stop';

    /**
     * command name
     */
    const COMMAND_NAME = 'graphql:logger';

    /**
     * Success message
     */
    const ERROR_MESSAGE = 'Something went wrong while starting the graphql logger.';

    /**
     * @var File
     */
    private $filesystem;

    /**
     * @param File $filesystem
     * @param null $name
     */
    public function __construct(
        File $filesystem,
        $name = null
    ) {
        parent::__construct($name);
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)->setDescription('Enable GraphQL Logging');
        $this->addOption(
            self::LOGGER_STATUS,
            null,
            InputOption::VALUE_REQUIRED,
            'Start or Stop logger',
        );
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logOption = $input->getOption(self::LOGGER_STATUS);
        switch ($logOption) {
            case self::OPTION_LOG_START_VALUE:
                $this->filesystem->write(BP . '/' . Logger::GRAPHQL_LOGGER_FLAG_FILE, '1');
                if ($this->filesystem->fileExists(BP . '/' . Logger::GRAPHQL_LOGGER_FLAG_FILE)) {
                    $output->writeln('<info>GraphQL Logger enabled</info>');

                    return;
                }
                $output->writeln('<error>' . self::ERROR_MESSAGE . '</error>');
                break;
            case self::OPTION_LOG_STOP_VALUE:
                $this->filesystem->rm(BP . '/' . Logger::GRAPHQL_LOGGER_FLAG_FILE);
                if (!$this->filesystem->fileExists(BP . '/' . Logger::GRAPHQL_LOGGER_FLAG_FILE)) {
                    $output->writeln('<info>GraphQL Logger disabled</info>');

                    return;
                }
                $output->writeln('<error>' . self::ERROR_MESSAGE . '</error>');
                break;
            default:
                $output->writeln('<error>Possible options for "--log" are "' . self::OPTION_LOG_START_VALUE . '" or "' . self::OPTION_LOG_STOP_VALUE . '"</error>');
        }
    }
}
