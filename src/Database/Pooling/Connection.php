<?php

namespace SF\Database\Pooling;

use SF\Contracts\Database\Connection as ConnectionInterface;
use SF\Contracts\Database\Statement as StatementInterface;
use SF\Pool\PooledConnection;

class Connection implements ConnectionInterface
{

    protected $pooledConnection;

    public function __construct(PooledConnection $pooledConnection)
    {
        $this->pooledConnection = $pooledConnection;
    }


    public function begin()
    {
        return $this->pooledConnection->begin();
    }

    public function commit()
    {
        return $this->pooledConnection->commit();
    }

    public function rollback(): bool
    {
        return $this->pooledConnection->rollback();
    }

    public function prepare(string $sql): StatementInterface
    {
        return new Statement($this, $this->pooledConnection->prepare($sql));
    }

    public function query(string $sql)
    {
        return $this->pooledConnection->query($sql);
    }

    public function close(): void
    {
        $this->pooledConnection->close();
        $this->pooledConnection = null;
    }

    public function isClosed(): bool
    {
        if ($this->pooledConnection === null) {
            return true;
        } else {
            return $this->pooledConnection->isClosed();
        }
    }


}