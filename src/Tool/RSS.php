<?php
/**
 * Tool_RSS Class
 *
 * Getting RSS Contents
 *
 * TODO:
 * * Unimplemented Atom structure
 * * Unimplemented tags information
 *
 * @access public
 * @author Lionheart Co., Ltd.
 * @version 1.0.0
 */

class Tool_RSS
{

    private $_datas;
    private $_posts = array();

    public function __construct($url)
    {
        $this->_datas = simplexml_load_file($url);

        if($this->_datas->entry) {
            $this->getContentsAtom();
        } else if($this->_datas->item) {
            $this->getContentsRss1();
        } else if($this->_datas->channel->item) {
            $this->getContentsRss2();
        }
    }

    /**
     * Get RSS contents (Atom structure)
     * TODO: Unimplemented
     *
     * @return void
     */
    protected function getContentsAtom()
    {
        $index = 0;
    }

    /**
     * Get RSS contents (RSS1 structure)
     *
     * @return void
     */
    protected function getContentsRss1()
    {
        $index = 0;

        foreach($this->_datas->item as $post) {
            $dcData = $post->children('http://purl.org/dc/elements/1.1/');

            $row = array(
                'title'       => $post->title->__toString(),
                'pubDate'     => $dcData->date->__toString(),
                'datetime'    => new DateTime($dcData->date->__toString()),
                'link'        => $post->link->__toString(),
                'description' => html_entity_decode($post->description->__toString()),
                'content'     => html_entity_decode($this->_datas->item[$index]->children('content', true)->encoded->__toString()),
                'author'      => $dcData->creator->__toString(),
                'category'    => array(),
                'tag'         => array(), // TODO: Unimplemented
            );

            // Check PR Entry
            if(strpos($row['title'], 'PR:') !== false) {
                continue;
            }

            foreach($dcData->subject as $category) {
                $row['category'][] = $category->__toString();
            }

            $this->_posts[] = $row;
            $index ++;
        }
    }

    /**
     * Get RSS contents (RSS2 structure)
     *
     * @return void
     */
    protected function getContentsRss2()
    {
        $index = 0;
        foreach($this->_datas->channel->item as $post) {
            $dcData = $post->children('http://purl.org/dc/elements/1.1/');

            $row = array(
                'title'       => $post->title->__toString(),
                'pubDate'     => $post->pubDate->__toString(),
                'datetime'    => new DateTime($post->pubDate->__toString()),
                'link'        => $post->link->__toString(),
                'description' => html_entity_decode($post->description->__toString()),
                'content'     => html_entity_decode($this->_datas->channel->item[$index]->children('content', true)->encoded->__toString()),
                'author'      => $dcData->creator->__toString(),
                'category'    => array(),
                'tag'         => array(), // TODO: Unimplemented
            );

            // Check PR Entry
            if(strpos($row['title'], 'PR:') !== false) {
                continue;
            }

            foreach($post->category as $category) {
                $row['category'][] = $category->__toString();
            }

            $this->_posts[] = $row;
            $index ++;
        }
    }

    /**
     * Get row
     */
    public function fetch()
    {
        $result = current($this->_posts);
        if($result === false) {
            return false;
        }

        next($this->_posts);
        return $result;
    }

    public function fetchAll($max = 0)
    {
        $result = array();

        $index = 0;
        while($row = $this->fetch()) {
            $result[] = $row;

            $index ++;
            if($max !== 0 && $index >= $max) {
                break;
            }
        }

        return $result;
    }

}