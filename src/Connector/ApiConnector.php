<?php

namespace Hungnguyenba\Apidriver\Connector;

use Illuminate\Database\Connectors\Connector;
use Illuminate\Database\Connectors\ConnectorInterface;

class ApiConnector extends Connector implements ConnectorInterface
{
    /**
     * Establish a database connection.
     *
     * @param  array  $config
     * @return \PDO
     */
    public function connect(array $config)
    {
        $connection = $config['host'] ?? '';

        return $connection;
    }

    /**
     * Create a DSN string from a configuration.
     *
     * Chooses socket or host/port based on the 'unix_socket' config value.
     *
     * @param  array   $config
     * @return string
     */
    protected function getDsn(array $config)
    {
        return null;
    }

    /**
     * Determine if the given configuration array has a UNIX socket value.
     *
     * @param  array  $config
     * @return bool
     */
    protected function configHasSocket(array $config)
    {
        return null;
    }

    /**
     * Get the DSN string for a socket configuration.
     *
     * @param  array  $config
     * @return string
     */
    protected function getSocketDsn(array $config)
    {
        return null;
    }

    /**
     * Get the DSN string for a host / port configuration.
     *
     * @param  array  $config
     * @return string
     */
    protected function getHostDsn(array $config)
    {
        return null;
        // extract($config, EXTR_SKIP);

        // return isset($port)
        //                 ? "mysql:host={$host};port={$port};dbname={$database}"
        //                 : "mysql:host={$host};dbname={$database}";
    }

    /**
     * Set the modes for the connection.
     *
     * @param  \PDO  $connection
     * @param  array  $config
     * @return void
     */
    protected function setModes(PDO $connection, array $config)
    {
        return null;
        // if (isset($config['modes'])) {
        //     $modes = implode(',', $config['modes']);

        //     $connection->prepare("set session sql_mode='".$modes."'")->execute();
        // } elseif (isset($config['strict'])) {
        //     if ($config['strict']) {
        //         $connection->prepare("set session sql_mode='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'")->execute();
        //     } else {
        //         $connection->prepare("set session sql_mode='NO_ENGINE_SUBSTITUTION'")->execute();
        //     }
        // }
    }
}
