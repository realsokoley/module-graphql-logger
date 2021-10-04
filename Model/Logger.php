<?php

/**
 * @author Sokoley
 * @copyright 2021 Sokoley
 * @package Sokoley_GraphQlLogger
 */
namespace Sokoley\GraphQlLogger\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

class Logger
{
    /**
     * Logger File
     */
    const GRAPHQL_LOGGER_FLAG_FILE = 'var/graphqllogger.flag';

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var File
     */
    private $filesystem;

    /**
     * @var PsrLoggerInterface
     */
    private $logger;

    /**
     * Logger constructor.
     * @param RemoteAddress $remoteAddress
     * @param RequestInterface $request
     * @param File $filesystem
     * @param PsrLoggerInterface $logger
     */
    public function __construct(
        RemoteAddress $remoteAddress,
        RequestInterface $request,
        File $filesystem,
        PsrLoggerInterface $logger
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->request = $request;
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    /**
     * @param $source
     * @return void
     */
    public function log($source)
    {
        if (!$this->filesystem->fileExists(BP . '/' . self::GRAPHQL_LOGGER_FLAG_FILE)) {
            return;
        }
        $ip = $this->remoteAddress->getRemoteAddress();
        $referer = $this->request->getServer('HTTP_REFERER');
        $this->logger->log(MonologLogger::INFO, 'Query', [$source, $ip, $referer]);
    }
}
