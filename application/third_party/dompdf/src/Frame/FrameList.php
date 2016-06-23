<?php
namespace Dompdf\Frame;

require_once(dirname(__FILE__).'/FrameListIterator.php');
require_once(dirname(__FILE__).'/FrameTreeIterator.php');

use IteratorAggregate;

/**
 * Linked-list IteratorAggregate
 *
 * @access private
 * @package dompdf
 */
class FrameList implements IteratorAggregate
{
    /**
     * @var
     */
    protected $_frame;

    /**
     * @param $frame
     */
    function __construct($frame)
    {
        $this->_frame = $frame;
    }

    /**
     * @return FrameListIterator
     */
    function getIterator()
    {
        return new FrameListIterator($this->_frame);
    }
}
