<?php

/**
 * Simple Validation Class
 * Supports PHP 5 >= 5.1.0 && PHP 7.x.x
 *
 * 
 * MIT License
 * 
 * Copyright (c) 2018 EgoistDeveloper
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of 
 * this software and associated documentation files (the "Software"), to deal in 
 * the Software without restriction, including without limitation the rights to use, 
 * copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH 
 * THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 * @category   Benchmark
 * @package    PHPBenchmarkClass
 * @author     Original Author <hiam@egoist.dev>
 * @copyright  2018 EgoistDeveloper
 * @license    MIT
 * @version    0.1
 * @link       https://github.com/EgoistDeveloper/PHPBenchmarkClass
 */

class Benchmark
{
    /**
     * Number of digits after a comma
     */
    public $diffRound = 3;

    /**
     * Simple benckmark function
     *
     * @param function $function: anonymous function
     * @param int $loopCount: max count of repeats
     * @return array
     */
    public function runBenchmark($function, int $loopCount = 1000)
    {
        if (is_callable($function) && $loopCount > 0) {
            $startTime = microtime(true);

            for ($i = 0; $i < $loopCount; $i++) {
                $function();
            }

            $endTime = microtime(true);
            $diff = $endTime - $startTime;

            $result = [
                'start' => $startTime,
                'end' => $endTime,
                'diff' => $diff,
            ];

            if ($this->diffRound > 0) {
                $result['rounded_diff'] = round($diff, $this->diffRound);
            }

            return $result;
        }

        return false;
    }

    /**
     * Serial
     *
     * @param function $function: anonymous function
     * @param int $loopCount: max count of repeats
     * @return array
     */
    public function runBenchMarks($functions, int $loopCount = 1000)
    {
        if (is_array($functions) && count($functions) > 0) {
            $results = [];

            // simple serial benchmarks
            foreach ($functions as $key => $function) {
                if (is_callable($function)) {
                    $result = $this->runBenchmark($function, $loopCount);

                    array_push($results, [
                        $key => $result,
                    ]);
                }
            }

            return $results;
        }

        return false;
    }

    /**
     * Simple benckmark function
     *
     * @param function $function: anonymous function
     * @param int $loopCount: max count of repeats
     * @return array
     */
    public function runBenchMarkCombinations($functions, int $loopCount = 1000)
    {
        if (is_array($functions) && count($functions) > 0) {
            $results = [];

            $functionKeys = [];

            // get function names as key
            foreach ($functions as $key => $value) {
                array_push($functionKeys, $key);
            }

            // get function name combinations as array
            $allCombinations = $this->getArraysOfCombinations($functionKeys);

            // loop in this combinations
            foreach ($allCombinations as $combination) {
                // merge function names for listing
                $mergeCombinations = implode(', ', $combination);
                $results[$mergeCombinations] = [];

                // loop in functions
                foreach ($combination as $key => $functionName) {
                    $function = $functions[$functionName];

                    if (is_callable($function)) {
                        $result = $this->runBenchmark($function, $loopCount);

                        $results[$mergeCombinations][$functionName] = $result;
                    }
                }
            }

            return $results;
        }

        return false;
    }

    /**
     * Get arrays of combinations
     *
     * @param array $array
     * @return array
     * @see source: https://www.oreilly.com/library/view/php-cookbook/1565926811/ch04s25.html
     */
    private function getArraysOfCombinations($array)
    {
        if ($array) {
            $combinations = [[]];

            foreach ($array as $element) {
                foreach ($combinations as $combination) {
                    array_push($combinations, array_merge(array($element), $combination));
                }
            }

            unset($combinations[0]);
            $combinations = array_values($combinations);

            return $combinations;
        }

        return false;
    }
}
