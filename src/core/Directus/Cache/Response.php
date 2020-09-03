<?php

namespace Directus\Cache;

class Response extends Cache
{
    protected $tags = [];

    public function tag($tags)
    {
    	//array_merge is a performance killer when running in a loop (which this method is often called from)
		//if the value is a scalar anyway, we dont need to call it
    	if (is_scalar($tags)) {
			$this->tags[] = $tags;
			return $this;
		}

        $this->tags = array_merge($this->tags, (array)$tags);

        return $this;
    }

    public function ttl($time)
    {
        $this->ttl = $time;
    }

    public function process($key, $bodyContent, $headers = [])
    {
        if ($key && !empty($this->tags)) {
            $value = ['body' => $bodyContent, 'headers' => $headers];

            return $this->set($key, $value, $this->tags, $this->defaultTtl);
        }

        return false;
    }
}
