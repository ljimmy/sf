<?php

namespace SF\Console;

use Dotenv\Dotenv;
use SF\Http\Application;
use SF\Support\PHP;

class Command
{

    const SERVER = [
        'http' => \SF\Http\Application::class,
        'web_socket' => \SF\WebSocket\Application::class
    ];

    const DEFAULT_SERVER = Application::class;

    /**
     * @var ParseCommand
     */
	protected $opt;

	protected static $self;

	public function __construct()
	{
	    self::$self = $this;

		$this->opt = new ParseCommand();
        (new Dotenv(ROOT_DIR))->load();
	}

	public static function getSelf()
    {
        return self::$self;
    }

	public static function run()
    {
        return (new static())->bootstrap();
    }

    public function bootstrap()
    {
        switch ($this->opt->get(1)) {
            case 'start':
                $this->start();
                break;
            case 'reload':
                $this->reload();
                break;
            case 'stop':
                $this->stop();
                break;
            case 'test':
                $this->test();
                break;
            default:
                $this->writeln('command not found');
        }
    }

    private function getConfig()
    {
        $c = $this->opt->get('c');

        $c = $c === null ? getenv('config') : $c;

        if (empty($c)) {
            return [];
        }
        $config = include($c);

        return $config;
    }

    public function getPidFile()
    {
        $file = $this->opt->get('pidFile');

        if ($file === null) {
            $pidFile = getenv('pidFile');
            return $pidFile ? $pidFile : PHP::getBasePath(). DIRECTORY_SEPARATOR . '.pid';
        } else {
            return $file;
        }
    }

    public function start()
    {
        $serverName = $this->opt->get('s', '')?:getenv('service');

        $server = self::SERVER[$serverName] ?? self::DEFAULT_SERVER;

        if ($server === null) {
            $this->writeln('Unsupported Service');
        } else {
            $this->writeln('Starting...');

            $application = (new $server($this->getConfig()));
            $application->setComponentsConfig(getenv('components'));
            $application->start();
            $this->writeln('Server has stopped');
        }

    }

    public function reload()
    {
        $pid = (int) $this->opt->get('p', 0);

        $this->writeln('Reloading...');
        if ($pid) {
            posix_kill($pid, $this->opt->get('t') === 'task' ? SIGUSR2: SIGUSR1);
            $this->writeln('Done');
        } else {
            get_pid(function($master_pid, $manager_pid) {
                posix_kill($master_pid, $this->opt->get('t') === 'task' ? SIGUSR2: SIGUSR1);
                $this->writeln('Done');
            });
        }
    }

    public function stop()
    {
        $pid = (int) $this->opt->get('p', 0);
        if (empty($pid)) {
            get_pid(function($master_pid, $manager_pid){
                $this->writeln('Stopping...');
                posix_kill($master_pid, SIGTERM);
                $this->writeln('Stopped');
            });
        } else {
            $this->writeln('Stopping...');
            posix_kill($pid, SIGTERM);
            $this->writeln('Stopped');
        }

    }

    /**
     * 返回命令行参数
     * @return ParseCommand
     */
    public function getOpt()
    {
        return $this->opt;
    }

    public function writeln(string $message)
    {
        echo $message . "\n";
    }

}