<?php

class IndexController extends BaseController
{
    public function __construct($file, $className, $user)
    {
        parent::__construct($file, $className, $user);
        $this->benchmarks();

        $this->result['testa'] = 'KOR PHFramework';
    }

    public function benchmarks()
    {
        $benchmark = new Benchmark();
        $results = $benchmark->runBenchMarkCombinations([
            'x1' => function(){
                $var = explode(' ', 'PHP: microtime - Manual');
            },
            'x2' => function(){
                $var = explode(' ', 'PHP: microtime - Manual + PHP: microtime - Manual');
            },
            'x3' => function(){
                $var = explode(' ', 'PHP: microtime - Manual + PHP: microtime - Manual + PHP: microtime - Manual');
            }
        ], 100000);

        print_r($results);


        $results1 = $benchmark->runBenchmark(function(){
            $var = explode(' ', 'PHP: microtime - Manual');
        }, 100000);

        $results2 = $benchmark->runBenchmark(function(){
            $var = explode(' ', 'PHP: microtime - Manual + PHP: microtime - Manual');
        }, 100000);

        print_r($results1);
        print_r($results2);
    }

    public function arrayToTable(){
        $data = new Data();
        $table = $data->arrayToTable($this->model->getData());

        $this->result['table'] = $table ? $table : 'data not found';
    }
}