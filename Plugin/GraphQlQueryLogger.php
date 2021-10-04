<?php

/**
 * @author Sokoley
 * @copyright 2021 Sokoley
 * @package Sokoley_GraphQlLogger
 */
namespace Sokoley\GraphQlLogger\Plugin;

use Magento\Framework\GraphQl\Query\QueryProcessor;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema;
use Sokoley\GraphQlLogger\Model\Logger;

class GraphQlQueryLogger
{
    /**
     * @var Logger
     */
    private $graphqlLogger;

    /**
     * @param Logger $graphqlLogger
     */
    public function __construct(
        Logger $graphqlLogger
    ) {
        $this->graphqlLogger = $graphqlLogger;
    }

    /**
     * @param QueryProcessor $subject
     * @param array $result
     * @param \Magento\Framework\GraphQl\Schema $subject
     * @param array $result
     * @param string $subject
     * @param array $result
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface|null $subject
     * @param array $result
     * @param array|null $subject
     * @param array $result
     * @param string|null $subject
     * @param array $result
     * @return array
     */
    public function afterProcess(QueryProcessor $subject, array $result, Schema $schema, string $source, ContextInterface $contextValue = null, array $variableValues = null, string $operationName = null)
    {
        $this->graphqlLogger->log($source);

        return $result;
    }
}
