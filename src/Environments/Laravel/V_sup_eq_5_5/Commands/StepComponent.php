<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5\Commands;

use Compio\Environments\Laravel\V_sup_eq_5_5\Component;
use Compio\Environments\Laravel\V_sup_eq_5_5\CommandInterface;

use Illuminate\Console\Command;

class StepComponent extends Component implements CommandInterface {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'compio:component-';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Advanced component generator Compio(`dovetail/compio`) | Step by Step';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle(){

		$this->initTemplateEngine();

		$c = $this->getTemplateEngineSelected('foundation');

		$this->initDatas($c);

		if(!empty($this->datas))
			foreach ($this->datas as $k => $val_)
				$this->generate($val_);

		return Command::SUCCESS;

	}

}
