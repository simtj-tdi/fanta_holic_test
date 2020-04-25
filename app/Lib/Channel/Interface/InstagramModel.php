<?php

class InstagramModel implements ChannelModelInterface
{

    public function load()
    {
        return \InstagramScraper\Model\Media::create();
        // TODO: Implement load() method.
    }
}