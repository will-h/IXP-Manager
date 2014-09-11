<?php

namespace IXP\Commands\Ping;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use \App      as App;
use \D2EM     as D2EM;
use \Log      as Log;

class SwitchPing extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'ping:switch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Ping all member interfaces on a switch.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info( 'Looking up switch in database...' );

		if( !( $switch = $this->resolveSwitchName() ) )
			return -1;

		$ips = D2R( 'Switcher' )->getConnectedAddresses( $switch->getId() );
		$this->info( 'Found ' . count( $ips ) . ' IP addresses...' );
		$icmp = new \IXP\Net\ICMP;

		foreach( $ips as $ip ) {

			$msg = "{$ip['ipaddr']} on {$ip['portname']} for {$ip['custname']}: ";

			if( !$ip['canping'] ) {
				$this->comment( $msg . 'Skipping as pings disabled' );
				continue;
			}

			if( $icmp->ping($ip['ipaddr'],1) == 1 )
				$this->info( $msg . '1/1 (sent/received)' );
			else
				$this->error( $msg . '0/1 (sent/received)' );
		}
	}


/**         'ipaddr'    => 'x.x.x.x / x:x::x',
	*         'custname'  => 'Abbreviated customer name',
	*         'vlanname'  => 'Vlan name',
	*         'vlantag'   => 'vlan tag',
	*         'portname'  => 'switchport ifName',
	*         'canping'   => bool
	*     ]*/

	private function resolveSwitchName()
	{
		$switch = D2R( 'Switcher' )->findByNameStartsWith( $this->argument( 'switch' ) );

		if( !count( $switch ) ) {
			$this->error( 'No switch with name beginning ' . $this->argument( 'switch' ) . ' found.' );
			return false;
		}

		if( count( $switch ) > 1 ) {
			$this->comment( 'Multiple switches with name beginning ' . $this->argument( 'switch' ) . ' found:' );
			foreach( $switch as $s )
				$this->comment( '  - ' . $s->getName() );

			return false;
		}

		return array_shift( $switch );
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			// array('example', InputArgument::REQUIRED, 'An example argument.'),
			[ 'switch', InputArgument::REQUIRED, 'The name of the switch to ping (or starts with if no excat match)' ]
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
