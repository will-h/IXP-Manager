<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PatchPanel
 */
class PatchPanel
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $medium;

    /**
     * @var string
     */
    private $connector;

    /**
     * @var boolean
     */
    private $duplex_allowed;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var integer
     */
    private $u_position;

    /**
     * @var \DateTime
     */
    private $installed;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $PatchPanelPorts;

    /**
     * @var \Entities\Cabinet
     */
    private $Cabinet;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->PatchPanelPorts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return PatchPanel
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set medium
     *
     * @param string $medium
     * @return PatchPanel
     */
    public function setMedium($medium)
    {
        $this->medium = $medium;
    
        return $this;
    }

    /**
     * Get medium
     *
     * @return string 
     */
    public function getMedium()
    {
        return $this->medium;
    }

    /**
     * Set connector
     *
     * @param string $connector
     * @return PatchPanel
     */
    public function setConnector($connector)
    {
        $this->connector = $connector;
    
        return $this;
    }

    /**
     * Get connector
     *
     * @return string 
     */
    public function getConnector()
    {
        return $this->connector;
    }

    /**
     * Set duplex_allowed
     *
     * @param boolean $duplexAllowed
     * @return PatchPanel
     */
    public function setDuplexAllowed($duplexAllowed)
    {
        $this->duplex_allowed = $duplexAllowed;
    
        return $this;
    }

    /**
     * Get duplex_allowed
     *
     * @return boolean 
     */
    public function getDuplexAllowed()
    {
        return $this->duplex_allowed;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return PatchPanel
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    
        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set u_position
     *
     * @param integer $uPosition
     * @return PatchPanel
     */
    public function setUPosition($uPosition)
    {
        $this->u_position = $uPosition;
    
        return $this;
    }

    /**
     * Get u_position
     *
     * @return integer 
     */
    public function getUPosition()
    {
        return $this->u_position;
    }

    /**
     * Set installed
     *
     * @param \DateTime $installed
     * @return PatchPanel
     */
    public function setInstalled($installed)
    {
        $this->installed = $installed;
    
        return $this;
    }

    /**
     * Get installed
     *
     * @return \DateTime 
     */
    public function getInstalled()
    {
        return $this->installed;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add PatchPanelPorts
     *
     * @param \Entities\PatchPanelPort $patchPanelPorts
     * @return PatchPanel
     */
    public function addPatchPanelPort(\Entities\PatchPanelPort $patchPanelPorts)
    {
        $this->PatchPanelPorts[] = $patchPanelPorts;
    
        return $this;
    }

    /**
     * Remove PatchPanelPorts
     *
     * @param \Entities\PatchPanelPort $patchPanelPorts
     */
    public function removePatchPanelPort(\Entities\PatchPanelPort $patchPanelPorts)
    {
        $this->PatchPanelPorts->removeElement($patchPanelPorts);
    }

    /**
     * Get PatchPanelPorts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPatchPanelPorts()
    {
        return $this->PatchPanelPorts;
    }

    /**
     * Set Cabinet
     *
     * @param \Entities\Cabinet $cabinet
     * @return PatchPanel
     */
    public function setCabinet(\Entities\Cabinet $cabinet)
    {
        $this->Cabinet = $cabinet;
    
        return $this;
    }

    /**
     * Get Cabinet
     *
     * @return \Entities\Cabinet 
     */
    public function getCabinet()
    {
        return $this->Cabinet;
    }
}