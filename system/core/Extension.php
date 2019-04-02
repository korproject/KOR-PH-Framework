<?php

class Extension
{
    protected $hooks = [];

    public function addHook(string $hook, string $callback, mixed $params = null, int $priority = 1)
    {
        if ($hook && $callback){
            $this->hooks[$hook][$callback]['priority'] = $priority;
            $this->hooks[$hook][$callback]['params'] = $params;
        }
    }

    public function callHook(string $hook, object $extensionClass)
    {
        if (isset($this->hook[$hook])){
            arsort($this->hooks);

            $result = null;

            foreach ($this->hooks[$hook] as $callback => $data) {
                if (method_exists($extensionClass, $callback)){
                    $result = $extensionClass->$callback($data['params']);
                }
            }

            return $result;
        }

        return false;
    }
}
